@extends('layouts.app')

@section('title', 'Detail Laporan #' . $report->ticket_number . ' - Sapa Sumbawa')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('warga.reports.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Laporan Saya</span>
        </a>
        <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
            Tiket: #{{ $report->ticket_number }}
        </span>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <!-- Status Bar Banner -->
        <div class="p-6 bg-slate-900 text-white flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $report->status_badge_class }}">
                        {{ $report->status_label }}
                    </span>
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase border {{ $report->urgency_badge_class }}">
                        Urgensi: {{ $report->urgency }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-white">
                    {{ $report->title }}
                </h1>
                <p class="text-xs text-slate-300 mt-1">
                    Diajukan pada {{ $report->created_at->format('d M Y, H:i') }} WITA
                </p>
            </div>

            @if($report->opd)
                <div class="bg-white/10 p-3.5 rounded-2xl border border-white/10 text-xs text-right">
                    <p class="text-[11px] text-slate-400">Diteruskan ke Instansi:</p>
                    <p class="font-bold text-white text-sm mt-0.5">{{ $report->opd->name }}</p>
                    <p class="text-[11px] text-blue-300">{{ $report->opd->short_name }}</p>
                </div>
            @endif
        </div>

        <!-- Details Grid -->
        <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Metadata Pills -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Kategori</span>
                        <span class="font-bold text-slate-800">{{ $report->category }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Kecamatan</span>
                        <span class="font-bold text-slate-800">Kec. {{ $report->subdistrict }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Status Verifikasi</span>
                        <span class="font-bold text-blue-700">{{ $report->status_label }}</span>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Uraian Masalah:</h4>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $report->description }}
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Patokan Lokasi:</h4>
                    <p class="text-xs text-slate-700 font-medium">{{ $report->location_detail }}</p>
                </div>

                <!-- Foto Bukti dari Warga -->
                @if($report->photo_path)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Bukti Awal Laporan:</h4>
                        <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 max-w-md">
                            <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Laporan" class="w-full h-auto object-cover max-h-80">
                        </div>
                    </div>
                @endif

                <!-- Tanggapan & Bukti Penyelesaian dari OPD -->
                @if($report->status === 'selesai')
                    <div class="bg-blue-50/70 border border-blue-200 rounded-3xl p-6 space-y-4">
                        <div class="flex items-center gap-2 text-blue-900 font-bold text-base">
                            <i data-lucide="check-circle-2" class="w-6 h-6 text-blue-600"></i>
                            <span>Laporan Telah Dituntaskan oleh {{ $report->opd->short_name ?? 'OPD' }}</span>
                        </div>
                        <p class="text-xs text-blue-950 leading-relaxed bg-white/80 p-4 rounded-xl border border-blue-100">
                            {{ $report->opd_notes }}
                        </p>
                        @if($report->resolution_photo_path)
                            <div>
                                <p class="text-xs font-bold text-blue-900 mb-2">Foto Dokumentasi Perbaikan di Lapangan:</p>
                                <img src="{{ asset('storage/' . $report->resolution_photo_path) }}" alt="Bukti Perbaikan" class="rounded-2xl border border-blue-200 max-h-72 object-cover">
                            </div>
                        @endif
                        <p class="text-[11px] text-blue-700 font-medium">
                            Diselesaikan pada: {{ $report->resolved_at ? $report->resolved_at->format('d M Y, H:i') . ' WITA' : '-' }}
                        </p>
                    </div>
                @elseif($report->status === 'ditolak')
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 space-y-2 text-xs text-rose-900">
                        <div class="flex items-center gap-2 font-bold text-sm text-rose-800">
                            <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
                            <span>Laporan Ditolak oleh Admin Pemkab</span>
                        </div>
                        <p class="text-xs text-rose-800 bg-white/80 p-3 rounded-xl border border-rose-100">
                            {{ $report->rejection_reason }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Right Timeline -->
            <div class="border-t lg:border-t-0 lg:border-l border-slate-200 lg:pl-8">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-blue-600"></i>
                    <span>Progres Tindak Lanjut</span>
                </h4>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @foreach($report->timelines as $timeline)
                        <div class="relative text-xs">
                            <div class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                            <p class="text-[10px] text-slate-400 font-medium">
                                {{ $timeline->created_at->format('d M Y, H:i') }} WITA
                            </p>
                            <p class="font-bold text-slate-800 mt-0.5 text-xs">
                                {{ $timeline->title }}
                            </p>
                            @if($timeline->notes)
                                <p class="text-[11px] text-slate-500 mt-1 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                                    {{ $timeline->notes }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
