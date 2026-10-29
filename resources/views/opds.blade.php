@extends('layouts.app')

@section('title', 'Direktori OPD Kabupaten Sumbawa - Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="max-w-3xl mb-10">
        <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Pemerintah Kabupaten Sumbawa</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-1">
            Direktori Perangkat Daerah (OPD)
        </h1>
        <p class="text-sm text-slate-600 mt-2 leading-relaxed">
            Daftar Organisasi Perangkat Daerah di lingkup Pemkab Sumbawa yang bertanggung jawab menindaklanjuti setiap aspirasi dan aduan fasilitas umum warga.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($opds as $opd)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs hover:shadow-lg transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-100 text-blue-800 tracking-wider">
                            {{ $opd->code }}
                        </span>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                            {{ $opd->reports_count }} Pengaduan Ditangani
                        </span>
                    </div>

                    <h2 class="text-lg font-bold text-slate-900 leading-snug">
                        {{ $opd->name }}
                    </h2>
                    <p class="text-xs font-semibold text-blue-700 mt-0.5">
                        {{ $opd->short_name }}
                    </p>

                    <p class="text-xs text-slate-600 mt-3 leading-relaxed">
                        {{ $opd->description }}
                    </p>

                    <!-- Responsible Categories -->
                    @if($opd->responsible_categories)
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Fokus Penanganan:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($opd->responsible_categories as $cat)
                                    <span class="text-[10px] font-medium bg-slate-100 text-slate-700 px-2 py-0.5 rounded">
                                        {{ $cat }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 space-y-1.5 text-xs text-slate-500">
                    @if($opd->address)
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                            <span class="truncate">{{ $opd->address }}</span>
                        </div>
                    @endif
                    @if($opd->email)
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                            <span class="truncate">{{ $opd->email }}</span>
                        </div>
                    @endif
                    @if($opd->phone)
                        <div class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                            <span>{{ $opd->phone }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
