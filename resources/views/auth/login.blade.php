@extends('layouts.app')

@section('title', 'Masuk ke Portal - Sapa Sumbawa')

@section('content')
<div class="min-h-[calc(100vh-16rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
                <!-- Header -->
        <div class="text-center">
            <!-- ===== LOGO ===== -->
            <div class="inline-flex items-center justify-center mb-4">
                <img src="{{ asset('SAPA1.png') }}" 
                     alt="Logo Sapa Sumbawa" 
                     class="w-48 h-48 object-contain">
            </div>
            <!-- ============== -->

            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Masuk ke Sapa Sumbawa
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Pilih jenis akun atau masukkan kredensial terdaftar Anda
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white py-8 px-6 sm:px-8 shadow-sm border border-slate-200 rounded-3xl">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:border-[#052158] focus:bg-white focus:ring-1 focus:ring-[#052158] transition"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm placeholder-slate-400 focus:outline-hidden focus:border-[#052158] focus:bg-white focus:ring-1 focus:ring-[#052158] transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#052158] focus:ring-[#052158] border-slate-300">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl text-white font-semibold bg-gradient-to-r from-[#052158] to-[#052158] hover:from-[#03163a] hover:to-[#03163a] shadow-md shadow-[#052158]/20 hover:shadow-lg transition-all">
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-200 text-center text-sm text-slate-600">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-bold text-[#052158] hover:text-[#03163a] ml-1">
                    Daftar di sini &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function fillLogin(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
</script>
@endpush
@endsection