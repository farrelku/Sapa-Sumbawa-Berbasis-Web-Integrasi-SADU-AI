@extends('layouts.app')

@section('title', 'Daftar sebagai Perangkat Daerah (OPD) - Sapa Sumbawa')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Pilihan Peran</span>
        </a>
        <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-800 bg-blue-100 rounded-full">
            Langkah 2: Registrasi Perangkat Daerah (OPD)
        </span>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10">
        <div class="mb-8">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3">
                <i data-lucide="building-2" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pendaftaran Akun Perangkat Daerah</h2>
            <p class="text-sm text-slate-500 mt-1">Pilih dinas / instansi penugasan Anda di lingkungan Pemerintah Kabupaten Sumbawa.</p>
        </div>

        <form action="{{ route('register.opd') }}" method="POST" class="space-y-6">
            @csrf

            <!-- PILIHAN INSTANSI / DINAS (OPD) -->
            <div>
                <label for="opd_id" class="block text-sm font-semibold text-slate-800 mb-2">
                    Pilih Instansi / OPD Anda <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <select id="opd_id" name="opd_id" required
                        class="block w-full px-4 py-3 bg-blue-50/50 border border-blue-200 rounded-xl text-sm font-medium text-slate-900 focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition">
                        <option value="">-- Pilih Dinas / Badan Penugasan --</option>
                        @foreach($opds as $opd)
                            <option value="{{ $opd->id }}" {{ old('opd_id') == $opd->id ? 'selected' : '' }}>
                                [{{ $opd->code }}] {{ $opd->name }} ({{ $opd->short_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-blue-500"></i>
                    <span>Tersedia pilihan: PUPR, DISKOMINFO, DISHUB, DLH, SATPOL PP, DINKES, DUKCAPIL, dll.</span>
                </p>
            </div>

            <!-- NIP & Jabatan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nip" class="block text-sm font-semibold text-slate-700 mb-1">
                        NIP Pegawai <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" required
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="Contoh: 19850314 200801 1 003">
                </div>

                <div>
                    <label for="position" class="block text-sm font-semibold text-slate-700 mb-1">
                        Jabatan / Bidang Penugasan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="position" name="position" value="{{ old('position') }}" required
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="Contoh: Tim Respon Aduan / Bidang Bina Marga">
                </div>
            </div>

            <!-- Nama Lengkap Pegawai -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                    placeholder="Contoh: Ahmad Rizki, S.Kom">
            </div>

            <!-- Email Dinas & No WhatsApp -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">
                        Alamat Email Kedinasan / Resmi <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="petugas@sumbawakab.go.id">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nomor Kontak / WhatsApp Aktif <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                        class="block w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="Contoh: 081234567890">
                </div>
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
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-white font-semibold bg-gradient-to-r from-blue-700 to-indigo-700 hover:from-blue-800 hover:to-indigo-800 shadow-md shadow-blue-700/30 hover:shadow-lg transition-all">
                    Daftar Akun Perangkat Daerah Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
