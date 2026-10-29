@extends('layouts.app')

@section('title', 'Detail Laporan #' . $report->ticket_number . ' - Sapa Sumbawa')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('reports.public') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Daftar Laporan</span>
        </a>
        <a href="{{ route('track', ['ticket' => $report->ticket_number]) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-200">
            <i data-lucide="search" class="w-3.5 h-3.5"></i>
            <span>Lacak Tiket Ini</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 sm:p-8 bg-slate-900 text-white">
            <div class="flex items-center gap-2">
                <span class="font-mono text-blue-400 font-bold text-sm tracking-wider">
                    #{{ $report->ticket_number }}
                </span>
                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full border {{ $report->status_badge_class }}">
                    {{ $report->status_label }}
                </span>
                <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase border {{ $report->urgency_badge_class }}">
                    Urgensi: {{ $report->urgency }}
                </span>
            </div>
            
            <h1 class="text-xl sm:text-2xl font-bold mt-3 text-white">
                {{ $report->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-300 mt-3">
                <span class="flex items-center gap-1"><i data-lucide="tag" class="w-3.5 h-3.5 text-blue-400"></i> {{ $report->category }}</span>
                <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-400"></i> Kec. {{ $report->subdistrict }}</span>
                <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5 text-blue-400"></i> {{ $report->created_at->format('d M Y, H:i') }} WITA</span>
            </div>
        </div>

        <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Info -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Uraian Pengaduan:</h4>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $report->description }}
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Lokasi Detail:</h4>
                    <p class="text-xs text-slate-700 font-medium">{{ $report->location_detail }}</p>
                </div>

                <!-- Foto Laporan -->
                @if($report->photo_path)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Kondisi / Bukti Awal:</h4>
                        <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 max-w-md">
                            <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Laporan" class="w-full h-auto object-cover max-h-72">
                        </div>
                    </div>
                @endif

                <!-- Disposisi Admin -->
                @if($report->opd)
                    <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center gap-2 text-blue-900 font-bold text-xs uppercase tracking-wider">
                            <i data-lucide="send" class="w-4 h-4 text-blue-600"></i>
                            <span>Disposisi ke Perangkat Daerah:</span>
                        </div>
                        <p class="font-bold text-sm text-blue-950">{{ $report->opd->name }} ({{ $report->opd->short_name }})</p>
                        @if($report->admin_notes)
                            <p class="text-xs text-blue-900/90 italic bg-white/70 p-3 rounded-xl border border-blue-100">
                                "{{ $report->admin_notes }}"
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Hasil Penanganan Selesai -->
                @if($report->status === 'selesai')
                    <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-5 space-y-3">
                        <div class="flex items-center gap-2 text-blue-900 font-bold text-sm">
                            <i data-lucide="check-circle" class="w-5 h-5 text-blue-600"></i>
                            <span>Tuntas Dikerjakan oleh OPD Terkait:</span>
                        </div>
                        <p class="text-xs text-blue-950 leading-relaxed">
                            {{ $report->opd_notes }}
                        </p>
                        @if($report->resolution_photo_path)
                            <div class="mt-3">
                                <p class="text-[11px] font-semibold text-blue-800 mb-1.5">Foto Bukti Lapangan Pasca Penanganan:</p>
                                <img src="{{ asset('storage/' . $report->resolution_photo_path) }}" alt="Bukti Selesai" class="rounded-xl border border-blue-200 max-h-64 object-cover">
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Right Timeline -->
            <div class="border-t lg:border-t-0 lg:border-l border-slate-200 lg:pl-8">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="activity" class="w-4 h-4 text-blue-600"></i>
                    <span>Perjalanan Laporan</span>
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
                                <p class="text-[11px] text-slate-500 mt-1 bg-slate-50 p-2 rounded-lg border border-slate-200">
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
