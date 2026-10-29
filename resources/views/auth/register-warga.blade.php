@extends('layouts.app')

@section('title', 'Daftar sebagai Warga - Sapa Sumbawa')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Pilihan Peran</span>
        </a>
        <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-800 bg-blue-100 rounded-full">
            Langkah 2: Registrasi Warga
        </span>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10">
        <div class="mb-8">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Formulir Pendaftaran Warga</h2>
            <p class="text-sm text-slate-500 mt-1">Lengkapi data diri Anda untuk mulai menyampaikan laporan dan aspirasi.</p>
        </div>

        <form action="{{ route('register.warga') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                    placeholder="Contoh: Lalu Muhammad Fikri">
            </div>

            <!-- NIK & No HP -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nik" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required maxlength="16"
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="16 digit nomor NIK KTP">
                    <p class="text-[11px] text-slate-400 mt-1">NIK digunakan untuk validasi laporan warga resmi.</p>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nomor WhatsApp / HP <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="Contoh: 081234567890">
                </div>
            </div>

            <!-- Kecamatan di Sumbawa -->
            <div>
                <label for="subdistrict" class="block text-sm font-semibold text-slate-700 mb-1">
                    Kecamatan Domisili (Kab. Sumbawa) <span class="text-rose-500">*</span>
                </label>
                <select id="subdistrict" name="subdistrict" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition">
                    <option value="">-- Pilih Kecamatan di Kabupaten Sumbawa --</option>
                    @foreach($districts as $key => $name)
                        <option value="{{ $key }}" {{ old('subdistrict') == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label for="address" class="block text-sm font-semibold text-slate-700 mb-1">
                    Alamat Lengkap Tempat Tinggal <span class="text-rose-500">*</span>
                </label>
                <textarea id="address" name="address" rows="2" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                    placeholder="Contoh: Jl. Garuda No. 45 RT 02 / RW 01, Kel. Brang Biji">{{ old('address') }}</textarea>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">
                    Alamat Email Aktif <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                    placeholder="email.aktif@gmail.com">
            </div>

            <!-- Password & Confirm -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required minlength="8"
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">
                        Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="Ulangi kata sandi">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-600/30 hover:shadow-lg transition-all">
                    Daftar Sebagai Warga Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
