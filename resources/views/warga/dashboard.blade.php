@extends('layouts.app')

@section('title', 'Dashboard Warga - Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Welcome Header (Nuansa Primary #052158) -->
    <div class="rounded-3xl p-6 sm:p-8 text-white shadow-md mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6" style="background: linear-gradient(to right, #052158, #0a3575, #052158);">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold text-[#052158]" style="background-color: #1DD8A3;">
                    PORTAL WARGA
                </span>
                <span class="text-xs text-emerald-100">NIK: {{ Auth::user()->nik ?? '-' }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1">
                Kecamatan {{ Auth::user()->subdistrict ?? 'Sumbawa' }} • Sampaikan aspirasi untuk Sumbawa yang lebih baik.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('warga.reports.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-[#052158] shadow-sm transition hover:brightness-110" style="background-color: #1DD8A3;">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>Buat Laporan Baru</span>
            </a>
            <a href="{{ route('warga.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition">
                <i data-lucide="list" class="w-5 h-5"></i>
                <span>Semua Laporan Saya</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total: primary #052158 -->
        <div class="bg-blue-50 p-5 rounded-2xl border border-blue-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-[#052158]">Total Laporan Saya</span>
                <i data-lucide="folder" class="w-4 h-4 text-[#052158]"></i>
            </div>
            <p class="text-2xl font-black text-[#052158]">{{ $stats['total'] }}</p>
        </div>

        <!-- Menunggu: orange -->
        <div class="bg-orange-50 p-5 rounded-2xl border border-orange-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-orange-600">Menunggu Verifikasi</span>
                <i data-lucide="clock" class="w-4 h-4 text-orange-600"></i>
            </div>
            <p class="text-2xl font-black text-orange-600">{{ $stats['menunggu'] }}</p>
        </div>

        <!-- Diproses: indigo (sesuai badge Diteruskan/Diproses) -->
        <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-indigo-700">Diproses oleh OPD</span>
                <i data-lucide="activity" class="w-4 h-4 text-indigo-700"></i>
            </div>
            <p class="text-2xl font-black text-indigo-700">{{ $stats['diproses'] }}</p>
        </div>

        <!-- Selesai: emerald -->
        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-emerald-700">Selesai Ditangani</span>
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-700"></i>
            </div>
            <p class="text-2xl font-black text-emerald-700">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-[#052158]">Laporan Terkini Saya</h2>
                <p class="text-xs text-slate-500">Daftar aspirasi atau pengaduan terakhir yang Anda ajukan</p>
            </div>
            <a href="{{ route('warga.reports.index') }}" class="text-xs font-bold text-[#052158] hover:text-[#1DD8A3] flex items-center gap-1">
                <span>Lihat Semua</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        @if($recentReports->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($recentReports as $report)
                    @php
                        // Mapping warna badge status (presentasi saja, bukan backend)
                        $statusStyles = [
                            'menunggu'   => 'bg-orange-50 text-orange-600 border-orange-200',
                            'diteruskan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'diproses'   => 'bg-teal-50 text-teal-700 border-teal-200',
                            'selesai'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'ditolak'    => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $badgeClass = $statusStyles[$report->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                    @endphp
                    <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/80 -mx-4 px-4 rounded-xl transition">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-500">#{{ $report->ticket_number }}</span>
                                <span class="px-2 py-0.5 text-[11px] font-bold rounded-full border {{ $badgeClass }}">
                                    {{ $report->status_label }}
                                </span>
                                <span class="text-xs text-slate-400">• {{ $report->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="font-bold text-sm text-slate-900">
                                <a href="{{ route('warga.reports.show', $report->id) }}" class="hover:text-[#052158]">
                                    {{ $report->title }}
                                </a>
                            </h3>
                            <div class="flex items-center gap-3 text-xs text-slate-500">
                                <span>Kategori: <strong>{{ $report->category }}</strong></span>
                                <span>•</span>
                                <span>Kec. {{ $report->subdistrict }}</span>
                                @if($report->opd)
                                    <span>•</span>
                                    <span class="text-[#0F766E] font-medium">OPD: {{ $report->opd->short_name }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('warga.reports.show', $report->id) }}" class="px-4 py-2 text-xs font-semibold text-[#052158] bg-[#1DD8A3]/15 hover:bg-[#1DD8A3]/25 rounded-lg border border-[#1DD8A3]/40 transition">
                                Lihat Progres &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-slate-400">
                <i data-lucide="file-question" class="w-12 h-12 mx-auto text-slate-300 mb-2"></i>
                <p class="text-sm font-medium text-slate-600">Anda belum pernah membuat laporan.</p>
                <p class="text-xs text-slate-400 mt-1 mb-4">Laporkan kondisi jalan rusak, penerangan umum mati, atau masalah lainnya di sekitar Anda.</p>
                <a href="{{ route('warga.reports.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-[#052158] rounded-lg shadow-sm hover:brightness-110" style="background-color: #1DD8A3;">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Mulai Buat Laporan Pertama</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection