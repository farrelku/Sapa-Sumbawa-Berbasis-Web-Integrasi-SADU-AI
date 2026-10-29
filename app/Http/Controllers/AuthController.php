<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            return $this->redirectBasedOnRole($user)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    // Step 1: Pilihan Registrasi (Warga vs Perangkat Daerah)
    public function showRegisterChoice()
    {
        return view('auth.register-choice');
    }

    // Step 2A: Form Registrasi Warga
    public function showRegisterWarga()
    {
        $districts = config('sumbawa.districts');
        return view('auth.register-warga', compact('districts'));
    }

    public function storeRegisterWarga(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16', 'unique:users,nik'],
            'phone' => ['required', 'string', 'max:20'],
            'subdistrict' => ['required', 'string'],
            'address' => ['required', 'string', 'max:500'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK (Nomor Induk Kependudukan) wajib diisi.',
            'nik.digits' => 'NIK harus berjumlah tepat 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar sebelumnya.',
            'phone.required' => 'Nomor WhatsApp / telepon wajib diisi.',
            'subdistrict.required' => 'Pilih kecamatan tempat tinggal Anda.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini telah digunakan oleh akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'warga',
            'nik' => $validated['nik'],
            'phone' => $validated['phone'],
            'subdistrict' => $validated['subdistrict'],
            'address' => $validated['address'],
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('warga.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang di Sapa Sumbawa.');
    }

    // Step 2B: Form Registrasi Perangkat Daerah (OPD)
    public function showRegisterOpd()
    {
        $opds = Opd::where('is_active', true)->orderBy('name')->get();
        return view('auth.register-opd', compact('opds'));
    }

    public function storeRegisterOpd(Request $request)
    {
        $validated = $request->validate([
            'opd_id' => ['required', 'exists:opds,id'],
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'max:30', 'unique:users,nip'],
            'position' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'opd_id.required' => 'Silakan pilih Instansi / Dinas Anda.',
            'name.required' => 'Nama lengkap pegawai wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP ini sudah terdaftar sebelumnya.',
            'position.required' => 'Jabatan atau bidang penugasan wajib diisi.',
            'phone.required' => 'Nomor kontak / WhatsApp wajib diisi.',
            'email.required' => 'Email kedinasan wajib diisi.',
            'email.unique' => 'Email ini telah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'opd',
            'opd_id' => $validated['opd_id'],
            'nip' => $validated['nip'],
            'position' => $validated['position'],
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('opd.dashboard')->with('success', 'Akun Perangkat Daerah berhasil didaftarkan. Selamat bertugas di Sapa Sumbawa!');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isOpd()) {
            return redirect()->route('opd.dashboard');
        }

        return redirect()->route('warga.dashboard');
    }
}
