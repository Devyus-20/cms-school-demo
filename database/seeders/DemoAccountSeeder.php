<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $operatorRole = Role::where('name', 'Operator')->first();
        $editorRole = Role::where('name', 'Editor')->first();
        $siswaRole = Role::where('name', 'Siswa')->first();

        // 1. Akun Super Admin Demo
        User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Demo Administrator',
                'username' => 'admin_demo',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole?->id_role,
                'status' => 'active',
            ]
        );

        // 2. Akun Guru / Operator Akademik Demo
        User::updateOrCreate(
            ['email' => 'guru@demo.com'],
            [
                'name' => 'Demo Guru Pengajar',
                'username' => 'guru_demo',
                'password' => Hash::make('password123'),
                'role_id' => $operatorRole?->id_role ?? $adminRole?->id_role,
                'status' => 'active',
            ]
        );

        // 3. Akun Siswa Demo
        User::updateOrCreate(
            ['email' => 'siswa@demo.com'],
            [
                'name' => 'Ahmad Fauzi (Siswa Demo)',
                'username' => 'siswa_demo',
                'password' => Hash::make('password123'),
                'role_id' => $siswaRole?->id_role,
                'status' => 'active',
            ]
        );

        // Pastikan Profil Siswa Terhubung
        Siswa::updateOrCreate(
            ['nis' => '2026001'],
            [
                'nisn' => '0051234561',
                'nama_lengkap' => 'Ahmad Fauzi',
                'email' => 'siswa@demo.com',
                'jenis_kelamin' => 'L',
                'kelas' => 'X MIPA 1',
                'tahun_masuk' => '2026',
                'status' => 'aktif',
                'telepon' => '081299990001',
                'alamat' => 'Jl. Pendidikan Mandiri No. 12, Kota Pelajar',
            ]
        );
    }
}
