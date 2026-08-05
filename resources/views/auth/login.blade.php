<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-[#1e293b]">
        <div class="w-full max-w-xl px-6">
            {{-- Logo --}}
            <div class="text-center mb-6">
                <img src="{{ asset('images/company-logo.png') }}" alt="Company Logo" class="h-40 w-auto mx-auto object-contain">
            </div>
            
            {{-- Title --}}
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-white mb-4 tracking-wide whitespace-nowrap" style="font-family: 'Plus Jakarta Sans', sans-serif;">SURFACE MINE OPERATIONALS</h1>
                <p class="text-slate-300 text-xl">Welcome To Civil Departement</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                {{-- Username --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <span class="material-symbols-outlined">person</span>
                        </span>
                        <input type="text" 
                               name="login" 
                               value="{{ old('login') }}"
                               placeholder="Enter username"
                               class="w-full pl-10 pr-4 py-3 bg-white border-0 rounded-lg text-gray-900 placeholder-slate-400 focus:ring-0"
                               required
                               autofocus>
                    </div>
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                </div>
                
                {{-- ID Pekerja --}}
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">ID Pekerja</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <span class="material-symbols-outlined">badge</span>
                        </span>
                        <input type="password" 
                               name="password" 
                               placeholder="Enter ID Pekerja"
                               class="w-full pl-10 pr-4 py-3 bg-white border-0 rounded-lg text-gray-900 placeholder-slate-400 focus:ring-0"
                               required
                               autocomplete="current-password">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                {{-- Error message --}}
                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                
                {{-- Button --}}
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-lg transition-colors flex items-center justify-center gap-2 text-lg">
                    Masuk
                    <span>→</span>
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
