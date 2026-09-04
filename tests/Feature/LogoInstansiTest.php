<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoInstansiTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin']);
        $permSettings = Permission::create(['name' => 'Kelola Settings']);
        $permAcademic = Permission::create(['name' => 'Kelola Akademik']);
        $adminRole->permissions()->attach([$permSettings->id, $permAcademic->id]);

        $this->adminUser = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin Logo Test',
            'username' => 'admin_logo',
            'email' => 'admin.logo@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
    }

    public function test_admin_can_save_logo_instansi(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->post('/admin/settings', [
            'website_name' => 'MA AL IKHLAS',
            'logo_instansi_url' => 'https://example.com/logo-kemenag.png',
        ]);

        $response->assertRedirect('/admin/settings');
        $this->assertDatabaseHas('settings', [
            'website_name' => 'MA AL IKHLAS',
            'logo_instansi' => 'https://example.com/logo-kemenag.png',
        ]);
    }

    public function test_print_views_contain_logo_instansi(): void
    {
        Setting::create([
            'website_name' => 'MA AL IKHLAS',
            'logo_instansi' => 'https://example.com/logo-kemenag.png',
        ]);

        $siswaRole = Role::create(['name' => 'Siswa']);
        $siswaUser = User::create([
            'role_id' => $siswaRole->id,
            'name' => 'Siswa Logo Test',
            'username' => '99002',
            'email' => 'siswa.logo@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
        $siswa = \App\Models\Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '99002',
            'nama_lengkap' => 'Siswa Logo Test',
            'email' => 'siswa.logo@school.test',
            'jenis_kelamin' => 'L',
            'kelas' => 'X MIPA 1',
            'status' => 'aktif',
        ]);

        $this->actingAs($this->adminUser);

        $response = $this->get("/admin/rekap-akademik/print-siswa/{$siswa->id_siswa}");
        $response->assertOk();
        $response->assertSee('https://example.com/logo-kemenag.png');
        $response->assertSee('Logo Instansi/Kementerian/Yayasan');
    }
}
