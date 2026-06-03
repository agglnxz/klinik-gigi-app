<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
            'is_aktif' => 1
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'pesan'  => 'Login gagal! Email/password salah atau akun Anda telah dinonaktifkan.'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'pesan'        => 'Login berhasil!',
            'data'         => $user,
            'access_token' => $token,
            'token_type'   => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $request->user()->tokens()->where('id', $token->id)->delete();
        }

        return response()->json([
            'status' => 'success',
            'pesan'  => 'Logout berhasil, token telah dihapus!'
        ]);
    }
}
