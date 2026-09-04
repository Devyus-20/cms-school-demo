<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrintDetailNilaiTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $siswaUser;
    protected Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin']);
        $siswaRole = Role::create(['name' => 'Siswa']);

        $permAcademic = Permission::create(['name' => 'Kelola Akademik']);
        $adminRole->permissions()->attach($permAcademic->id);

        $this->adminUser = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin Penguji',
            'username' => 'admin_print',
            'email' => 'admin.print@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);

        $this->siswa = Siswa::create([
            'nis' => '99001',
            'nama_lengkap' => 'Siswa Penguji Cetak',
            'email' => 'siswa.print@school.test',
            'jenis_kelamin' => 'L',
            'kelas' => 'X MIPA 1',
            'tahun_masuk' => '2026',
            'status' => 'aktif',
        ]);

        $this->siswaUser = User::create([
            'role_id' => $siswaRole->id,
            'name' => $this->siswa->nama_lengkap,
            'username' => '99001',
            'email' => $this->siswa->email,
            'password' => bcrypt('PasswordSiswa123!'),
            'status' => 'active',
        ]);

        $this->siswa->update(['user_id' => $this->siswaUser->id]);
    }

    public function test_admin_can_print_detail_nilai_siswa(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->get("/admin/rekap-akademik/print-siswa/{$this->siswa->id_siswa}");
        $response->assertOk();
        $response->assertSee('TRANSKRIP');
        $response->assertSee('RINCIAN HASIL EVALUASI AKADEMIK SISWA');
        $response->assertSee('Siswa Penguji Cetak');
        $response->assertSee('X MIPA 1');
    }

    public function test_siswa_can_print_own_detail_nilai(): void
    {
        $this->actingAs($this->siswaUser);

        $response = $this->get('/siswa/nilai/cetak');
        $response->assertOk();
        $response->assertSee('TRANSKRIP');
        $response->assertSee('RINCIAN HASIL EVALUASI AKADEMIK SISWA');
        $response->assertSee('Siswa Penguji Cetak');
        $response->assertSee('X MIPA 1');
    }
}
