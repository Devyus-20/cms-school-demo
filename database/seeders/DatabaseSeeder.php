<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator dengan akses penuh ke seluruh sistem']
        );

        $editorRole = Role::firstOrCreate(
            ['name' => 'Editor'],
            ['description' => 'Editor konten berita dan artikel sekolah']
        );

        $operatorRole = Role::firstOrCreate(
            ['name' => 'Operator'],
            ['description' => 'Operator pengelolaan data sekolah']
        );

        $siswaRole = Role::firstOrCreate(
            ['name' => 'Siswa'],
            ['description' => 'Akses halaman portal siswa, presensi, tugas, dan ujian online']
        );

        // 2. Fixed System Permissions
        $permissions = [
            [
                'name' => 'Kelola User',
                'description' => 'Akses Manajemen User Admin, Role & Permission, serta Activity Logs Security',
            ],
            [
                'name' => 'Kelola Akademik',
                'description' => 'Akses Data Siswa, Presensi Kehadiran, Tugas Sekolah, & Rekap Nilai Akademik',
            ],
            [
                'name' => 'Kelola Website',
                'description' => 'Akses Halaman Profil, Galeri Foto, Kategori, Tag, & Ujian Online CBT',
            ],
            [
                'name' => 'Tambah Berita',
                'description' => 'Akses Membuat, Mengedit, & Mempublikasikan Berita, Pengumuman, dan Agenda',
            ],
            [
                'name' => 'Kelola Settings',
                'description' => 'Akses Pengaturan Website, Logo Sekolah, Favicon, Kontak, & PPDB Admin',
            ],
        ];

        $permissionModels = [];
        foreach ($permissions as $pData) {
            $permissionModels[$pData['name']] = Permission::firstOrCreate(
                ['name' => $pData['name']],
                ['description' => $pData['description']]
            );
        }

        // Attach permissions to roles
        $adminRole->permissions()->sync(array_column($permissionModels, 'id_permission'));
        $editorRole->permissions()->sync([
            $permissionModels['Tambah Berita']->id_permission,
            $permissionModels['Kelola Website']->id_permission,
        ]);
        $operatorRole->permissions()->sync([
            $permissionModels['Kelola Akademik']->id_permission,
            $permissionModels['Tambah Berita']->id_permission,
        ]);

        // 3. User Super Admin
        User::firstOrCreate(
            ['email' => 'admin@school.test'],
            [
                'role_id' => $adminRole->id_role,
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => bcrypt('Password123!'),
                'status' => 'active',
            ]
        );

        // 4. Default Settings
        Setting::firstOrCreate(
            ['email' => 'admin@school.test'],
            [
                'website_name' => 'MA Al Ikhlas',
                'website_description' => 'CMS Sekolah Digital Terpadu',
                'logo' => null,
                'favicon' => null,
                'alamat' => 'Jl. Pendidikan No. 45, Kota Digital',
                'telepon' => '0812-3456-7890',
                'facebook' => 'https://facebook.com/maalikhlas',
                'instagram' => 'https://instagram.com/maalikhlas',
                'youtube' => 'https://youtube.com/maalikhlas',
                'linkedin' => 'https://linkedin.com/school/maalikhlas',
                'footer' => '© 2026 MA Al Ikhlas. All Rights Reserved.',
                'hero_title' => 'Membangun Generasi Berakhlak Mulia & Berprestasi',
                'hero_subtitle' => 'Pendidikan berkualitas tinggi dengan dasar keagamaan kokoh, fasilitas modern, serta pembinaan minat bakat secara optimal.',
                'ppdb_aktif' => true,
                'ppdb_tahun' => '2026/2027',
                'ppdb_keterangan' => 'Pendaftaran Peserta Didik Baru (PPDB) T.A 2026/2027 telah resmi dibuka.',
                'ppdb_jurusan' => ['MIPA / IPA', 'IPS', 'Keagamaan / MAK', 'Teknologi Informasi'],
                'google_maps' => '-6.200000, 106.816666',
            ]
        );

        // 5. Default Pages (Profil Sekolah)
        $pages = [
            [
                'judul' => 'Sejarah Sekolah',
                'slug' => 'sejarah',
                'konten' => '<p>MA Al Ikhlas didirikan dengan semangat untuk memberikan pendidikan Islam berkualitas tinggi yang memadukan keilmuan agama, akademik, serta penguasaan teknologi digital modern.</p>',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'judul' => 'Visi dan Misi',
                'slug' => 'visi-dan-misi',
                'konten' => '<h3>Visi Sekolah</h3><p>Mewujudkan generasi yang berakhlak mulia, berprestasi unggul, serta siap menghadapi tantangan era digital.</p><h3>Misi Sekolah</h3><ul><li>Menyelenggarakan pendidikan berbasis karakter dan keagamaan kokoh.</li><li>Mengembangkan potensi akademik dan non-akademik peserta didik.</li><li>Menyediakan sarana & prasarana berbasis teknologi terkini.</li></ul>',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'judul' => 'Guru dan Staff Pengajar',
                'slug' => 'guru-dan-staff',
                'konten' => '<h3>Tenaga Pendidik & Kependidikan</h3><p>MA Al Ikhlas didukung oleh guru-guru profesional lulusan perguruan tinggi terkemuka yang berdedikasi tinggi dalam mendampingi tumbuh kembang siswa.</p>',
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'judul' => 'Prestasi Sekolah & Siswa',
                'slug' => 'prestasi',
                'konten' => '<p>Berbagai capaian prestasi akademis maupun non-akademis telah diraih oleh para siswa dan guru MA Al Ikhlas tingkat Kabupaten, Provinsi, hingga Nasional.</p>',
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'judul' => 'Ekstrakurikuler',
                'slug' => 'ekstrakurikuler',
                'konten' => '<p>Kegiatan ekstrakurikuler meliputi Pramuka, Paskibra, Palang Merah Remaja (PMR), Olahraga, Seni Musik Islam, Kaligrafi, serta Club Robotika & Coding.</p>',
                'urutan' => 5,
                'aktif' => true,
            ],
            [
                'judul' => 'Fasilitas Sekolah',
                'slug' => 'fasilitas',
                'konten' => '<p>Fasilitas lengkap meliputi Ruang Kelas Ber-AC, Laboratorium Komputer, Laboratorium IPA, Perpustakaan Digital, Lapangan Olahraga, Musala, dan Free Wi-Fi Area.</p>',
                'urutan' => 6,
                'aktif' => true,
            ],
        ];

        foreach ($pages as $p) {
            \App\Models\Page::firstOrCreate(['slug' => $p['slug']], $p);
        }

        // 6. Default Menus
        $berandaMenu = \App\Models\Menu::firstOrCreate(
            ['nama_menu' => 'Beranda'],
            [
                'icon' => 'home',
                'urutan' => 1,
                'status' => 1,
            ]
        );

        $profilMenu = \App\Models\Menu::firstOrCreate(
            ['nama_menu' => 'Profil Sekolah'],
            [
                'icon' => 'school',
                'urutan' => 2,
                'status' => 1,
            ]
        );

        \App\Models\Submenu::firstOrCreate([
            'menu_id' => $profilMenu->id,
            'nama_submenu' => 'Visi & Misi',
            'url' => '/profil/visi-dan-misi',
            'urutan' => 1,
        ]);

        \App\Models\Submenu::firstOrCreate([
            'menu_id' => $profilMenu->id,
            'nama_submenu' => 'Guru & Staff',
            'url' => '/profil/guru-dan-staff',
            'urutan' => 2,
        ]);

        // 7. Default Categories & Tags
        $catPengumuman = \App\Models\Category::firstOrCreate(['nama' => 'Pengumuman', 'slug' => 'pengumuman']);
        $catPrestasi = \App\Models\Category::firstOrCreate(['nama' => 'Prestasi', 'slug' => 'prestasi']);
        $catKegiatan = \App\Models\Category::firstOrCreate(['nama' => 'Kegiatan', 'slug' => 'kegiatan']);

        $tagAkademik = \App\Models\Tag::firstOrCreate(['nama' => 'Akademik']);
        $tagEkstrakurikuler = \App\Models\Tag::firstOrCreate(['nama' => 'Ekstrakurikuler']);

        // Sample Post
        $post = \App\Models\Post::firstOrCreate(
            ['slug' => 'penerimaan-peserta-didik-baru-2026'],
            [
                'category_id' => $catPengumuman->id_category,
                'author_id' => 1,
                'judul' => 'Penerimaan Peserta Didik Baru (PPDB) T.A 2026/2027 Telah Dibuka',
                'isi' => '<p>Pendaftaran siswa baru MA Al Ikhlas resmi dibuka mulai hari ini. Silakan kunjungi halaman PPDB untuk informasi persyaratan selengkapnya.</p>',
                'status' => 'published',
                'views' => 105,
                'published_at' => now(),
            ]
        );
        $post->tags()->sync([$tagAkademik->id_tag]);

        // 8. Sample Siswa Terdaftar (Untuk Pengujian Registrasi Restricted Email)
        \App\Models\Siswa::firstOrCreate(
            ['nis' => '2026001'],
            [
                'nisn' => '0051234561',
                'nama_lengkap' => 'Ahmad Fauzi',
                'email' => 'ahmad.siswa@school.test',
                'jenis_kelamin' => 'L',
                'kelas' => 'X MIPA 1',
                'tahun_masuk' => '2026',
                'status' => 'pending_register',
                'telepon' => '081299990001',
                'alamat' => 'Jl. Merdeka No. 12',
            ]
        );

        \App\Models\Siswa::firstOrCreate(
            ['nis' => '2026002'],
            [
                'nisn' => '0051234562',
                'nama_lengkap' => 'Siti Nurhaliza',
                'email' => 'siti.siswa@school.test',
                'jenis_kelamin' => 'P',
                'kelas' => 'X MIPA 1',
                'tahun_masuk' => '2026',
                'status' => 'pending_register',
                'telepon' => '081299990002',
                'alamat' => 'Jl. Kemerdekaan No. 34',
            ]
        );

        // 9. Akun Demo
        $this->call(DemoAccountSeeder::class);
    }
}
