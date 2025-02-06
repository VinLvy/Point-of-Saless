<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Petugas;
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
