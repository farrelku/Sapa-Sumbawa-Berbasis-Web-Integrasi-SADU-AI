@extends('layouts.app')

@section('title', 'Sapa Sumbawa - Portal Pengaduan & Aspirasi Warga')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden text-white py-20">
    <!-- Background Foto -->
    <div class="absolute inset-0">
        <img src="{{ asset('sumbawa.jpg') }}"
             alt="Lanskap Sumbawa"
             class="w-full h-full object-cover object-center">
        <!-- Overlay gradient agar teks terbaca -->
        <div class="absolute inset-0 bg-gradient-to-b from-blue-950/90 via-slate-900/85 to-indigo-950/95"></div>
    </div>

    <!-- Konten -->
    <div class="relative max-w-3xl mx-auto px-4 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-500/30 mb-5 backdrop-blur-sm">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
            Platform Resmi Pemkab Sumbawa
        </span>

        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight drop-shadow-lg">
            Sapa Sumbawa
            <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-sky-100 to-indigo-200 text-2xl sm:text-4xl">
                Suara Warga, Aksi Nyata
            </span>
        </h1>

        <p class="mt-5 text-sm sm:text-base text-slate-200 drop-shadow">
            Laporkan jalan rusak, sampah liar, lampu padam, atau pelayanan lambat. Admin meneruskan langsung ke OPD terkait.
        </p>

        <div class="mt-7 flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('warga.reports.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-500/25 transition">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Laporkan Sekarang
            </a>
            <a href="{{ route('ai.chat.page') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-sm transition">
                <i data-lucide="sparkles" class="w-5 h-5 text-blue-400"></i>
                Konsultasi SADU AI
            </a>
        </div>

        <!-- Ticket Tracker -->
        <form action="{{ route('track') }}" method="GET" class="mt-8 max-w-md mx-auto relative flex items-center">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-300">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input type="text" name="ticket" placeholder="Lacak nomor tiket: SBW-202610-0001" required
                class="w-full pl-11 pr-28 py-3 bg-white/10 border border-white/20 rounded-xl text-sm text-white placeholder-slate-300 focus:outline-hidden focus:bg-white/20 focus:border-blue-400 backdrop-blur-md transition">
            <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-lg text-xs transition">
                Lacak
            </button>
        </form>
    </div>
</section>

<!-- Stats -->


<!-- Alur 4 Langkah -->
<section class="py-14 max-w-5xl mx-auto px-4">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-extrabold text-slate-900">Bagaimana Cara Kerjanya?</h2>
        <p class="text-sm text-slate-500 mt-1">Transparan dari pelaporan sampai selesai.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['01', 'Warga Melapor', 'Isi formulir, pilih kecamatan, unggah foto bukti.'],
            ['02', 'Verifikasi', 'Admin validasi dan disposisi ke OPD terkait.'],
            ['03', 'Tindak Lanjut', 'OPD turun ke lapangan dan update progres.'],
            ['04', 'Selesai', 'Laporan tuntas dengan bukti foto hasil kerja.'],
        ] as [$num, $title, $desc])
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-800 font-black text-xs flex items-center justify-center mb-3">
                    {{ $num }}
                </div>
                <h3 class="font-bold text-sm text-slate-900">{{ $title }}</h3>
                <p class="text-[11px] text-slate-600 mt-1 leading-relaxed">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</section>

<!-- Kategori -->
<section class="py-10 bg-slate-100/70 border-y border-slate-200">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-end justify-between mb-5">
            <h2 class="text-xl font-extrabold text-slate-900">Kategori Pengaduan</h2>
            <a href="{{ route('warga.reports.create') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center gap-1">
                Buat Pengaduan <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($categories as $name => $meta)
                <a href="{{ route('warga.reports.create') }}" class="bg-white p-4 rounded-xl border border-slate-200 hover:border-blue-500 hover:shadow-md transition group">
                    <i data-lucide="folder" class="w-5 h-5 text-blue-600 mb-2"></i>
                    <h3 class="font-bold text-xs text-slate-900 group-hover:text-blue-700">{{ $name }}</h3>
                    <p class="text-[10px] text-slate-500 mt-1 line-clamp-2">{{ $meta['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>


<!-- Direktori OPD -->
<section class="py-10 bg-slate-900 text-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <h2 class="text-xl font-extrabold">OPD Terintegrasi</h2>
            <p class="text-xs text-slate-400 mt-1">Laporan disalurkan sesuai tupoksi instansi.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($opds as $opd)
                <div class="bg-slate-800/80 p-4 rounded-xl border border-slate-700/80 hover:border-blue-500/50 transition">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-500/20 text-blue-300 flex items-center justify-center shrink-0">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-[9px] font-bold text-blue-400 uppercase">{{ $opd->code }}</span>
                            <h3 class="font-bold text-xs text-white truncate">{{ $opd->short_name }}</h3>
                            <p class="text-[10px] text-slate-400 mt-1 line-clamp-2">{{ $opd->description }}</p>
                            <p class="text-[10px] text-slate-300 mt-2">📋 {{ $opd->reports_count }} Laporan</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection