<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menangani proses login user
     */
    public function login(Request $request)
    {
        // 1. Validasi input email dan password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba autentikasi user
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Ambil role user untuk menentukan arah redirect
            $role = Auth::user()->role;

            // Jika dia Admin atau Petugas, kirim ke dashboard backend (Dashboard Admin)
            if ($role === 'admin' || $role === 'petugas') {
                return redirect()->route('admin.dashboard');
            }

            // JIKA DIA ANGGOTA, LANGSUNG KIRIM KE KATALOG (SESUAI REQUEST)
            // Pastikan route 'katalog' sudah ada di web.php
            return redirect()->route('katalog'); 
        }

        // 4. Balikkan ke halaman login jika gagal dengan pesan error
        return back()->with('loginError', 'Email atau password salah, coba cek lagi bro!');
    }

    /**
     * Menangani proses keluar dari sistem (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Bersihkan session agar aman
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setelah logout, balikkan ke halaman Landing Page awal
        return redirect()->route('landing');
    }
}