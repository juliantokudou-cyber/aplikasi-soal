<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp' => 'required|numeric',
            // Aturan password kustom: tepat 6 karakter, min 1 huruf besar, 1 huruf kecil, 1 angka
            'password' => ['required', 'confirmed', Password::min(6)->max(6)->letters()->mixedCase()->numbers()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'is_approved' => false,
        ]);

        // Kembalikan session flash untuk memicu SweetAlert
        return redirect()->route('login')->with('registration_success', true);
    }

    public function showLogin() {
        return view('auth.login');
    }

    /*public function login(Request $request) 
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Coba login menggunakan USERNAME yang diberikan admin
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            
            // Cek sekali lagi apakah statusnya sudah approved
            if (!auth()->user()->is_approved) {
                return redirect()->route('waiting');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah, atau akun belum di-approve.',
        ])->onlyInput('username');
    }*/

    public function login(Request $request) 
{
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required',
    ]);

    if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();
        
        $user = auth()->user();

        // 1. Cek Apakah Ini Akun Admin Utama
        if ($user->email === 'admin@cbt.com') {
            return redirect()->route('admin.dashboard');
        }

        // 2. Jika Peserta, Cek Status Approval
        if (!$user->is_approved) {
            return redirect()->route('waiting');
        }

        // 3. Jika Peserta Sudah Di-approve, Masuk Halaman Soal
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'username' => 'Username atau password salah, atau akun belum di-approve.',
    ])->onlyInput('username');
}


    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}

