<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Klien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan login internal
     */
    public function login()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.internal')->with('success', 'Login berhasil!');
        }

        $klien = Klien::where('email', $credentials['email'])->first();

        if ($klien && Hash::check($credentials['password'], $klien->password)) {
            if (! $klien->status_aktif) {
                return back()->withErrors(['email' => 'Akun klien Anda tidak aktif.'])->onlyInput('email');
            }

            Auth::guard('klien')->login($klien, $remember);
            $request->session()->regenerate();
            return redirect()->route('dashboard.klien')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['email' => 'Email atau password salah'])->onlyInput('email');
    }

    /**
     * Tampilkan login portal klien
     */
    public function loginClient()
    {
        return view('auth.login');
    }

    /**
     * Handle login portal klien
     */
    public function doLoginClient(Request $request)
    {
        return $this->doLogin($request);
    }

    /**
     * Logout internal
     */
    public function logout(Request $request)
    {
        if (Auth::guard('klien')->check()) {
            Auth::guard('klien')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Logout portal klien
     */
    public function logoutClient(Request $request)
    {
        Auth::guard('klien')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Tampilkan halaman forgot password
     */
    public function forgotPassword()
    {
        return view('auth.forgot-password', [
            'title' => 'Lupa Password',
            'action' => route('password.email'),
            'backRoute' => route('login'),
            'buttonLabel' => 'Kirim Link Reset',
        ]);
    }

    /**
     * Handle forgot password (simplified mockup)
     */
    public function handleForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // TODO: Implementasi email reset password
        return back()->with('success', 'Instruksi reset password telah dikirim ke email Anda.');
    }
}
