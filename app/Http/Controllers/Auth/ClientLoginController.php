<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('klien')->check()) {
            return redirect()->route('dashboard.klien');
        }

        return view('auth.client-login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password Klien',
            'action' => route('client.password.email'),
            'backRoute' => route('client.login'),
            'buttonLabel' => 'Minta Reset Password',
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        return back()->with('status', 'Jika email klien terdaftar, instruksi reset password akan dikirim.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('klien')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.klien'));
        }

        return back()->withErrors([
            'email' => 'Email atau password klien salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('klien')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }
}
