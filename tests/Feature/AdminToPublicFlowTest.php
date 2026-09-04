<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminToPublicFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Admin User with Role 'Admin' and necessary permissions
        $role = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator Utama',
        ]);

        $permSettings = Permission::create(['name' => 'Kelola Settings']);
        $permWebsite  = Permission::create(['name' => 'Kelola Website']);
        $permNews     = Permission::create(['name' => 'Tambah Berita']);
        $permUser     = Permission::create(['name' => 'Kelola User']);

        $role->permissions()->attach([
            $permSettings->id,
            $permWebsite->id,
            $permNews->id,
            $permUser->id,
        ]);

        $this->adminUser = User::create([
            'role_id' => $role->id,
            'name' => 'Admin Penguji',
            'username' => 'admin_penguji',
            'email' => 'admin.penguji@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
    }

    /**
     * UJI 1: Penginputan Setting Website dari Admin ke Halaman Depan Publik
     */
    public function test_admin_settings_update_reflects_on_public_home_page(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Admin menyimpan pengaturan nama & kontak sekolah
        $response = $this->post('/admin/settings', [
            'website_name' => 'MA Al Ikhlas Modern Test',
            'telepon' => '081234567890',
            'email' => 'info@alikhlas-test.sch.id',
            'alamat' => 'Jl. Pendidikan No. 99 Jakarta',
        ]);

        $response->assertRedirect('/admin/settings');
        $this->assertDatabaseHas('settings', [
            'website_name' => 'MA Al Ikhlas Modern Test',
            'email' => 'info@alikhlas-test.sch.id',
            'telepon' => '081234567890',
        ]);

        // 2. Verifikasi di API Website & Halaman Publik
        $apiWeb = $this->get('/api/website');
        $apiWeb->assertOk();
        $apiWeb->assertJsonPath('name', 'MA Al Ikhlas Modern Test');
        $apiWeb->assertJsonPath('email', 'info@alikhlas-test.sch.id');
        $apiWeb->assertJsonPath('telepon', '081234567890');
    }

    /**
     * UJI 2: Penginputan Halaman Profil (Pages) dari Admin ke Halaman Profil Publik
     */
    public function test_admin_created_page_reflects_on_public_profil_page(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Admin membuat Halaman Profil baru (misal Visi Misi)
        $response = $this->post('/admin/pages', [
            'judul' => 'Visi Misi Unggulan Sekolah',
            'slug' => 'visi-misi',
            'konten' => 'Menjadi Madrasah Aliyah terbaik dan berakhlak mulia.',
            'aktif' => true,
        ]);

        $response->assertRedirect('/admin/pages');
        $this->assertDatabaseHas('pages', [
            'slug' => 'visi-misi',
            'judul' => 'Visi Misi Unggulan Sekolah',
        ]);

        // 2. Verifikasi di Halaman Profil Publik (/profil/visi-misi)
        $publicProfil = $this->get('/profil/visi-misi');
        $publicProfil->assertOk();
        $publicProfil->assertSee('Visi Misi Unggulan Sekolah');
        $publicProfil->assertSee('Menjadi Madrasah Aliyah terbaik dan berakhlak mulia.');
    }

    /**
     * UJI 3: Penginputan Berita/Pengumuman dari Admin ke Beranda & Informasi Publik
     */
    public function test_admin_created_post_reflects_on_public_berita_and_home(): void
    {
        $this->actingAs($this->adminUser);

        // Buat Kategori
        $category = Category::create([
            'nama' => 'Berita Sekolah',
            'slug' => 'berita-sekolah',
        ]);

        // 1. Admin mempublikasikan Berita Baru
        $response = $this->post('/admin/posts', [
            'judul' => 'Siswa Al Ikhlas Raih Medali Emas OSN 2026',
            'category_id' => $category->id_category,
            'tipe' => 'berita',
            'isi' => 'Prestasi membanggakan kembali diraih oleh tim olimpiade sains madrasah.',
            'status' => 'published',
        ]);

        $response->assertRedirect('/admin/posts?tipe=berita');
        $this->assertDatabaseHas('posts', [
            'slug' => 'siswa-al-ikhlas-raih-medali-emas-osn-2026',
            'judul' => 'Siswa Al Ikhlas Raih Medali Emas OSN 2026',
            'status' => 'published',
        ]);

        // 2. Verifikasi di API Posts Publik
        $apiPosts = $this->get('/api/posts');
        $apiPosts->assertOk();
        $apiPosts->assertSee('Siswa Al Ikhlas Raih Medali Emas OSN 2026');

        // 3. Verifikasi di Halaman List Informasi (/informasi/berita)
        $publicList = $this->get('/informasi/berita');
        $publicList->assertOk();
        $publicList->assertSee('Siswa Al Ikhlas Raih Medali Emas OSN 2026');

        // 4. Verifikasi Halaman Detail Berita (/berita/siswa-al-ikhlas-raih-medali-emas-osn-2026)
        $publicDetail = $this->get('/berita/siswa-al-ikhlas-raih-medali-emas-osn-2026');
        $publicDetail->assertOk();
        $publicDetail->assertSee('Prestasi membanggakan kembali diraih oleh tim olimpiade sains madrasah.');
    }

    /**
     * UJI 4: Penginputan Galeri Kegiatan dari Admin ke Halaman Galeri Publik
     */
    public function test_admin_created_gallery_reflects_on_public_galeri_page(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Admin menambah foto galeri kegiatan
        $response = $this->post('/admin/galleries', [
            'judul' => 'Kegiatan Kemah Bakti Pramuka 2026',
            'deskripsi' => 'Dokumentasi perkemahan sabtu minggu di bumi perkemahan.',
            'gambar_url' => '/storage/galleries/pramuka.jpg',
            'aktif' => true,
        ]);

        $response->assertRedirect('/admin/galleries');
        $this->assertDatabaseHas('galleries', [
            'judul' => 'Kegiatan Kemah Bakti Pramuka 2026',
        ]);

        // 2. Verifikasi di Halaman Galeri Publik (/galeri)
        $publicGaleri = $this->get('/galeri');
        $publicGaleri->assertOk();
        $publicGaleri->assertSee('Kegiatan Kemah Bakti Pramuka 2026');
    }
}
