<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthWebController extends Controller
{
    /**
     * Menampilkan halaman form login HTML
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses pengecekan email, password, dan status is_aktif
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Siapkan Kredensial (Wajib Aktif)
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
            'is_aktif' => 1 // Memblokir akun yang sedang diskors/nonaktif
        ];

        // 3. Eksekusi Login berbasis Session
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Arahkan ke halaman utama
            return redirect()->intended('dashboard')->with('success', 'Selamat datang di Sistem Klinik Gigi!');
        }

        // 4. Jika Gagal (Salah password ATAU akun nonaktif)
        return back()->withErrors([
            'email' => 'Login gagal! Email/password salah atau akun Anda telah dinonaktifkan.',
        ])->onlyInput('email');
    }

    /**
     * Memproses Logout dan menghancurkan Session
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Keamanan ekstra: Hancurkan dan buat ulang token session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil keluar dari sistem.');
    }
}
