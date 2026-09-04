<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** Form Login Siswa */
    public function showLoginSiswaForm()
    {
        return view('auth.login-siswa');
    }

    /** Proses Login Siswa */
    public function loginSiswa(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = trim($credentials['login']);
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 1. Cek keberadaan user & role
        $user = User::with('role')->where($loginField, $loginInput)->first();

        if ($user && $user->role?->name !== 'Siswa') {
            $msg = 'Akun ini terdaftar sebagai Admin/Pengelola Sekolah. Silakan gunakan Halaman Login khusus Admin.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['login' => $msg])->withInput();
        }

        // 2. Eksekusi Attempt
        if (Auth::attempt([$loginField => $loginInput, 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update(['last_login' => now()]);

            ActivityLog::record('login', "Siswa {$user->name} ({$user->username}) berhasil masuk ke Portal Siswa.", $user, $user->id_user);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login siswa berhasil.',
                    'redirect' => route('siswa.dashboard'),
                ]);
            }

            return redirect()->route('siswa.dashboard');
        }

        $msg = 'Gagal masuk. Periksa kembali Email/Username dan Password Anda.';
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        return back()->withErrors(['login' => $msg])->withInput();
    }

    /** Form Login Admin / Staff */
    public function showLoginAdminForm()
    {
        return view('auth.login-admin');
    }

    /** Proses Login Admin / Staff */
    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Email atau Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = trim($credentials['login']);
        $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 1. Cek keberadaan user & role
        $user = User::with('role')->where($loginField, $loginInput)->first();

        if ($user && $user->role?->name === 'Siswa') {
            $msg = 'Akun ini adalah Akun Siswa. Silakan gunakan Halaman Login khusus Siswa.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['login' => $msg])->withInput();
        }

        // 2. Eksekusi Attempt
        if (Auth::attempt([$loginField => $loginInput, 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update(['last_login' => now()]);

            ActivityLog::record('login', "Admin {$user->name} ({$user->username}) berhasil masuk ke Dashboard Admin.", $user, $user->id_user);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login admin berhasil.',
                    'redirect' => route('dashboard'),
                ]);
            }

            return redirect()->route('dashboard');
        }

        $msg = 'Gagal masuk. Periksa kembali Email/Username dan Password Admin Anda.';
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        return back()->withErrors(['login' => $msg])->withInput();
    }

    /** Logout */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::record('logout', "User {$user->name} ({$user->username}) telah keluar dari sistem.", $user, $user->id_user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login/siswa');
    }
}