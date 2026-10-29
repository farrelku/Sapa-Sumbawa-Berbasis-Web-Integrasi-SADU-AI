@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Admin Sapa Sumbawa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar akun warga masyarakat dan pegawai perangkat daerah terdaftar.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
            &larr; Dashboard Admin
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, NIK, atau NIP..."
                class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600">
            <select name="role" class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:outline-hidden focus:border-blue-600">
                <option value="">Semua Peran</option>
                <option value="warga" {{ request('role') == 'warga' ? 'selected' : '' }}>Warga Masyarakat</option>
                <option value="opd" {{ request('role') == 'opd' ? 'selected' : '' }}>Perangkat Daerah (OPD)</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition">
                Cari
            </button>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-[11px] text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5 font-bold">Nama Lengkap & Kontak</th>
                        <th class="px-5 py-3.5 font-bold">Peran Akun</th>
                        <th class="px-5 py-3.5 font-bold">Identitas (NIK / NIP)</th>
                        <th class="px-5 py-3.5 font-bold">Instansi / Kecamatan</th>
                        <th class="px-5 py-3.5 font-bold">Status</th>
                        <th class="px-5 py-3.5 font-bold text-right">Terdaftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900 block text-xs">{{ $user->name }}</span>
                                <span class="text-slate-500 block text-[11px]">{{ $user->email }}</span>
                                @if($user->phone)
                                    <span class="text-slate-400 text-[10px]">{{ $user->phone }}</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($user->isAdmin())
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-slate-900 text-white">
                                        ADMIN PEMKAB
                                    </span>
                                @elseif($user->isOpd())
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        PERANGKAT DAERAH
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        WARGA
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap font-mono text-slate-700">
                                @if($user->isOpd())
                                    NIP: {{ $user->nip ?? '-' }}
                                @else
                                    NIK: {{ $user->nik ?? '-' }}
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                @if($user->isOpd() && $user->opd)
                                    <span class="font-semibold text-blue-900">{{ $user->opd->short_name }}</span>
                                    <span class="text-slate-500 block text-[10px]">{{ $user->position ?? 'Pegawai' }}</span>
                                @else
                                    <span class="text-slate-700">Kec. {{ $user->subdistrict ?? 'Sumbawa' }}</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap text-right text-slate-400 text-[11px]">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
