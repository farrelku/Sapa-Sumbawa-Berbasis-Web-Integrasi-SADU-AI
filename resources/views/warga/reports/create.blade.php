@extends('layouts.app')

@section('title', 'Buat Laporan Baru - Sapa Sumbawa')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('warga.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-blue-700 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Dashboard</span>
        </a>
        <a href="{{ route('ai.chat.page') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-full border border-blue-200 transition">
            <i data-lucide="sparkles" class="w-4 h-4 text-blue-600"></i>
            <span>Bingung pilih kategori? Tanya AI</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-10">
        <div class="mb-8">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3">
                <i data-lucide="edit-3" class="w-6 h-6"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Formulir Pengaduan Warga</h1>
            <p class="text-sm text-slate-500 mt-1">
                Laporan Anda akan diverifikasi oleh Admin Pemkab Sumbawa lalu didisposisikan ke OPD terkait (PUPR, Dishub, DLH, dll).
            </p>
        </div>

        <form action="{{ route('warga.reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Judul Laporan -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-800 mb-1">
                    Judul Singkat Laporan <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                    placeholder="Contoh: Jalan Aspal Berlubang Parah di Dekat Jembatan Brang Biji">
            </div>

            <!-- Kategori & Urgensi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-semibold text-slate-800 mb-1">
                        Kategori Pengaduan <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" name="category" required
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $name => $meta)
                            <option value="{{ $name }}" {{ old('category') == $name ? 'selected' : '' }}>
                                {{ $name }} (Dinas: {{ $meta['default_opd'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="urgency" class="block text-sm font-semibold text-slate-800 mb-1">
                        Tingkat Urgensi <span class="text-rose-500">*</span>
                    </label>
                    <select id="urgency" name="urgency" required
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition">
                        <option value="sedang" {{ old('urgency') == 'sedang' ? 'selected' : '' }}>Sedang (Kondisi umum terganggu)</option>
                        <option value="mendesak" {{ old('urgency') == 'mendesak' ? 'selected' : '' }}>Mendesak (Rawan kecelakaan / bahaya jiwa)</option>
                        <option value="rendah" {{ old('urgency') == 'rendah' ? 'selected' : '' }}>Rendah (Saran perbaikan berkala)</option>
                    </select>
                </div>
            </div>

            <!-- Kecamatan & Detail Lokasi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="subdistrict" class="block text-sm font-semibold text-slate-800 mb-1">
                        Kecamatan Lokasi Kejadian <span class="text-rose-500">*</span>
                    </label>
                    <select id="subdistrict" name="subdistrict" required
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition">
                        <option value="">-- Pilih Kecamatan di Sumbawa --</option>
                        @foreach($districts as $key => $name)
                            <option value="{{ $key }}" {{ (old('subdistrict') == $key || Auth::user()->subdistrict == $key) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="location_detail" class="block text-sm font-semibold text-slate-800 mb-1">
                        Detail Patokan Lokasi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="location_detail" name="location_detail" value="{{ old('location_detail') }}" required
                        class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                        placeholder="Nama jalan, nomor RT/RW, dekat masjid/toko">
                </div>
            </div>

            <!-- Deskripsi Detail -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-800 mb-1">
                    Deskripsi Lengkap Masalah <span class="text-rose-500">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required
                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition"
                    placeholder="Ceritakan kondisi jalan yang berlubang (ukuran, kedalaman), tumpukan sampah, pelayanan yang buruk, atau kendala fasilitas lainnya secara lengkap (minimal 20 karakter)...">{{ old('description') }}</textarea>
            </div>

            <!-- Upload Foto Bukti -->
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1">
                    Unggah Foto Bukti Kejadian (Opsional / Sangat Dianjurkan)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl hover:border-blue-500 transition bg-slate-50/50">
                    <div class="space-y-2 text-center">
                        <i data-lucide="image-plus" class="mx-auto h-10 w-10 text-slate-400"></i>
                        <div class="flex text-xs text-slate-600 justify-center">
                            <label for="photo" class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-500 px-2 py-0.5 border border-slate-200">
                                <span>Pilih file foto</span>
                                <input id="photo" name="photo" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                            </label>
                            <span class="pl-2">atau drag & drop foto</span>
                        </div>
                        <p class="text-[11px] text-slate-400">JPG, PNG, atau WEBP hingga maksimal 5MB</p>
                    </div>
                </div>
                <!-- Image Preview Container -->
                <div id="image-preview-container" class="mt-3 hidden">
                    <p class="text-xs font-semibold text-slate-600 mb-1">Pratinjau Foto:</p>
                    <img id="image-preview" src="#" alt="Preview" class="max-h-48 rounded-xl border border-slate-200 object-cover">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 px-6 rounded-xl text-white font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-600/30 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    <span>Kirim Laporan ke Pemkab Sumbawa</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
