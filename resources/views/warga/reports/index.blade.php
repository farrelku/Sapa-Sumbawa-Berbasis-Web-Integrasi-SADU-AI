@extends('layouts.app')

@section('title', 'Riwayat Laporan Saya - Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#052158] tracking-tight">Riwayat Pengaduan Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau seluruh laporan masalah yang telah Anda kirimkan.</p>
        </div>
        <a href="{{ route('warga.reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-[#052158] shadow-sm transition hover:brightness-110" style="background-color: #1DD8A3;">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Buat Laporan Baru</span>
        </a>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs mb-6">
        <form action="{{ route('warga.reports.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor tiket atau judul..."
                class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#1DD8A3] focus:ring-2 focus:ring-[#1DD8A3]/30 focus:bg-white">
            <select name="status" class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-[#1DD8A3] focus:ring-2 focus:ring-[#1DD8A3]/30 focus:bg-white">
                <option value="">Semua Status</option>
                <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                <option value="didisposisikan" {{ request('status') == 'didisposisikan' ? 'selected' : '' }}>Diteruskan ke OPD</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Ditangani</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 font-semibold rounded-lg text-xs text-[#052158] transition hover:brightness-110" style="background-color: #1DD8A3;">
                Cari
            </button>
        </form>
    </div>

    <!-- Reports Table / List -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs overflow-hidden">
        @if($reports->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($reports as $report)
                    @php
                        // Mapping warna badge status (presentasi saja, bukan backend)
                        $statusStyles = [
                            'menunggu_verifikasi' => 'bg-orange-50 text-orange-600 border-orange-200',
                            'didisposisikan'      => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'diproses'            => 'bg-teal-50 text-teal-700 border-teal-200',
                            'selesai'             => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'ditolak'             => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $badgeClass = $statusStyles[$report->status] ?? 'bg-slate-50 text-slate-600 border-slate-200';

                        // Mapping warna badge urgensi (presentasi saja, bukan backend)
                        $urgencyStyles = [
                            'rendah'  => 'bg-slate-50 text-slate-600 border-slate-200',
                            'sedang'  => 'bg-amber-50 text-amber-700 border-amber-200',
                            'tinggi'  => 'bg-orange-50 text-orange-600 border-orange-200',
                            'darurat' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $urgencyClass = $urgencyStyles[strtolower($report->urgency ?? '')] ?? 'bg-slate-50 text-slate-600 border-slate-200';
                    @endphp
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1.5 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-500">#{{ $report->ticket_number }}</span>
                                <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-full border {{ $badgeClass }}">
                                    {{ $report->status_label }}
                                </span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase border {{ $urgencyClass }}">
                                    {{ $report->urgency }}
                                </span>
                            </div>

                            <h3 class="font-bold text-base text-slate-900">
                                <a href="{{ route('warga.reports.show', $report->id) }}" class="hover:text-[#052158]">
                                    {{ $report->title }}
                                </a>
                            </h3>

                            <p class="text-xs text-slate-600 line-clamp-2">
                                {{ $report->description }}
                            </p>

                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 pt-1">
                                <span>Kategori: <strong>{{ $report->category }}</strong></span>
                                <span>•</span>
                                <span>Kec. {{ $report->subdistrict }}</span>
                                @if($report->opd)
                                    <span>•</span>
                                    <span class="text-[#0F766E] font-semibold">OPD Ditugaskan: {{ $report->opd->short_name }}</span>
                                @endif
                                <span>•</span>
                                <span>{{ $report->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('warga.reports.show', $report->id) }}" class="px-4 py-2.5 text-[#052158] rounded-xl text-xs font-semibold shadow-2xs transition flex items-center gap-1.5 hover:brightness-110" style="background-color: #1DD8A3;">
                                <span>Pantau Progres</span>
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $reports->links() }}
            </div>
        @else
            <div class="text-center py-16 text-slate-400">
                <i data-lucide="inbox" class="w-12 h-12 mx-auto text-slate-300 mb-2"></i>
                <p class="text-sm font-medium text-slate-700">Tidak ada laporan yang ditemukan.</p>
                <a href="{{ route('warga.reports.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-[#052158] text-xs font-bold rounded-lg shadow-sm hover:brightness-110" style="background-color: #1DD8A3;">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Buat Laporan Sekarang</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection