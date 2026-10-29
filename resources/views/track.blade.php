@extends('layouts.app')

@section('title', 'Lacak Status Laporan - Sapa Sumbawa')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <div class="text-center max-w-xl mx-auto mb-10">
        <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center mx-auto mb-3">
            <i data-lucide="search-check" class="w-7 h-7"></i>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
            Lacak Status Pengaduan
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Masukkan Nomor Tiket laporan Anda untuk melihat perkembangan tindak lanjut dari Admin Pemkab dan OPD terkait.
        </p>

        <!-- Search Form -->
        <form action="{{ route('track') }}" method="GET" class="mt-6 flex items-center gap-2">
            <div class="relative flex-1">
                <input type="text" name="ticket" value="{{ $ticket }}" placeholder="Contoh: SBW-202610-0001" required
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:ring-1 focus:ring-blue-600 shadow-2xs font-mono uppercase">
            </div>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-sm transition">
                Cari Tiket
            </button>
        </form>
    </div>

    @if($ticket && !$report)
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-8 text-center text-rose-800">
            <i data-lucide="alert-circle" class="w-10 h-10 text-rose-500 mx-auto mb-2"></i>
            <h3 class="font-bold text-base">Nomor Tiket "{{ $ticket }}" Tidak Ditemukan</h3>
            <p class="text-xs text-rose-600 mt-1 max-w-md mx-auto">
                Pastikan Anda memasukkan kode tiket dengan benar (terdiri dari awalan SBW diikuti tahun bulan dan kode unik).
            </p>
        </div>
    @elseif($report)
        <!-- Report Found -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="p-6 sm:p-8 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-blue-400 font-bold text-sm tracking-wider">
                            #{{ $report->ticket_number }}
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full border {{ $report->status_badge_class }}">
                            {{ $report->status_label }}
                        </span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-bold mt-2 text-white">
                        {{ $report->title }}
                    </h2>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-300 mt-2">
                        <span class="flex items-center gap-1"><i data-lucide="tag" class="w-3.5 h-3.5 text-blue-400"></i> {{ $report->category }}</span>
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-blue-400"></i> Kec. {{ $report->subdistrict }}</span>
                        <span class="flex items-center gap-1"><i data-lucide="clock" class="w-3.5 h-3.5 text-blue-400"></i> {{ $report->created_at->format('d M Y, H:i') }} WITA</span>
                    </div>
                </div>

                @if($report->opd)
                    <div class="bg-white/10 p-3 rounded-2xl border border-white/15 text-xs sm:text-right shrink-0">
                        <p class="text-[11px] text-slate-300">OPD Ditugaskan:</p>
                        <p class="font-bold text-white text-sm">{{ $report->opd->name }}</p>
                        <p class="text-[10px] text-blue-300">Kode: {{ $report->opd->code }}</p>
                    </div>
                @endif
            </div>

            <!-- Content Grid -->
            <div class="p-6 sm:p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Details -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Detail Kejadian / Masalah:</h4>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $report->description }}
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Patokan / Detail Lokasi:</h4>
                        <p class="text-xs text-slate-700 font-medium">{{ $report->location_detail }}</p>
                    </div>

                    <!-- Foto Pengaduan -->
                    @if($report->photo_path)
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Bukti Pengaduan:</h4>
                            <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 max-w-md">
                                <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Laporan" class="w-full h-auto object-cover max-h-72">
                            </div>
                        </div>
                    @endif

                    <!-- Bukti Penanganan Selesai dari OPD -->
                    @if($report->status === 'selesai')
                        <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-5 space-y-3">
                            <div class="flex items-center gap-2 text-blue-900 font-bold text-sm">
                                <i data-lucide="check-circle" class="w-5 h-5 text-blue-600"></i>
                                <span>Hasil Tindak Lanjut oleh {{ $report->opd->short_name ?? 'OPD' }}:</span>
                            </div>
                            <p class="text-xs text-blue-950 leading-relaxed">
                                {{ $report->opd_notes }}
                            </p>
                            @if($report->resolution_photo_path)
                                <div>
                                    <p class="text-[11px] font-semibold text-blue-800 mb-1.5">Foto Bukti Selesai di Lapangan:</p>
                                    <img src="{{ asset('storage/' . $report->resolution_photo_path) }}" alt="Bukti Selesai" class="rounded-xl border border-blue-200 max-h-64 object-cover">
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Right Timeline -->
                <div class="border-t lg:border-t-0 lg:border-l border-slate-200 lg:pl-8">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900 mb-4 flex items-center gap-2">
                        <i data-lucide="git-commit" class="w-4 h-4 text-blue-600"></i>
                        <span>Riwayat Penanganan (Timeline)</span>
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
    @endif
</div>
@endsection
