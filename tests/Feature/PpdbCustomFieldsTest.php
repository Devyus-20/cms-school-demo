<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\PpdbCustomField;
use App\Models\PpdbRegistration;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbCustomFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'Admin']);
        $permSettings = Permission::create(['name' => 'Kelola Settings']);
        $adminRole->permissions()->attach($permSettings->id);

        $this->adminUser = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin PPDB Test',
            'username' => 'admin_ppdb',
            'email' => 'admin.ppdb@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);

        Setting::create([
            'website_name' => 'MA AL IKHLAS',
            'ppdb_aktif' => true,
            'ppdb_tahun' => '2026/2027',
        ]);
    }

    public function test_admin_can_access_ppdb_custom_fields_page(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/admin/ppdb/fields');
        $response->assertOk();
        $response->assertSee('Dynamic Form Fields PPDB');
    }

    public function test_admin_can_create_custom_field(): void
    {
        $this->actingAs($this->adminUser);

        $response = $this->post('/admin/ppdb/fields', [
            'label' => 'Ukuran Seragam',
            'tipe' => 'select',
            'options_raw' => "S\nM\nL\nXL",
            'is_required' => 1,
            'urutan' => 1,
            'aktif' => 1,
        ]);

        $response->assertRedirect('/admin/ppdb/fields');
        $this->assertDatabaseHas('ppdb_custom_fields', [
            'label' => 'Ukuran Seragam',
            'field_key' => 'ukuran_seragam',
            'tipe' => 'select',
            'is_required' => true,
        ]);
    }

    public function test_public_registration_saves_custom_field_data(): void
    {
        PpdbCustomField::create([
            'label' => 'Ukuran Seragam',
            'field_key' => 'ukuran_seragam',
            'tipe' => 'select',
            'options' => ['S', 'M', 'L', 'XL'],
            'is_required' => true,
            'urutan' => 1,
            'aktif' => true,
        ]);

        $response = $this->post('/ppdb', [
            'nama_lengkap' => 'Calon Siswa Custom',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2010-05-15',
            'agama' => 'Islam',
            'sekolah_asal' => 'SMP Negeri 1',
            'nama_orang_tua' => 'Bapak Custom',
            'no_hp' => '081299998888',
            'alamat' => 'Jl. Merdeka No. 10',
            'data_tambahan' => [
                'ukuran_seragam' => 'XL',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('ppdb_success');

        $reg = PpdbRegistration::where('nama_lengkap', 'Calon Siswa Custom')->first();
        $this->assertNotNull($reg);
        $this->assertIsArray($reg->data_tambahan);
        $this->assertEquals('XL', $reg->data_tambahan['ukuran_seragam']);
    }
}
