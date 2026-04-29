<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
// FUNGSI LOGIN (API)
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
            'is_aktif' => 1
        ];
        // 2. Cek apakah kredensial cocok dan akun aktif
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Login gagal! Email/password salah atau akun Anda telah dinonaktifkan.'
            ], 401);
        }
        // 3. Jika cocok, ambil data user tersebut
        $user = User::where('email', $request->email)->firstOrFail();

        // 4. Buatkan Token (Tiket Masuk)
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'status'       => 'success',
            'pesan'        => 'Login berhasil!',
            'data'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer'
        ]);
    }

    // FUNGSI LOGOUT
    public function logout(Request $request)
    {
        // Hapus (bakar) token yang sedang digunakan oleh user ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Logout berhasil, token telah dihapus!'
        ]);
    }
}
