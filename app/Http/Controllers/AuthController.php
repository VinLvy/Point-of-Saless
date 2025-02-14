<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Petugas;
use App\Models\Pelanggan;
use App\Services\ActivityLogService;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah email ada di tabel pelanggan
        $pelanggan = Pelanggan::where('email', $credentials['email'])->first();
        if ($pelanggan) {
            return back()->withErrors(['email' => 'Akses ditolak. Anda tidak memiliki izin untuk masuk.']);
        }

        // Jika email tidak ada di pelanggan, lanjutkan dengan autentikasi petugas
        if (Auth::attempt($credentials)) {
            ActivityLogService::log('login');
            $user = Auth::user();

            // Redirect berdasarkan role
            switch ($user->role) {
                case 'administrator':
                    return redirect()->route('admin.dashboard');
                case 'kasir':
                    return redirect()->route('kasir.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors('Role tidak valid.');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout()
    {
        ActivityLogService::log('logout');
        Auth::logout();
        return redirect()->route('login');
    }
}
