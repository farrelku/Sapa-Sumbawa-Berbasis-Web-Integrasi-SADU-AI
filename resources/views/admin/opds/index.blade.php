@extends('layouts.app')

@section('title', 'Manajemen OPD - Admin Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Perangkat Daerah (OPD) Terdaftar</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar dinas dan badan di lingkungan Pemkab Sumbawa beserta statistik tindak lanjut laporan.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
            &larr; Dashboard Admin
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($opds as $opd)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-100 text-blue-800 tracking-wider">
                            {{ $opd->code }}
                        </span>
                        <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                            {{ $opd->users_count }} Pegawai Terdaftar
                        </span>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900 leading-snug">
                        {{ $opd->name }}
                    </h2>
                    <p class="text-xs font-semibold text-blue-600 mt-0.5">
                        {{ $opd->short_name }}
                    </p>

                    <p class="text-xs text-slate-600 mt-3 line-clamp-3">
                        {{ $opd->description }}
                    </p>

                    <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2 text-center text-xs">
                        <div class="p-2 bg-slate-50 rounded-xl">
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Total Laporan</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $opd->reports_count }}</span>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-xl">
                            <span class="text-blue-700 text-[10px] uppercase font-bold block">Status Aktif</span>
                            <span class="font-bold text-blue-800 text-sm">Terintegrasi</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 truncate max-w-[150px]">{{ $opd->phone ?? '-' }}</span>
                    <a href="{{ route('admin.reports.index', ['opd_id' => $opd->id]) }}" class="font-bold text-blue-600 hover:text-blue-700">
                        Lihat Laporan &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
