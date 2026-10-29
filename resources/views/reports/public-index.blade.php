@extends('layouts.app')

@section('title', 'Daftar Laporan Warga - Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Laporan Warga</h1>
            <p class="text-sm text-slate-500 mt-1">Transparansi aspirasi dan pengaduan masyarakat di seluruh wilayah Kabupaten Sumbawa.</p>
        </div>
        <a href="{{ route('warga.reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Buat Laporan Baru</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs mb-8">
        <form action="{{ route('reports.public') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block font-semibold text-slate-600 mb-1">Cari Kata Kunci</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, nomor tiket, atau lokasi..." 
                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600 focus:bg-white">
            </div>

            <!-- Status -->
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Status Penanganan</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600 focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="didisposisikan" {{ request('status') == 'didisposisikan' ? 'selected' : '' }}>Diteruskan ke OPD</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Ditangani</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block font-semibold text-slate-600 mb-1">Kategori</label>
                <select name="category" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600 focus:bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $name => $meta)
                        <option value="{{ $name }}" {{ request('category') == $name ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs transition">
                    Filter
                </button>
                <a href="{{ route('reports.public') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-lg text-xs transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-2">
                        <span class="font-mono text-xs font-bold text-slate-500">
                            #{{ $report->ticket_number }}
                        </span>
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full border {{ $report->status_badge_class }}">
                            {{ $report->status_label }}
                        </span>
                    </div>

                    <div class="p-5 space-y-3">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <span class="font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                                {{ $report->category }}
                            </span>
                            <span>•</span>
                            <span>Kec. {{ $report->subdistrict }}</span>
                        </div>

                        <h3 class="font-bold text-slate-900 text-base line-clamp-2 hover:text-blue-700 transition">
                            <a href="{{ route('reports.public.show', $report->ticket_number) }}">
                                {{ $report->title }}
                            </a>
                        </h3>

                        <p class="text-xs text-slate-600 line-clamp-3">
                            {{ $report->description }}
                        </p>

                        @if($report->opd)
                            <div class="pt-3 border-t border-slate-100 text-xs flex items-center gap-2 text-slate-600">
                                <i data-lucide="building" class="w-4 h-4 text-blue-600 shrink-0"></i>
                                <span class="font-medium text-slate-800 truncate">OPD: {{ $report->opd->short_name }}</span>
                            </div>
                        @else
                            <div class="pt-3 border-t border-slate-100 text-xs text-amber-700 font-medium flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span>Menunggu Verifikasi & Disposisi</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400">
                        {{ $report->created_at->diffForHumans() }}
                    </span>
                    <a href="{{ route('reports.public.show', $report->ticket_number) }}" class="font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1">
                        <span>Lihat Detail</span>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-2xl border border-slate-200 text-slate-500">
                <i data-lucide="search-x" class="w-12 h-12 mx-auto text-slate-300 mb-2"></i>
                <p>Tidak ada laporan yang sesuai dengan kriteria filter.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $reports->links() }}
    </div>
</div>
@endsection
