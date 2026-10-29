@extends('layouts.app')

@section('title', 'Laporan Masuk - ' . $opd->short_name . ' - Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Pengaduan Masuk</h1>
            <p class="text-sm text-slate-500 mt-1">Disposisi laporan dari Admin Pemkab yang ditugaskan kepada {{ $opd->name }}.</p>
        </div>
        <a href="{{ route('opd.dashboard') }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
            &larr; Dashboard OPD
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs mb-6">
        <form action="{{ route('opd.reports.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, tiket, atau lokasi..."
                class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600">
            <select name="status" class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600">
                <option value="">Semua Status</option>
                <option value="didisposisikan" {{ request('status') == 'didisposisikan' ? 'selected' : '' }}>Perlu Tindakan (Baru Disposisi)</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Ditangani di Lapangan</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-semibold transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs overflow-hidden">
        @if($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5 font-bold">Tiket & Tanggal Disposisi</th>
                            <th class="px-5 py-3.5 font-bold">Judul & Kategori</th>
                            <th class="px-5 py-3.5 font-bold">Pelapor</th>
                            <th class="px-5 py-3.5 font-bold">Lokasi</th>
                            <th class="px-5 py-3.5 font-bold">Status</th>
                            <th class="px-5 py-3.5 font-bold text-right">Aksi Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($reports as $report)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-mono font-bold text-slate-900 block">#{{ $report->ticket_number }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $report->dispatched_at ? $report->dispatched_at->format('d/m/Y H:i') : $report->created_at->format('d/m/Y') }}</span>
                                </td>

                                <td class="px-5 py-4 max-w-xs">
                                    <a href="{{ route('opd.reports.show', $report->id) }}" class="font-bold text-slate-900 hover:text-blue-700 block truncate">
                                        {{ $report->title }}
                                    </a>
                                    <span class="text-[10px] uppercase font-bold text-slate-400">{{ $report->category }}</span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-medium text-slate-800 block">{{ $report->user->name ?? 'Warga' }}</span>
                                    <span class="text-[10px] text-slate-400">Kec. {{ $report->subdistrict }}</span>
                                </td>

                                <td class="px-5 py-4 text-slate-600 max-w-xs truncate">
                                    {{ $report->location_detail }}
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full border {{ $report->status_badge_class }}">
                                        {{ $report->status_label }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('opd.reports.show', $report->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg text-xs font-semibold shadow-2xs transition">
                                        <span>Proses</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $reports->links() }}
            </div>
        @else
            <div class="text-center py-16 text-slate-400">
                <i data-lucide="inbox" class="w-12 h-12 mx-auto text-slate-300 mb-2"></i>
                <p class="text-sm font-medium text-slate-700">Belum ada laporan yang sesuai.</p>
            </div>
        @endif
    </div>
</div>
@endsection
