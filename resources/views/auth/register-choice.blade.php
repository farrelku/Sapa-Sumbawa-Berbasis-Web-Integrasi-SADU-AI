@extends('layouts.app')

@section('title', 'Pilih Jenis Pendaftaran - Sapa Sumbawa')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-800 bg-blue-100 rounded-full mb-3">
            Langkah 1 dari 2
        </span>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
            Pilih Jenis Pendaftaran Akun
        </h1>
        <p class="mt-3 text-base text-slate-600">
            Pilih peran Anda untuk mendapatkan akses dan antarmuka layanan yang sesuai di platform Sapa Sumbawa.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Pilihan 1: Warga Masyarakat -->
        <a href="{{ route('register.warga') }}" class="group relative bg-white border-2 border-slate-200 hover:border-blue-600 rounded-3xl p-8 shadow-xs hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="users" class="w-8 h-8"></i>
                </div>
                <div>
                    <span class="text-xs uppercase font-bold tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                        Masyarakat Umum
                    </span>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2 group-hover:text-blue-700 transition-colors">
                        Saya adalah Warga
                    </h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Daftar sebagai warga masyarakat Kabupaten Sumbawa untuk menyampaikan pengaduan jalan rusak, sampah, penerangan jalan padam, pelayanan publik, serta memantau status tindak lanjutnya.
                </p>

                <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-blue-600"></i>
                        <span>Pelaporan mudah dengan foto & lokasi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-blue-600"></i>
                        <span>Notifikasi & riwayat penanganan realtime</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-blue-600"></i>
                        <span>Akses asisten AI pintar 24 jam</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 flex items-center justify-between text-sm font-bold text-blue-700 group-hover:translate-x-1 transition-transform">
                <span>Lanjut Daftar sebagai Warga</span>
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </div>
        </a>

        <!-- Pilihan 2: Perangkat Daerah (OPD) -->
        <a href="{{ route('register.opd') }}" class="group relative bg-white border-2 border-slate-200 hover:border-indigo-600 rounded-3xl p-8 shadow-xs hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="building-2" class="w-8 h-8"></i>
                </div>
                <div>
                    <span class="text-xs uppercase font-bold tracking-wider text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded">
                        Pemerintah Daerah
                    </span>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2 group-hover:text-indigo-700 transition-colors">
                        Perangkat Daerah (OPD)
                    </h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Khusus bagi staf, pejabat, atau perwakilan instansi dinas di lingkungan Pemkab Sumbawa (PUPR, DISKOMINFO, DISHUB, DLH, dll) untuk menerima disposisi laporan dan mengunggah hasil tindak lanjut.
                </p>

                <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-indigo-600"></i>
                        <span>Pilihan instansi dinas pada langkah berikutnya</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-indigo-600"></i>
                        <span>Terima disposisi tugas dari Admin Pemkab</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-indigo-600"></i>
                        <span>Update progres & upload bukti foto penanganan</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 flex items-center justify-between text-sm font-bold text-indigo-700 group-hover:translate-x-1 transition-transform">
                <span>Pilih Instansi & Daftar OPD</span>
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </div>
        </a>
    </div>

    <!-- Login Link -->
    <div class="mt-12 text-center text-sm text-slate-600">
        Sudah memiliki akun terdaftar? 
        <a href="{{ route('login') }}" class="font-bold text-blue-700 hover:text-blue-800 ml-1">
            Masuk di sini &rarr;
        </a>
    </div>
</div>
@endsection
