@extends('layouts.app')

@section('title', 'Tindak Lanjut Laporan #' . $report->ticket_number . ' - ' . $opd->short_name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('opd.reports.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Laporan Masuk</span>
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
        
        <!-- Left: Detail Pengaduan & Instruksi Disposisi -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
                <!-- Header -->
                <div>
                    <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">
                        <span>Laporan Disposisi untuk {{ $opd->short_name }}</span>
                        <span>•</span>
                        <span class="text-blue-700 bg-blue-50 px-2 py-0.5 rounded">{{ $report->category }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900 leading-snug">
                        {{ $report->title }}
                    </h1>
                </div>

                <!-- KOTAK INSTRUKSI ADMIN PEMKAB -->
                <div class="p-5 bg-gradient-to-r from-blue-50 to-indigo-50/70 border-2 border-blue-200 rounded-2xl space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-900 flex items-center gap-1.5">
                            <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                            <span>Catatan Instruksi dari Admin Pemkab:</span>
                        </span>
                        <span class="text-[11px] text-blue-600 font-semibold">{{ $report->dispatched_at ? $report->dispatched_at->format('d M Y, H:i') : '' }}</span>
                    </div>
                    <p class="text-sm font-medium text-blue-950 italic bg-white/80 p-3.5 rounded-xl border border-blue-100">
                        "{{ $report->admin_notes ?? 'Segera lakukan pengecekan dan penanganan teknis di lokasi.' }}"
                    </p>
                    <div class="text-[11px] text-blue-800 flex items-center gap-2 pt-1">
                        <span>Didisposisikan oleh: <strong>{{ $report->assignedBy->name ?? 'Admin Pemkab' }}</strong></span>
                        <span>•</span>
                        <span class="font-bold uppercase text-rose-600">Urgensi: {{ $report->urgency }}</span>
                    </div>
                </div>

                <!-- Informasi Pelapor & Kontak -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Warga Pelapor:</span>
                        <span class="font-bold text-slate-800">{{ $report->user->name ?? 'Warga' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Kecamatan:</span>
                        <span class="font-semibold text-slate-700">Kec. {{ $report->subdistrict }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">WhatsApp Pelapor:</span>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $report->user->phone ?? '') }}" target="_blank" class="font-bold text-emerald-700 hover:underline flex items-center gap-1">
                            <i data-lucide="phone" class="w-3 h-3"></i>
                            <span>{{ $report->user->phone ?? '-' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Lokasi & Uraian Masalah -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Patokan / Detail Titik Lokasi:</h4>
                    <p class="text-sm font-semibold text-slate-900">{{ $report->location_detail }}</p>
                </div>

                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Uraian / Kronologi dari Warga:</h4>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $report->description }}
                    </div>
                </div>

                <!-- Foto Awal dari Warga -->
                @if($report->photo_path)
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Foto Kondisi / Masalah Awal:</h4>
                        <div class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 max-w-md">
                            <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Awal" class="w-full h-auto object-cover max-h-80">
                        </div>
                    </div>
                @endif

                <!-- Jika Sudah Selesai Ditangani -->
                @if($report->status === 'selesai')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-6 space-y-4">
                        <div class="flex items-center gap-2 text-emerald-900 font-bold text-base">
                            <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-600"></i>
                            <span>Laporan Telah Berhasil Diselesaikan</span>
                        </div>
                        <p class="text-xs text-emerald-950 bg-white/80 p-4 rounded-xl border border-emerald-100 leading-relaxed">
                            {{ $report->opd_notes }}
                        </p>
                        @if($report->resolution_photo_path)
                            <div>
                                <p class="text-xs font-bold text-emerald-900 mb-2">Foto Dokumentasi Hasil Penanganan Lapangan:</p>
                                <img src="{{ asset('storage/' . $report->resolution_photo_path) }}" alt="Hasil Penanganan" class="rounded-2xl border border-emerald-200 max-h-72 object-cover">
                            </div>
                        @endif
                        <p class="text-[11px] text-emerald-700">
                            Waktu Penyelesaian: {{ $report->resolved_at ? $report->resolved_at->format('d M Y, H:i') . ' WITA' : '-' }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xs p-6">
                <h3 class="font-bold text-sm text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="history" class="w-4 h-4 text-blue-600"></i>
                    <span>Log Riwayat Penanganan</span>
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

        <!-- Right: PANEL TINDAK LANJUT OPD -->
        <div class="space-y-6">
            
            @if($report->status !== 'selesai')
                <!-- ACTION 1: MULAI PROSES / TURUNKAN TIM -->
                @if($report->status === 'didisposisikan')
                    <div class="bg-white rounded-3xl border border-blue-200 shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                                <i data-lucide="play" class="w-4 h-4"></i>
                            </div>
                            <h3 class="font-bold text-sm text-slate-900">Mulai Penanganan</h3>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">
                            Ubah status menjadi <span class="font-semibold text-purple-700">"Sedang Ditangani"</span> untuk menginformasikan kepada warga bahwa tim OPD Anda sedang bekerja.
                        </p>

                        <form action="{{ route('opd.reports.process', $report->id) }}" method="POST" class="space-y-3 text-xs">
                            @csrf
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Catatan Tindak Lanjut Awal:</label>
                                <textarea name="process_notes" rows="2" required
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-blue-600 focus:bg-white"
                                    placeholder="Contoh: Tim teknis lapangan {{ $opd->short_name }} telah diberangkatkan untuk melakukan pengecekan dan penanganan."></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-purple-700 hover:bg-purple-800 text-white font-bold rounded-xl shadow-xs transition">
                                Update ke "Sedang Ditangani"
                            </button>
                        </form>
                    </div>
                @endif

                <!-- ACTION 2: SELESAIKAN LAPORAN & UPLOAD BUKTI -->
                <div class="bg-gradient-to-br from-white to-emerald-50/60 rounded-3xl border-2 border-emerald-500 shadow-md p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-sm text-slate-900">Tandai Selesai & Unggah Bukti</h3>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">
                        Pekerjaan di lapangan telah rampung? Unggah foto bukti hasil kerja dan berikan rincian tindakan.
                    </p>

                    <form action="{{ route('opd.reports.resolve', $report->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                        @csrf
                        
                        <!-- Rincian Tindakan -->
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">
                                Rincian Hasil Penanganan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="resolution_notes" rows="3" required
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs focus:outline-hidden focus:border-emerald-600 shadow-2xs"
                                placeholder="Contoh: Tim {{ $opd->short_name }} telah melakukan penambalan aspal hotmix dan pemadatan di sepanjang titik kerusakan jalan tersebut. Lalu lintas kembali normal."></textarea>
                        </div>

                        <!-- Foto Bukti Penanganan -->
                        <div>
                            <label class="block font-bold text-slate-800 mb-1">
                                Unggah Foto Bukti Lapangan Selesai
                            </label>
                            <input type="file" name="resolution_photo" accept="image/*"
                                class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                            <p class="text-[11px] text-slate-400 mt-1">Foto aspal yang sudah ditambal / sampah yang sudah bersih, dll.</p>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 hover:shadow-lg transition flex items-center justify-center gap-1.5">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span>Selesaikan Laporan Ini</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-emerald-50 rounded-3xl border border-emerald-200 p-6 text-center text-emerald-900">
                    <i data-lucide="award" class="w-12 h-12 text-emerald-600 mx-auto mb-2"></i>
                    <h4 class="font-bold text-base">Tugas Tuntas!</h4>
                    <p class="text-xs text-emerald-800 mt-1">
                        Pengaduan ini telah berhasil diselesaikan oleh {{ $opd->short_name }}. Terima kasih atas kerja keras Anda melayani warga Sumbawa.
                    </p>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
