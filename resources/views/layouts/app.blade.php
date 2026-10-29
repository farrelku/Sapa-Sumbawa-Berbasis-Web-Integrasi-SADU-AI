<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sapa Sumbawa - Portal Aspirasi & Pengaduan Warga') | Kab. Sumbawa</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen bg-slate-50 antialiased selection:bg-[#052158] selection:text-white">

    <!-- Top Announcement Bar -->
    

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Brand -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ asset('SAPA1.png') }}" 
                         alt="Sapa Sumbawa" 
                         class="h-8 sm:h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-200">
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('home') ? 'text-[#052158] bg-slate-100' : 'text-slate-600 hover:text-[#052158] hover:bg-slate-100' }}">
                        Beranda
                    </a>
                    <a href="{{ route('reports.public') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('reports.public*') ? 'text-[#052158] bg-slate-100' : 'text-slate-600 hover:text-[#052158] hover:bg-slate-100' }}">
                        Laporan Warga
                    </a>
                    <a href="{{ route('opds.public') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('opds.public*') ? 'text-[#052158] bg-slate-100' : 'text-slate-600 hover:text-[#052158] hover:bg-slate-100' }}">
                        Daftar OPD
                    </a>
                    <a href="{{ route('track') }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors {{ request()->routeIs('track') ? 'text-[#052158] bg-slate-100' : 'text-slate-600 hover:text-[#052158] hover:bg-slate-100' }}">
                        Lacak Status
                    </a>
                    <a href="{{ route('ai.chat.page') }}" class="px-3 py-1.5 text-sm font-semibold rounded-lg text-white bg-[#052158] hover:bg-[#03163a] flex items-center gap-1.5 shadow-sm transition-colors ml-1">
                        <i data-lucide="sparkles" class="w-4 h-4 text-blue-200"></i>
                        <span>Tanya AI</span>
                    </a>
                </nav>

                <!-- Auth Buttons / User Profile -->
                <div class="flex items-center gap-3">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition">
                                <i data-lucide="shield-check" class="w-4 h-4 text-blue-400"></i>
                                <span>Dashboard Admin</span>
                            </a>
                        @elseif(Auth::user()->isOpd())
                            <a href="{{ route('opd.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg text-white bg-[#052158] hover:bg-[#03163a] shadow-sm transition">
                                <i data-lucide="building" class="w-4 h-4 text-blue-200"></i>
                                <span>Portal OPD: {{ Auth::user()->opd->code ?? 'OPD' }}</span>
                            </a>
                        @else
                            <a href="{{ route('warga.reports.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg text-white bg-[#052158] hover:bg-[#03163a] shadow-sm shadow-[#052158]/30 transition">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                <span>Buat Laporan</span>
                            </a>
                            <a href="{{ route('warga.dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-700 hover:text-[#052158]">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                <span>{{ Str::words(Auth::user()->name, 1, '') }}</span>
                            </a>
                        @endif

                        <!-- Logout Form -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Keluar">
                                <i data-lucide="log-out" class="w-5 h-5"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-[#052158] hover:bg-slate-100 rounded-lg transition">
                            Masuk
                        </a>
                        <!-- Perbaikan: bg[#052158] menjadi bg-[#052158] -->
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-[#052158] hover:bg-[#03163a] rounded-lg shadow-sm shadow-[#052158]/20 transition">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span>Daftar Akun</span>
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 text-slate-600 hover:text-[#052158] hover:bg-slate-100 rounded-lg">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 hover:text-[#052158]">Beranda</a>
            <a href="{{ route('reports.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 hover:text-[#052158]">Laporan Warga</a>
            <a href="{{ route('opds.public') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 hover:text-[#052158]">Daftar OPD</a>
            <a href="{{ route('track') }}" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:bg-slate-100 hover:text-[#052158]">Lacak Status Laporan</a>
            <a href="{{ route('ai.chat.page') }}" class="block px-3 py-2 rounded-md text-base font-medium text-[#052158] bg-slate-100">Tanya Asisten AI</a>
            @guest
                <div class="pt-3 border-t border-slate-200 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="text-center w-full py-2 font-medium text-slate-700 border border-slate-300 rounded-lg">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center w-full py-2 font-medium text-white bg-[#052158] rounded-lg">Daftar Akun Baru</a>
                </div>
            @endguest
        </div>
    </header>

    <!-- Global Alerts / Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 mb-4 text-[#052158] bg-slate-100 border border-slate-200 rounded-xl shadow-xs">
                <i data-lucide="check-circle" class="w-5 h-5 text-[#052158] shrink-0"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 mb-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-xs">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="flex items-center gap-3 p-4 mb-4 text-indigo-900 bg-indigo-50 border border-indigo-200 rounded-xl shadow-xs">
                <i data-lucide="info" class="w-5 h-5 text-indigo-600 shrink-0"></i>
                <div class="text-sm font-medium">{{ session('info') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-xs">
                <div class="flex items-center gap-2 mb-2 font-semibold text-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                    <span>Terdapat beberapa kesalahan:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-1 pb-16">
        @yield('content')
    </main>

    <!-- Floating AI Chatbot Widget -->
    <div id="ai-floating-widget" class="fixed bottom-6 right-6 z-50">
        <!-- Floating Trigger Button -->
        <button id="ai-toggle-btn" class="flex items-center gap-2.5 px-4 py-3 bg-[#07BF8B] text-white font-semibold rounded-full shadow-lg shadow-[#052158]/40 hover:scale-105 active:scale-95 transition-all duration-200 group">
            <div class="relative">
                <i data-lucide="sparkles" class="w-5 h-5 text-blue-200 animate-pulse"></i>
            </div>
            <span class="text-sm">SADU AI</span>
        </button>

        <!-- Floating Chatbox Window -->
        <div id="ai-chatbox" class="hidden fixed bottom-20 right-6 w-96 max-w-[calc(100vw-2rem)] h-[520px] bg-white rounded-2xl shadow-2xl border border-slate-200 flex-col overflow-hidden transition-all duration-300 z-50">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#052158] to-[#03163a] text-white px-4 py-3 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                        <i data-lucide="bot" class="w-5 h-5 text-blue-200"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-tight">SADU AI</h4>
                        <p class="text-[11px] text-blue-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Asisten Pintar (Saling Dukung)
                        </p>
                    </div>
                </div>
                <button id="ai-close-btn" class="text-white/80 hover:text-white hover:bg-white/10 p-1 rounded-lg">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="ai-messages" class="flex-1 p-4 overflow-y-auto space-y-3 bg-slate-50 text-sm">
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-[#052158] text-white flex items-center justify-center shrink-0 text-xs font-bold">
                        AI
                    </div>
                    <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-slate-200 shadow-2xs max-w-[85%] text-slate-800 leading-relaxed text-xs">
                        Salam Samawa! 👋 Ada keluhan seputar jalan rusak, sampah, penerangan jalan umum, atau pelayanan dinas yang ingin Anda tanyakan? Tanyakan di sini!
                    </div>
                </div>
            </div>

            <!-- Quick Suggestions -->
            
            <!-- Input Box -->
            <form id="ai-chat-form" class="p-3 bg-white border-t border-slate-200 flex items-center gap-2">
                <input type="text" id="ai-input" placeholder="Ketik pertanyaan Anda..." class="flex-1 text-xs px-3.5 py-2.5 bg-slate-100 rounded-xl border border-transparent focus:border-[#052158] focus:bg-white focus:outline-hidden transition">
                <button type="submit" id="ai-submit-btn" class="p-2.5 bg-[#052158] hover:bg-[#03163a] text-white rounded-xl transition shadow-xs">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-sm mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1 -->
                <div class="space-y-4 md:col-span-2">
                    <div class="flex items-center gap-3">
                        <!-- Logo Footer (dibuat putih dengan filter invert agar kontras di background gelap) -->
                      <!-- Brand Text Footer -->
<h3 class="text-white font-extrabold text-lg tracking-tight">
    Sapa <span class="text-blue-400">Sumbawa</span>
</h3>
                    </div>
                    <p class="text-xs text-slate-400 max-w-md leading-relaxed">
                        Sistem Informasi Pengaduan & Aspirasi Warga Kabupaten Sumbawa. Menghubungkan partisipasi masyarakat secara langsung dengan instansi dan OPD terkait di lingkup Pemerintah Kabupaten Sumbawa demi terwujudnya pembangunan yang transparan dan akuntabel.
                    </p>
                    <div class="flex items-center gap-4 text-xs text-slate-400">
                        <span class="flex items-center gap-1.5"><i data-lucide="map-pin" class="w-4 h-4 text-blue-400"></i> Jl. Garuda No. 1 Sumbawa Besar</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="phone" class="w-4 h-4 text-blue-400"></i> (0371) 21111</span>
                    </div>
                </div>

                <!-- Col 2 -->
                <div>
                    <h4 class="text-white font-semibold text-xs tracking-wider uppercase mb-3">Tautan Cepat</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="hover:text-blue-400 transition">Beranda</a></li>
                        <li><a href="{{ route('reports.public') }}" class="hover:text-blue-400 transition">Semua Laporan Warga</a></li>
                        <li><a href="{{ route('opds.public') }}" class="hover:text-blue-400 transition">Daftar OPD & Tupoksi</a></li>
                        <li><a href="{{ route('track') }}" class="hover:text-blue-400 transition">Lacak Nomor Tiket</a></li>
                        <li><a href="{{ route('ai.chat.page') }}" class="hover:text-blue-400 transition">Konsultasi Asisten AI</a></li>
                    </ul>
                </div>

                <!-- Col 3 -->
                <div>
                    <h4 class="text-white font-semibold text-xs tracking-wider uppercase mb-3">Akses Akun</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Masuk ke Portal</a></li>
                        <li><a href="{{ route('register.warga') }}" class="hover:text-blue-400 transition">Daftar Akun Warga</a></li>
                        <li><a href="{{ route('register.opd') }}" class="hover:text-blue-400 transition">Daftar Perangkat Daerah (OPD)</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Masuk Admin Pemkab</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-800 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>&copy; {{ date('Y') }} Pemerintah Kabupaten Sumbawa. Hak Cipta Dilindungi.</p>
                <p class="text-[11px]">Sapa Sumbawa • Kolaborasi Warga & Perangkat Daerah</p>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Floating AI Chat Widget Toggle
        const aiToggleBtn = document.getElementById('ai-toggle-btn');
        const aiCloseBtn = document.getElementById('ai-close-btn');
        const aiChatbox = document.getElementById('ai-chatbox');
        const aiChatForm = document.getElementById('ai-chat-form');
        const aiInput = document.getElementById('ai-input');
        const aiMessages = document.getElementById('ai-messages');

        if (aiToggleBtn && aiChatbox) {
            aiToggleBtn.addEventListener('click', () => {
                aiChatbox.classList.toggle('hidden');
                aiChatbox.classList.toggle('flex');
                if (!aiChatbox.classList.contains('hidden')) {
                    aiInput.focus();
                }
            });

            aiCloseBtn.addEventListener('click', () => {
                aiChatbox.classList.add('hidden');
                aiChatbox.classList.remove('flex');
            });

            aiChatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const text = aiInput.value.trim();
                if (!text) return;

                appendUserMessage(text);
                aiInput.value = '';

                // Show loading
                const loadingId = appendLoadingMessage();

                try {
                    const response = await fetch("{{ route('ai.chat.send') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: text })
                    });

                    const data = await response.json();
                    removeLoadingMessage(loadingId);

                    if (data.success) {
                        appendBotMessage(data.reply, data.suggested_category, data.target_opd);
                    } else {
                        appendBotMessage("Mohon maaf, sistem sedang mengalami kendala jaringan. Silakan coba kembali sesaat lagi.");
                    }
                } catch (err) {
                    removeLoadingMessage(loadingId);
                    appendBotMessage("Koneksi gagal. Silakan periksa jaringan internet Anda.");
                }
            });
        }

        function sendQuickAiPrompt(text) {
            if (aiChatbox.classList.contains('hidden')) {
                aiChatbox.classList.remove('hidden');
                aiChatbox.classList.add('flex');
            }
            aiInput.value = text;
            aiChatForm.dispatchEvent(new Event('submit'));
        }

        function appendUserMessage(text) {
            const div = document.createElement('div');
            div.className = 'flex items-start justify-end gap-2';
            div.innerHTML = `
                <div class="bg-[#052158] text-white p-3 rounded-2xl rounded-tr-none shadow-2xs max-w-[85%] text-xs leading-relaxed">
                    ${escapeHtml(text)}
                </div>
            `;
            aiMessages.appendChild(div);
            aiMessages.scrollTop = aiMessages.scrollHeight;
        }

        function appendLoadingMessage() {
            const id = 'loading-' + Date.now();
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex items-start gap-2.5';
            div.innerHTML = `
                <div class="w-7 h-7 rounded-full bg-[#052158] text-white flex items-center justify-center shrink-0 text-xs font-bold">
                    AI
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-slate-200 text-xs text-slate-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-[#052158] rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-[#052158] rounded-full animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1.5 h-1.5 bg-[#052158] rounded-full animate-bounce [animation-delay:0.4s]"></span>
                    <span class="ml-1 text-[11px]">Sedang berpikir...</span>
                </div>
            `;
            aiMessages.appendChild(div);
            aiMessages.scrollTop = aiMessages.scrollHeight;
            return id;
        }

        function removeLoadingMessage(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        function appendBotMessage(text, category, opd) {
            const div = document.createElement('div');
            div.className = 'flex items-start gap-2.5';
            
            let extraBadge = '';
            if (category || opd) {
                extraBadge = `
                    <div class="mt-2 pt-2 border-t border-slate-100 flex flex-col gap-1 text-[11px]">
                        ${opd ? `<span class="text-[#052158] font-semibold">🏛️ Instansi: ${escapeHtml(opd)}</span>` : ''}
                        ${category ? `<span class="text-indigo-700 font-medium">🏷️ Kategori: ${escapeHtml(category)}</span>` : ''}
                        <a href="{{ route('warga.reports.create') }}" class="mt-1 text-center bg-slate-100 text-[#052158] font-semibold py-1 px-2 rounded-lg border border-slate-200 hover:bg-slate-200">
                            ✍️ Buat Laporan Ini Sekarang &rarr;
                        </a>
                    </div>
                `;
            }

            let formattedText = escapeHtml(text)
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');

            div.innerHTML = `
                <div class="w-7 h-7 rounded-full bg-[#052158] text-white flex items-center justify-center shrink-0 text-xs font-bold">
                    AI
                </div>
                <div class="bg-white p-3 rounded-2xl rounded-tl-none border border-slate-200 shadow-2xs max-w-[85%] text-slate-800 leading-relaxed text-xs">
                    <div>${formattedText}</div>
                    ${extraBadge}
                </div>
            `;
            aiMessages.appendChild(div);
            aiMessages.scrollTop = aiMessages.scrollHeight;
        }

        function escapeHtml(string) {
            return String(string).replace(/[&<>"']/g, function(s) {
                return ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                })[s];
            });
        }
    </script>
    @stack('scripts')
</body>
</html>