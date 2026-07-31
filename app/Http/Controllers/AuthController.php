<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $attempted = false;
        $user = null;

        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Models\User::where('email', $request->login)->first();
            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                $attempted = true;
            }
        }

        if (!$attempted) {
            $user = \App\Models\User::where('username', $request->login)->first();
            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                $attempted = true;
            }
        }

        if (!$attempted) {
            $pegawai = \App\Models\Pegawai::where('nama', $request->login)->first();
            if ($pegawai) {
                $user = \App\Models\User::where('pegawai_id', $pegawai->id)->first();
                if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                    $attempted = true;
                }
            }
        }

        if ($attempted && $user) {
            Auth::login($user);
            $request->session()->regenerate();

            return match ($user->role) {
                'admin'   => redirect()->intended('/admin/dashboard'),
                'spv'     => redirect()->intended('/spv/dashboard'),
                'pegawai' => redirect()->intended('/pegawai'),
                default   => redirect('/'),
            };
        }

        return back()->withErrors([
            'login' => 'Kredensial yang dimasukkan salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
