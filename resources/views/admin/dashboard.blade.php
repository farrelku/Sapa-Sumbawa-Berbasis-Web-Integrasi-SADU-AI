@extends('layouts.app')

@section('title', 'Dashboard Admin Pemkab - Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Admin Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 rounded-3xl p-6 sm:p-8 text-white shadow-md mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-500 text-white uppercase tracking-wider">
                    PANEL ADMINISTRATOR PEMKAB
                </span>
                <span class="text-xs text-blue-200">Sekretariat Daerah Kab. Sumbawa</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Pusat Kendali & Disposisi Aduan Warga
            </h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl">
                Pantau seluruh pengaduan warga, verifikasi keabsahan laporan, dan teruskan (disposisikan) ke dinas terkait (PUPR, Dishub, DLH, dll).
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-500 shadow-sm transition">
                <i data-lucide="layers" class="w-5 h-5"></i>
                <span>Kelola Semua Laporan</span>
            </a>
            <a href="{{ route('admin.opds.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl font-semibold text-slate-200 bg-white/10 hover:bg-white/20 border border-white/15 transition">
                <i data-lucide="building" class="w-5 h-5"></i>
                <span>Data OPD</span>
            </a>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-500">Total Masuk</span>
                <i data-lucide="inbox" class="w-4 h-4 text-slate-400"></i>
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-amber-50/80 p-5 rounded-2xl border border-amber-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-amber-800">Menunggu Disposisi</span>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
            </div>
            <p class="text-2xl font-black text-amber-700">{{ $stats['menunggu'] }}</p>
            <span class="text-[10px] text-amber-800 font-semibold block mt-1">Perlu Diteruskan ke OPD Segera</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-blue-600">Didisposisikan</span>
                <i data-lucide="send" class="w-4 h-4 text-blue-500"></i>
            </div>
            <p class="text-2xl font-black text-blue-600">{{ $stats['didisposisikan'] }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-purple-600">Diproses OPD</span>
                <i data-lucide="refresh-cw" class="w-4 h-4 text-purple-500"></i>
            </div>
            <p class="text-2xl font-black text-purple-600">{{ $stats['diproses'] }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-emerald-600">Selesai Ditangani</span>
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
            </div>
            <p class="text-2xl font-black text-emerald-600">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    <!-- Reports Needing Disposisi (PRIORITAS ADMIN) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs p-6 sm:p-8 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-2">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-slate-900">Pengaduan Baru yang Perlu Disposisi ke OPD</h2>
                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $pendingReports->count() }} Menunggu
                    </span>
                </div>
                <p class="text-xs text-slate-500">Tugas utama admin: Verifikasi dan teruskan pengaduan ini ke dinas yang berwenang (PUPR, Dishub, dll).</p>
            </div>
            <a href="{{ route('admin.reports.index', ['status' => 'menunggu_verifikasi']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                <span>Lihat Semua Antrean</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        @if($pendingReports->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pendingReports as $report)
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

                            <p class="text-xs text-slate-600 line-clamp-3">
                                {{ $report->description }}
                            </p>

                            <div class="pt-2 text-[11px] text-slate-500 space-y-1">
                                <p><span class="font-semibold text-slate-700">Pelapor:</span> {{ $report->user->name ?? 'Warga' }}</p>
                                <p><span class="font-semibold text-slate-700">Kategori:</span> {{ $report->category }}</p>
                                <p><span class="font-semibold text-slate-700">Lokasi:</span> Kec. {{ $report->subdistrict }}</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between gap-2">
                            <span class="text-[11px] text-slate-400">{{ $report->created_at->diffForHumans() }}</span>
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="px-3.5 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg text-xs font-bold shadow-2xs transition flex items-center gap-1.5">
                                <span>Disposisikan ke OPD</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-slate-400">
                <i data-lucide="check-circle" class="w-12 h-12 text-emerald-500 mx-auto mb-2"></i>
                <p class="text-sm font-semibold text-slate-800">Semua Laporan Telah Didisposisikan!</p>
                <p class="text-xs text-slate-500 mt-1">Tidak ada pengaduan warga yang tertunda di antrean verifikasi saat ini.</p>
            </div>
        @endif
    </div>

    <!-- OPD Distribution & Performance Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs p-6 sm:p-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Distribusi Beban Kerja OPD Kabupaten Sumbawa</h2>
                <p class="text-xs text-slate-500">Jumlah laporan yang telah didisposisikan kepada masing-masing instansi</p>
            </div>
            <a href="{{ route('admin.opds.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                Kelola Data OPD &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-[11px] text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-4 py-3 font-bold">Kode / Nama OPD</th>
                        <th class="px-4 py-3 font-bold">Total Disposisi</th>
                        <th class="px-4 py-3 font-bold">Sedang Diproses</th>
                        <th class="px-4 py-3 font-bold">Selesai Ditangani</th>
                        <th class="px-4 py-3 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($opds as $opd)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-900 text-xs">{{ $opd->short_name }}</span>
                                <span class="text-slate-500 block text-[11px] truncate max-w-sm">{{ $opd->name }}</span>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-800">
                                {{ $opd->reports_count }}
                            </td>
                            <td class="px-4 py-3.5 font-bold text-purple-700">
                                {{ $opd->pending_reports_count }}
                            </td>
                            <td class="px-4 py-3.5 font-bold text-emerald-700">
                                {{ $opd->completed_reports_count }}
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('admin.reports.index', ['opd_id' => $opd->id]) }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    Lihat Laporan &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
