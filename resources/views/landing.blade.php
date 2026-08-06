@extends('layouts.public')

@section('title', 'Surface Mine Production')

@section('content')
<nav class="bg-white/95 backdrop-blur border-b border-[var(--border)] sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <span class="font-heading font-bold text-lg text-[var(--primary)] tracking-wide">SURFACE MINE PRODUCTION</span>
        <a href="{{ route('login') }}" class="btn-primary px-5 py-2 text-sm">LOGIN</a>
    </div>
</nav>

<div class="min-h-[calc(100vh-73px)] flex items-center relative overflow-hidden bg-gradient-to-br from-[var(--bg)] to-[var(--bg-secondary)]">
    {{-- Left side - Text --}}
    <div class="relative z-10 w-full lg:w-1/2 px-6 md:px-16 lg:pl-24 py-12">
        <h1 class="font-heading text-4xl lg:text-5xl font-bold leading-tight mb-6 text-[var(--primary)]">
            Surface Mine<br>
            Production Operational<br>
            Record
        </h1>
        <p class="text-lg text-[var(--text-secondary)] mb-8 max-w-md leading-relaxed">
            A centralized dashboard to monitor daily operational activities across Civil Departments, providing real-time insights
        </p>
        <a href="{{ route('login') }}" class="btn-primary inline-block px-10 py-3 text-lg">
            LOGIN
        </a>
    </div>

    {{-- Right side - Image --}}
    <div class="hidden lg:flex absolute right-0 bottom-0 top-0 w-1/2 items-end justify-center">
        <img src="{{ asset('images/worker-hero.jpg') }}" alt="Mining Worker" class="max-h-[90%] w-auto object-contain object-bottom">
    </div>
</div>
@endsection
