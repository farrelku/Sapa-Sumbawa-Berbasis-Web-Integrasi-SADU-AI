@extends('layouts.app')

@section('title', 'Dashboard OPD - ' . $opd->short_name . ' - Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- OPD Header Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-md mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500 text-white uppercase tracking-wider">
                    PORTAL PERANGKAT DAERAH (OPD)
                </span>
                <span class="text-xs text-blue-200">Kode: {{ $opd->code }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                {{ $opd->name }}
            </h1>
            <p class="text-xs sm:text-sm text-blue-100 mt-1">
                Petugas: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->position ?? 'Pegawai OPD' }}) • NIP: {{ Auth::user()->nip ?? '-' }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('opd.reports.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-slate-900 bg-blue-400 hover:bg-blue-300 shadow-sm transition">
                <i data-lucide="inbox" class="w-5 h-5"></i>
                <span>Lihat Semua Laporan Masuk</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-500">Total Didisposisikan</span>
                <i data-lucide="layers" class="w-4 h-4 text-slate-400"></i>
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-blue-50/70 p-5 rounded-2xl border border-blue-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-blue-800">Perlu Tindakan Cepat</span>
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-ping"></span>
            </div>
            <p class="text-2xl font-black text-blue-700">{{ $stats['didisposisikan'] }}</p>
            <span class="text-[10px] text-blue-800 font-semibold block mt-1">Menunggu Penanganan Tim</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-purple-600">Sedang Dikerjakan</span>
                <i data-lucide="tool" class="w-4 h-4 text-purple-500"></i>
            </div>
            <p class="text-2xl font-black text-purple-600">{{ $stats['diproses'] }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-emerald-600">Tuntas Selesai</span>
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
            </div>
            <p class="text-2xl font-black text-emerald-600">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    <!-- Action Required Reports -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs p-6 sm:p-8 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-2">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Laporan Menunggu Tindak Lanjut dari {{ $opd->short_name }}</h2>
                <p class="text-xs text-slate-500">Laporan warga yang telah didisposisikan oleh Admin Pemkab kepada instansi Anda.</p>
            </div>
            <a href="{{ route('opd.reports.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center gap-1">
                <span>Buka Semua Laporan</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        @if($pendingActionReports->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($pendingActionReports as $report)
                    <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200 hover:border-blue-500 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono text-xs font-bold text-slate-500">#{{ $report->ticket_number }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase border {{ $report->urgency_badge_class }}">
                                    {{ $report->urgency }}
                                </span>
                            </div>

                            <h3 class="font-bold text-slate-900 text-sm line-clamp-2">
                                {{ $report->title }}
                            </h3>

                            <div class="p-2.5 rounded-xl bg-blue-50/80 border border-blue-100 text-[11px] text-blue-900">
                                <span class="font-bold block">Instruksi Admin:</span>
                                <p class="italic line-clamp-2">{{ $report->admin_notes ?? 'Segera cek dan tindak lanjuti.' }}</p>
                            </div>

                            <div class="text-[11px] text-slate-500 space-y-0.5 pt-1">
                                <p>📍 <span class="font-medium">Lokasi:</span> Kec. {{ $report->subdistrict }}</p>
                                <p>👤 <span class="font-medium">Pelapor:</span> {{ $report->user->name ?? 'Warga' }}</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between gap-2">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full border {{ $report->status_badge_class }}">
                                {{ $report->status_label }}
                            </span>
                            <a href="{{ route('opd.reports.show', $report->id) }}" class="px-3.5 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-semibold shadow-2xs transition flex items-center gap-1">
                                <span>Tindak Lanjuti</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-slate-400">
                <i data-lucide="smile" class="w-12 h-12 text-emerald-500 mx-auto mb-2"></i>
                <p class="text-sm font-semibold text-slate-800">Luar biasa! Tidak ada laporan pending.</p>
                <p class="text-xs text-slate-500 mt-1">Semua disposisi laporan ke {{ $opd->short_name }} telah ditangani dengan baik.</p>
            </div>
        @endif
    </div>
</div>
@endsection
