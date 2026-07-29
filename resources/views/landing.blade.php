@extends('layouts.public')

@section('title', 'Surface Mine Production')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    {{-- Background gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#0a0f1a] via-[#0f172a] to-[#1a2332]"></div>
    
    {{-- Content --}}
    <div class="relative z-10 max-w-6xl mx-auto px-6 py-12 flex items-center gap-12">
        {{-- Left side - Text --}}
        <div class="flex-1 text-white">
            <h1 class="text-4xl lg:text-5xl font-heading font-bold leading-tight mb-6">
                Surface Mine<br>
                Production Operational<br>
                Record
            </h1>
            <p class="text-lg text-slate-300 mb-8 max-w-lg">
                A centralized dashboard to monitor daily operational activities across Civil Departments, providing real-time insights
            </p>
            <a href="{{ route('login') }}" class="inline-block bg-white text-[#0f172a] font-bold px-8 py-3 rounded-lg hover:bg-slate-100 transition-colors">
                LOGIN
            </a>
        </div>
        
        {{-- Right side - Image --}}
        <div class="flex-1 hidden lg:flex justify-center">
            <img src="{{ asset('images/worker-hero.png') }}" alt="Mining Worker" class="max-w-md w-full h-auto">
        </div>
    </div>
</div>

<style>
    body { margin: 0; padding: 0; overflow-x: hidden; }
</style>
@endsection
