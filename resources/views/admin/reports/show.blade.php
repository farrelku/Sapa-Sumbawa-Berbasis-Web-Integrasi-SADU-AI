@extends('layouts.app')

@section('title', 'Verifikasi & Disposisi Laporan #' . $report->ticket_number . ' - Admin Sapa Sumbawa')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Manajemen Laporan</span>
        </a>
        <div class="flex items-center gap-2">
            <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                #{{ $report->ticket_number }}
            </span>
            <span class="px-2.5 py-1 text-xs font-bold rounded-full border {{ $report->status_badge_class }}">
                {{ $report->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Detail Pengaduan Warga -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                        <span>Pengaduan Warga</span>
                        <span>•</span>
                        <span class="text-blue-700 bg-blue-50 px-2 py-0.5 rounded">{{ $report->category }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 leading-snug">
                        {{ $report->title }}
                    </h1>
                    <p class="text-xs text-slate-400 mt-1">
                        Diterima sistem pada {{ $report->created_at->format('d F Y, H:i') }} WITA
                    </p>
                </div>

                <!-- Informasi Pelapor -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Nama Pelapor:</span>
                        <span class="font-bold text-slate-800">{{ $report->user->name ?? 'Warga' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">NIK:</span>
                        <span class="font-mono font-medium text-slate-700">{{ $report->user->nik ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kontak WhatsApp:</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $report->user->phone ?? '') }}" target="_blank" class="font-bold text-blue-600 hover:underline flex items-center gap-1">
                            <i data-lucide="phone" class="w-3 h-3"></i>
                            <span>{{ $report->user->phone ?? '-' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Lokasi & Detail Kejadian -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Lokasi Kejadian:</h4>
                    <p class="text-sm font-semibold text-slate-900">Kecamatan {{ $report->subdistrict }}</p>
                    <p class="text-xs text-slate-600 mt-0.5">{{ $report->location_detail }}</p>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Uraian / Kronologi Masalah:</h4>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $report->description }}
                    </div>
                </div>

                <!-- Bukti Foto Warga -->
                @if($report->photo_path)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Bukti dari Pelapor:</h4>
                        <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 max-w-md">
                            <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Laporan" class="w-full h-auto object-cover max-h-80">
                        </div>
                    </div>
                @endif

                <!-- Progres OPD saat ini jika sudah didisposisikan -->
                @if($report->opd)
                    <div class="p-5 bg-blue-50/70 border border-blue-200 rounded-2xl space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-blue-900 text-sm flex items-center gap-1.5">
                                <i data-lucide="building" class="w-4 h-4 text-blue-600"></i>
                                <span>Telah Didisposisikan ke {{ $report->opd->name }}</span>
                            </span>
                            <span class="text-[10px] text-blue-600 font-semibold">{{ $report->dispatched_at ? $report->dispatched_at->diffForHumans() : '' }}</span>
                        </div>
                        <p class="text-blue-950 font-medium">Instruksi Admin: "{{ $report->admin_notes }}"</p>
                        @if($report->opd_notes)
                            <div class="mt-3 pt-3 border-t border-blue-200">
                                <span class="font-bold text-slate-800 block mb-1">Catatan Terakhir OPD:</span>
                                <p class="text-slate-700 bg-white/80 p-3 rounded-xl border border-blue-100">{{ $report->opd_notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs p-6">
                <h3 class="font-bold text-sm text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-blue-600"></i>
                    <span>Log Riwayat Tindakan</span>
                </h3>
                <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200 text-xs">
                    @foreach($report->timelines as $timeline)
                        <div class="relative">
                            <div class="absolute -left-6 top-1 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $timeline->created_at->format('d M Y, H:i') }}</span>
                            <p class="font-bold text-slate-800 text-xs mt-0.5">{{ $timeline->title }}</p>
                            @if($timeline->notes)
                                <p class="text-[11px] text-slate-600 mt-1 bg-slate-50 p-2 rounded-lg border border-slate-200">{{ $timeline->notes }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: AKSI ADMIN (DISPOSISI KE OPD TERKAIT) -->
        <div class="space-y-6">
            
            <!-- FORM DISPOSISI UTAMA ADMIN -->
            <div class="bg-gradient-to-br from-white to-blue-50/50 rounded-3xl border-2 border-blue-500/80 shadow-md p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Disposisi ke OPD</h3>
                        <p class="text-xs text-slate-500">Teruskan laporan ke dinas berwenang</p>
                    </div>
                </div>

                <form action="{{ route('admin.reports.disposisi', $report->id) }}" method="POST" class="space-y-4 text-xs">
                    @csrf

                    <!-- Pilih OPD -->
                    <div>
                        <label for="opd_id" class="block font-bold text-slate-800 mb-1.5">
                            Pilih OPD Penanggung Jawab <span class="text-rose-500">*</span>
                        </label>
                        <select id="opd_id" name="opd_id" required
                            class="block w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:outline-hidden focus:border-blue-600 focus:ring-1 focus:ring-blue-600 shadow-2xs">
                            <option value="">-- Pilih OPD / Dinas Terkait --</option>
                            @foreach($opds as $opd)
                                <option value="{{ $opd->id }}" {{ (old('opd_id') == $opd->id || $report->opd_id == $opd->id) ? 'selected' : '' }}>
                                    [{{ $opd->code }}] {{ $opd->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">
                            Contoh: Laporan jalan/jembatan rusak &rarr; <span class="font-bold text-blue-700">Dinas PUPR</span>.
                        </p>
                    </div>

                    <!-- Sesuaikan Urgensi -->
                    <div>
                        <label for="urgency" class="block font-bold text-slate-800 mb-1.5">
                            Tingkat Urgensi Penanganan
                        </label>
                        <select id="urgency" name="urgency"
                            class="block w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-blue-600">
                            <option value="sedang" {{ $report->urgency == 'sedang' ? 'selected' : '' }}>Sedang (Prioritas Normal)</option>
                            <option value="mendesak" {{ $report->urgency == 'mendesak' ? 'selected' : '' }}>Mendesak (Prioritas Tinggi / Darurat)</option>
                            <option value="rendah" {{ $report->urgency == 'rendah' ? 'selected' : '' }}>Rendah (Penanganan Berkala)</option>
                        </select>
                    </div>

                    <!-- Instruksi Catatan Admin -->
                    <div>
                        <label for="admin_notes" class="block font-bold text-slate-800 mb-1.5">
                            Instruksi Disposisi Admin untuk OPD <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" required
                            class="block w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-blue-600 focus:ring-1 focus:ring-blue-600 shadow-2xs"
                            placeholder="Contoh: Mohon Dinas PUPR bidang Bina Marga segera melakukan survei lapangan dan patching aspal sebelum membahayakan pengendara.">{{ old('admin_notes', $report->admin_notes ?? 'Mohon segera ditindaklanjuti dan lakukan penanganan teknis di lapangan sesuai SOP.') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-md shadow-blue-600/20 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>{{ $report->opd ? 'Perbarui Disposisi OPD' : 'Kirim Disposisi Sekarang' }}</span>
                    </button>
                </form>
            </div>

            <!-- KARTU TOLAK LAPORAN (JIKA TIDAK VALID) -->
            @if($report->status !== 'selesai' && $report->status !== 'ditolak')
                <div class="bg-white rounded-3xl border border-rose-200 shadow-2xs p-6">
                    <h4 class="font-bold text-sm text-rose-800 mb-1 flex items-center gap-1.5">
                        <i data-lucide="x-circle" class="w-4 h-4 text-rose-600"></i>
                        <span>Tolak Pengaduan</span>
                    </h4>
                    <p class="text-xs text-slate-500 mb-4">
                        Gunakan ini jika laporan tidak valid, terindikasi hoaks, atau berada di luar yurisdiksi Kab. Sumbawa.
                    </p>

                    <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak laporan ini?')" class="space-y-3 text-xs">
                        @csrf
                        <div>
                            <textarea name="rejection_reason" rows="2" required placeholder="Tuliskan alasan penolakan yang jelas untuk warga..."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-rose-500 focus:bg-white"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl border border-rose-200 transition">
                            Tolak Laporan Ini
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
