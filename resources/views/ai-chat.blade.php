@extends('layouts.app')

@section('title', 'Tanya Asisten AI Sumbawa - Sapa Sumbawa')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Chat Area (Nuansa Biru) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm flex flex-col h-[650px] overflow-hidden">
            <!-- Header -->
            <div class="p-4 sm:p-5 border-b border-slate-200 bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-white">
                        <i data-lucide="bot" class="w-6 h-6 text-blue-300"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-base flex items-center gap-2">
                            <span>SADU AI Asisten Virtual</span>
             
                        </h2>
                        <p class="text-xs text-blue-200">Menjawab pertanyaan seputar pengaduan & OPD di Kab. Sumbawa</p>
                    </div>
                </div>
                
            </div>

            <!-- Messages Stream -->
            <div id="full-chat-messages" class="flex-1 p-5 overflow-y-auto space-y-4 bg-slate-50 text-sm">
                <!-- Greeting message -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        AI
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none border border-slate-200 shadow-2xs max-w-[85%] text-slate-800 space-y-2 text-xs leading-relaxed">
                        <p class="font-bold text-blue-800 text-sm">Salam Samawa!</p>
                        <p>Saya adalah SADU AI Sapa Sumbawa. Saya dapat membantu Anda mengetahui:</p>
                        <ul class="list-disc list-inside space-y-1 text-slate-600">
                            <li>Dinas (OPD) mana yang berwenang menyelesaikan keluhan Anda (misal PUPR, DLH, Dishub).</li>
                            <li>Kategori laporan yang tepat untuk masalah jalan rusak, sampah, atau pelayanan dinas.</li>
                            <li>Alur pengaduan mulai dari verifikasi admin hingga penyelesaian di lapangan.</li>
                        </ul>
                        <p class="pt-1 text-slate-500">Silakan tanyakan keluhan Anda atau klik saran pertanyaan di bawah!</p>
                    </div>
                </div>
            </div>

            <!-- Prompt Suggestions -->
            <div class="p-3 bg-slate-100/80 border-t border-slate-200 flex gap-2 overflow-x-auto no-scrollbar text-xs">
                <button onclick="fillFullChatPrompt('Ada jalan rusak berlubang parah di Unter Iwes, lapor ke dinas apa?')" class="shrink-0 bg-white hover:bg-blue-50 hover:text-blue-700 text-slate-700 px-3 py-1.5 rounded-full border border-slate-200 transition font-medium">
                    🛣️ Jalan Berlubang di Unter Iwes
                </button>
                <button onclick="fillFullChatPrompt('Lampu jalan PJU di Brang Biji mati total, bagaimana cara lapor?')" class="shrink-0 bg-white hover:bg-blue-50 hover:text-blue-700 text-slate-700 px-3 py-1.5 rounded-full border border-slate-200 transition font-medium">
                    💡 Lampu PJU Mati di Brang Biji
                </button>
                <button onclick="fillFullChatPrompt('Sampah menumpuk di pasar Sumbawa, dinas apa yang bertanggung jawab?')" class="shrink-0 bg-white hover:bg-blue-50 hover:text-blue-700 text-slate-700 px-3 py-1.5 rounded-full border border-slate-200 transition font-medium">
                    🗑️ Sampah di Pasar Seketeng
                </button>
                <button onclick="fillFullChatPrompt('Bagaimana cara kerja admin mendisposisikan laporan ke OPD?')" class="shrink-0 bg-white hover:bg-blue-50 hover:text-blue-700 text-slate-700 px-3 py-1.5 rounded-full border border-slate-200 transition font-medium">
                    📋 Alur Disposisi Admin
                </button>
            </div>

            <!-- Input Bar -->
            <form id="full-chat-form" class="p-4 bg-white border-t border-slate-200 flex items-center gap-3">
                <input type="text" id="full-chat-input" placeholder="Tanyakan seputar pengaduan atau masalah fasilitas di Sumbawa..." required
                    class="flex-1 px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-hidden focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-600 transition">
                <button type="submit" id="full-chat-submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-sm transition flex items-center gap-2">
                    <span>Kirim</span>
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Card 1: Action -->
            <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-700 rounded-3xl p-6 text-white shadow-md">
                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center mb-4">
                    <i data-lucide="file-plus-2" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-xl font-bold">Siap Mengirim Laporan?</h3>
                <p class="text-xs text-blue-100 mt-2 leading-relaxed">
                    Jika Anda sudah mengetahui masalah dan lokasi fasilitas yang rusak, segera ajukan laporan resmi agar Admin Pemkab dapat meneruskannya ke dinas terkait.
                </p>
                <a href="{{ route('warga.reports.create') }}" class="mt-5 block w-full py-3 text-center bg-white text-blue-800 font-bold rounded-xl text-sm hover:bg-blue-50 shadow-sm transition">
                    Buat Laporan Baru &rarr;
                </a>
            </div>

            <!-- Card 2: OPD Quick Reference -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <h4 class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <i data-lucide="building" class="w-4 h-4 text-blue-600"></i>
                    <span>Tupoksi Cepat OPD Sumbawa:</span>
                </h4>
                <div class="space-y-3 text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-bold text-slate-800">Dinas PUPR:</span>
                        <p class="text-slate-500 text-[11px] mt-0.5">Jalan berlubang, jembatan rusak, drainase & saluran irigasi.</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-bold text-slate-800">Dinas Lingkungan Hidup (DLH):</span>
                        <p class="text-slate-500 text-[11px] mt-0.5">Penumpukan sampah liar, TPS overload, kebersihan taman kota.</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-bold text-slate-800">Dinas Perhubungan (DISHUB):</span>
                        <p class="text-slate-500 text-[11px] mt-0.5">Lampu jalan padam (PJU), marka jalan, kemacetan, parkir liar.</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="font-bold text-slate-800">Dinas Dukcapil:</span>
                        <p class="text-slate-500 text-[11px] mt-0.5">Pengurusan KTP-el, KK, akta, dan aktivasi Identitas Kependudukan Digital.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const fullForm = document.getElementById('full-chat-form');
    const fullInput = document.getElementById('full-chat-input');
    const fullMessages = document.getElementById('full-chat-messages');

    function fillFullChatPrompt(text) {
        fullInput.value = text;
        fullForm.dispatchEvent(new Event('submit'));
    }

    fullForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = fullInput.value.trim();
        if (!text) return;

        // User message
        appendFullUserMsg(text);
        fullInput.value = '';

        // Loading
        const loadId = appendFullLoading();

        try {
            const res = await fetch("{{ route('ai.chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: text })
            });

            const data = await res.json();
            removeFullLoading(loadId);

            if (data.success) {
                appendFullBotMsg(data.reply, data.suggested_category, data.target_opd);
            } else {
                appendFullBotMsg("Mohon maaf, sistem sedang mengalami kendala. Silakan coba kembali.");
            }
        } catch (err) {
            removeFullLoading(loadId);
            appendFullBotMsg("Gagal menghubungi server. Silakan periksa koneksi internet Anda.");
        }
    });

    function appendFullUserMsg(text) {
        const div = document.createElement('div');
        div.className = 'flex items-start justify-end gap-3';
        div.innerHTML = `
            <div class="bg-blue-600 text-white p-4 rounded-2xl rounded-tr-none shadow-xs max-w-[85%] text-xs leading-relaxed">
                ${escapeFullHtml(text)}
            </div>
        `;
        fullMessages.appendChild(div);
        fullMessages.scrollTop = fullMessages.scrollHeight;
    }

    function appendFullLoading() {
        const id = 'full-load-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex items-start gap-3';
        div.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                AI
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none border border-slate-200 text-xs text-slate-500 flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></span>
                <span class="w-2 h-2 bg-blue-600 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                <span class="w-2 h-2 bg-blue-600 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                <span class="ml-1">Sedang menganalisis jawaban bersama Gemini AI...</span>
            </div>
        `;
        fullMessages.appendChild(div);
        fullMessages.scrollTop = fullMessages.scrollHeight;
        return id;
    }

    function removeFullLoading(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function appendFullBotMsg(text, category, opd) {
        const div = document.createElement('div');
        div.className = 'flex items-start gap-3';

        let extraBadge = '';
        if (category || opd) {
            extraBadge = `
                <div class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                    <div class="space-y-1">
                        ${opd ? `<div class="font-bold text-blue-800">🏛️ Instansi Berwenang: ${escapeFullHtml(opd)}</div>` : ''}
                        ${category ? `<div class="font-medium text-slate-600">🏷️ Rekomendasi Kategori: ${escapeFullHtml(category)}</div>` : ''}
                    </div>
                    <a href="{{ route('warga.reports.create') }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        Lapor Masalah Ini &rarr;
                    </a>
                </div>
            `;
        }

        let formattedText = escapeFullHtml(text)
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');

        div.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                AI
            </div>
            <div class="bg-white p-4 rounded-2xl rounded-tl-none border border-slate-200 shadow-2xs max-w-[85%] text-slate-800 text-xs leading-relaxed space-y-2">
                <div>${formattedText}</div>
                ${extraBadge}
            </div>
        `;
        fullMessages.appendChild(div);
        fullMessages.scrollTop = fullMessages.scrollHeight;
    }

    function escapeFullHtml(str) {
        return String(str).replace(/[&<>"']/g, function(s) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[s];
        });
    }
</script>
@endpush
@endsection
