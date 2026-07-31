@extends('layouts.public')

@section('title', 'Surface Mine Production')

@section('content')
<div class="min-h-screen flex items-center relative overflow-hidden bg-[#1e293b]">
    {{-- Left side - Text --}}
    <div class="relative z-10 w-1/2 pl-16 lg:pl-24 py-12">
        <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-6 text-white" style="font-family: 'Plus Jakarta Sans', sans-serif;">
            Surface Mine<br>
            Production Operational<br>
            Record
        </h1>
        <p class="text-lg text-slate-300 mb-8 max-w-md leading-relaxed">
            A centralized dashboard to monitor daily operational activities across Civil Departments, providing real-time insights
        </p>
        <a href="{{ route('login') }}" class="inline-block bg-white text-[#1e293b] font-bold px-10 py-3 rounded-lg hover:bg-slate-100 transition-colors text-lg">
            LOGIN
        </a>
    </div>
    
    {{-- Right side - Image --}}
    <div class="absolute right-0 bottom-0 top-0 w-1/2 flex items-end justify-center">
        <img src="{{ asset('images/worker-hero.jpg') }}" alt="Mining Worker" class="max-h-[90%] w-auto object-contain object-bottom">
    </div>
</div>

<style>
    body { margin: 0; padding: 0; overflow-x: hidden; }
</style>
@endsection
