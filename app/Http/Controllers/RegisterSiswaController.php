<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterSiswaController extends Controller
{
    /** Form Registrasi Siswa Terdaftar */
    public function showRegisterForm()
    {
        return view('auth.register-siswa');
    }

    /** Proses Registrasi Akun Siswa (Email Whitelisted oleh Admin) */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $email = strtolower(trim($validated['email']));

        // 1. Cek apakah Email terdaftar di basis data siswa yang dimasukkan oleh Admin
        $siswa = Siswa::whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$siswa) {
            $msg = 'Email Anda (' . $validated['email'] . ') belum didaftarkan di dalam sistem sekolah. Hanya email yang sudah didaftarkan oleh Admin yang berhasil membuat registrasi.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['email' => $msg])->withInput();
        }

        // 2. Cek apakah akun email ini sudah pernah dibuat sebelumnya
        if ($siswa->user_id || User::where('email', $siswa->email)->exists()) {
            $msg = 'Akun dengan email tersebut sudah terdaftar sebelumnya. Silakan masuk melalui halaman login.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['email' => $msg])->withInput();
        }

        // 3. Dapatkan/Buat Role Siswa
        $siswaRole = Role::where('name', 'Siswa')->first();
        if (!$siswaRole) {
            $siswaRole = Role::create([
                'name' => 'Siswa',
                'description' => 'Akses portal siswa, presensi, tugas, dan ujian online',
            ]);
        }

        // 4. Buat User Akun
        $user = User::create([
            'role_id' => $siswaRole->id_role,
            'name' => $siswa->nama_lengkap,
            'username' => $validated['username'],
            'email' => $siswa->email,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        // 5. Hubungkan tabel Siswa ke User dan update statusnya jadi aktif
        $siswa->update([
            'user_id' => $user->id_user,
            'status' => 'aktif',
        ]);

        ActivityLog::record('register', "Siswa {$siswa->nama_lengkap} (Email: {$siswa->email}) berhasil menyelesaikan registrasi akun.", $user, $user->id_user);

        // 6. Login otomatis & redirect ke PORTAL SISWA (bukan admin)
        Auth::login($user);
        $request->session()->regenerate();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi akun siswa berhasil! Selamat datang di Portal Siswa.',
                'redirect' => route('siswa.dashboard'),
            ]);
        }

        return redirect()->route('siswa.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Portal Siswa.');
    }
}
