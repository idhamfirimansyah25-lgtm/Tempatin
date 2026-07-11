@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-12 mb-20">
    
    <div class="text-center mb-8">
        <h1 class="font-manrope text-3xl font-bold text-primary tracking-tight mb-2">Jadwal Tenang.</h1>
        <p class="text-sm text-gray-500">Portal Manajemen Admin</p>
    </div>

    <div class="bg-white p-8 sm:p-10 rounded-xl shadow-sm border border-gray-200">
        <h2 class="font-manrope text-2xl font-bold text-slate mb-6 text-center">Masuk ke Akun</h2>
        
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-slate mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="admin@domain.com" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate mb-2">Kata Sandi</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm text-slate focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
            </div>
            
            <div class="flex items-center pt-1">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-primary bg-gray-50 border-gray-300 rounded focus:ring-primary/50 cursor-pointer">
                <label for="remember" class="ml-2 text-sm text-gray-600 select-none cursor-pointer">
                    Ingat sesi perangkat ini
                </label>
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full bg-primary text-white font-medium py-3.5 rounded-lg hover:bg-opacity-90 transition-all text-sm shadow-sm flex justify-center items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Masuk ke Dashboard
                </button>
            </div>
        </form>
    </div>
    
    <div class="text-center mt-6">
        <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-primary transition-colors">
            &larr; Kembali ke Halaman Utama
        </a>
    </div>
</div>
@endsection