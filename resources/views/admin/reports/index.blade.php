@extends('layouts.app')

@section('title', 'Semua Pengaduan Warga - Admin Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengaduan Warga</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar seluruh aspirasi dan laporan masyarakat se-Kabupaten Sumbawa.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                &larr; Dashboard Admin
            </a>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-2xs mb-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <!-- Search -->
            <div class="sm:col-span-2">
                <label class="block font-semibold text-slate-600 mb-1">Cari Laporan</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, tiket, atau lokasi..."
                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="didisposisikan" {{ request('status') == 'didisposisikan' ? 'selected' : '' }}>Didisposisikan ke OPD</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Diproses OPD</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Filter OPD -->
            <div>
                <label class="block font-semibold text-slate-600 mb-1">OPD Tujuan</label>
                <select name="opd_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600">
                    <option value="">Semua OPD</option>
                    @foreach($opds as $opd)
                        <option value="{{ $opd->id }}" {{ request('opd_id') == $opd->id ? 'selected' : '' }}>
                            {{ $opd->short_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg text-xs transition">
                    Terapkan
                </button>
                <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-lg text-xs transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reports Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs overflow-hidden">
        @if($reports->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[11px] text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-5 py-3.5 font-bold">Nomor Tiket & Tanggal</th>
                            <th class="px-5 py-3.5 font-bold">Judul & Kategori</th>
                            <th class="px-5 py-3.5 font-bold">Pelapor</th>
                            <th class="px-5 py-3.5 font-bold">Kecamatan</th>
                            <th class="px-5 py-3.5 font-bold">OPD Ditugaskan</th>
                            <th class="px-5 py-3.5 font-bold">Status</th>
                            <th class="px-5 py-3.5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($reports as $report)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-mono font-bold text-slate-900 block">#{{ $report->ticket_number }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                                </td>

                                <td class="px-5 py-4 max-w-xs">
                                    <a href="{{ route('admin.reports.show', $report->id) }}" class="font-bold text-slate-900 hover:text-blue-600 block truncate">
                                        {{ $report->title }}
                                    </a>
                                    <span class="text-[11px] text-slate-500 font-medium">{{ $report->category }}</span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-medium text-slate-800 block">{{ $report->user->name ?? 'Warga' }}</span>
                                    <span class="text-[10px] text-slate-400">NIK: {{ $report->user->nik ?? '-' }}</span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-slate-600">
                                    Kec. {{ $report->subdistrict }}
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($report->opd)
                                        <span class="inline-flex items-center gap-1 font-semibold text-blue-800 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-200">
                                            <i data-lucide="building" class="w-3 h-3 text-blue-600"></i>
                                            {{ $report->opd->short_name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                            <i data-lucide="alert-circle" class="w-3 h-3 text-amber-600"></i>
                                            Belum Disposisi
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-full border {{ $report->status_badge_class }}">
                                        {{ $report->status_label }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.reports.show', $report->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold shadow-2xs transition">
                                        <span>Kelola</span>
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
                <p class="text-sm font-medium text-slate-700">Tidak ada laporan yang sesuai.</p>
            </div>
        @endif
    </div>
</div>
@endsection
