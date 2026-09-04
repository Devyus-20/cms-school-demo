<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\PengumpulanTugas;
use App\Models\Permission;
use App\Models\PresensiSiswa;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminToStudentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Role $siswaRole;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Role Admin & Permissions
        $adminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator Utama',
        ]);

        $this->siswaRole = Role::create([
            'name' => 'Siswa',
            'description' => 'Role Peserta Didik',
        ]);

        $permAcademic = Permission::create(['name' => 'Kelola Akademik']);
        $permUser     = Permission::create(['name' => 'Kelola User']);
        $permWebsite  = Permission::create(['name' => 'Kelola Website']);

        $adminRole->permissions()->attach([
            $permAcademic->id,
            $permUser->id,
            $permWebsite->id,
        ]);

        $this->adminUser = User::create([
            'role_id' => $adminRole->id,
            'name' => 'Admin Akademik',
            'username' => 'admin_akademik',
            'email' => 'admin.akademik@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);
    }

    /**
     * UJI 1: Pre-registrasi Siswa oleh Admin & Registrasi/Login Siswa
     */
    public function test_admin_pre_registers_siswa_and_siswa_can_register_and_login(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Admin mendaftarkan whitelist Siswa baru
        $response = $this->post('/admin/siswa', [
            'nis' => '10001',
            'nisn' => '0010001',
            'username' => 'budi10001',
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@siswa.test',
            'jenis_kelamin' => 'L',
            'kelas' => 'X MIPA 1',
            'tahun_masuk' => '2026',
            'telepon' => '081299998888',
        ]);

        $response->assertRedirect('/admin/siswa');
        $this->assertDatabaseHas('siswa', [
            'nis' => '10001',
            'email' => 'budi.santoso@siswa.test',
            'status' => 'pending_register',
        ]);

        // Logout Admin
        $this->post('/logout');

        // 2. Siswa menyelesaikan Registrasi Mandiri di Portal Registrasi Siswa (JSON AJAX)
        $regResponse = $this->postJson('/register/siswa', [
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@siswa.test',
            'nis' => '10001',
            'username' => 'budi10001',
            'password' => 'PasswordSiswa123!',
            'password_confirmation' => 'PasswordSiswa123!',
        ]);

        $regResponse->assertJson(['success' => true]);
        $this->assertDatabaseHas('users', [
            'email' => 'budi.santoso@siswa.test',
            'role_id' => $this->siswaRole->id,
        ]);

        // 3. Siswa Login
        $loginResponse = $this->postJson('/login/siswa', [
            'login' => 'budi.santoso@siswa.test',
            'password' => 'PasswordSiswa123!',
        ]);

        $loginResponse->assertJson(['success' => true]);

        // 4. Siswa mengakses Dashboard Siswa
        $dashboard = $this->get('/siswa/dashboard');
        $dashboard->assertOk();
        $dashboard->assertSee('Budi Santoso');
        $dashboard->assertSee('X MIPA 1');
    }

    /**
     * UJI 2: Penginputan Presensi Siswa oleh Admin -> Tampilan Presensi Siswa
     */
    public function test_admin_stores_presensi_and_siswa_views_presensi(): void
    {
        // Pre-setup Siswa & User
        $siswa = Siswa::create([
            'nis' => '10002',
            'nama_lengkap' => 'Siti Rahma',
            'email' => 'siti.rahma@siswa.test',
            'jenis_kelamin' => 'P',
            'kelas' => 'X MIPA 1',
            'tahun_masuk' => '2026',
            'status' => 'aktif',
        ]);

        $siswaUser = User::create([
            'role_id' => $this->siswaRole->id,
            'name' => $siswa->nama_lengkap,
            'username' => '10002',
            'email' => $siswa->email,
            'password' => bcrypt('PasswordSiswa123!'),
            'status' => 'active',
        ]);
        $siswa->update(['user_id' => $siswaUser->id]);

        // 1. Admin menginput Presensi (Siswa Hadir)
        $this->actingAs($this->adminUser);
        $today = date('Y-m-d');

        $response = $this->post('/admin/presensi', [
            'tanggal' => $today,
            'presensi' => [
                $siswa->id_siswa => [
                    'status' => 'hadir',
                    'keterangan' => 'Hadir Tepat Waktu',
                ],
            ],
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('presensi_siswa', [
            'siswa_id' => $siswa->id_siswa,
            'status' => 'hadir',
        ]);

        // 2. Siswa login & melihat Rekap Presensi
        $this->actingAs($siswaUser);
        $presensiPage = $this->get('/siswa/presensi');
        $presensiPage->assertOk();
        $presensiPage->assertSee('1 Hari');
        $presensiPage->assertSee('Hadir Tepat Waktu');
    }

    /**
     * UJI 3: Buat Tugas (Admin) -> Kirim Jawaban (Siswa) -> Beri Nilai (Admin) -> Rekap Nilai (Siswa)
     */
    public function test_tugas_full_workflow_from_admin_to_siswa_and_grading(): void
    {
        // Setup Siswa
        $siswa = Siswa::create([
            'nis' => '10003',
            'nama_lengkap' => 'Ahmad Dani',
            'email' => 'ahmad.dani@siswa.test',
            'jenis_kelamin' => 'L',
            'kelas' => 'X MIPA 1',
            'tahun_masuk' => '2026',
            'status' => 'aktif',
        ]);

        $siswaUser = User::create([
            'role_id' => $this->siswaRole->id,
            'name' => $siswa->nama_lengkap,
            'username' => '10003',
            'email' => $siswa->email,
            'password' => bcrypt('PasswordSiswa123!'),
            'status' => 'active',
        ]);
        $siswa->update(['user_id' => $siswaUser->id]);

        // 1. Admin membuat Tugas baru
        $this->actingAs($this->adminUser);
        $deadline = now()->addDays(2)->format('Y-m-d H:i:s');

        $this->post('/admin/tugas', [
            'judul' => 'Tugas Matematika Bab 1 Algebra',
            'mata_pelajaran' => 'Matematika',
            'kelas' => 'X MIPA 1',
            'deadline' => $deadline,
            'deskripsi' => 'Kerjakan soal 1 sampai 10 di buku latihan.',
        ])->assertRedirect('/admin/tugas');

        $tugas = Tugas::where('judul', 'Tugas Matematika Bab 1 Algebra')->first();
        $this->assertNotNull($tugas);

        // 2. Siswa Mengumpulkan Jawaban Tugas
        $this->actingAs($siswaUser);
        $this->get('/siswa/tugas')->assertSee('Tugas Matematika Bab 1 Algebra');

        $this->post("/siswa/tugas/{$tugas->id_tugas}", [
            'jawaban_teks' => 'Saya telah menyelesaikan semua soal nomor 1-10.',
        ])->assertSessionHas('success');

        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id_tugas)->where('siswa_id', $siswa->id_siswa)->first();
        $this->assertNotNull($pengumpulan);

        // 3. Admin memeriksa & memberi Nilai 95 & Catatan Evaluasi
        $this->actingAs($this->adminUser);
        $this->put("/admin/tugas/pengumpulan/{$pengumpulan->id_pengumpulan}/nilai", [
            'nilai' => 95,
            'catatan_guru' => 'Pekerjaan sangat rapi dan jawaban tepat 100%!',
        ])->assertSessionHas('success');

        $pengumpulan->refresh();
        $this->assertEquals(95, $pengumpulan->nilai);

        // 4. Siswa melihat Hasil Nilai di Tugas & Rekap Nilai
        $this->actingAs($siswaUser);
        $tugasPage = $this->get('/siswa/tugas');
        $tugasPage->assertSee('95 / 100');
        $tugasPage->assertSee('Pekerjaan sangat rapi dan jawaban tepat 100%!');

        $nilaiPage = $this->get('/siswa/nilai');
        $nilaiPage->assertSee('95');
    }

    /**
     * UJI 4: Ujian CBT Online dari Admin -> Siswa Kerjakan -> Skor Terkalkulasi
     */
    public function test_cbt_exam_workflow_admin_to_siswa_completion(): void
    {
        // Setup Siswa
        $siswa = Siswa::create([
            'nis' => '10004',
            'nama_lengkap' => 'Rina Melati',
            'email' => 'rina.melati@siswa.test',
            'jenis_kelamin' => 'P',
            'kelas' => 'X MIPA 1',
            'tahun_masuk' => '2026',
            'status' => 'aktif',
        ]);

        $siswaUser = User::create([
            'role_id' => $this->siswaRole->id,
            'name' => $siswa->nama_lengkap,
            'username' => '10004',
            'email' => $siswa->email,
            'password' => bcrypt('PasswordSiswa123!'),
            'status' => 'active',
        ]);
        $siswa->update(['user_id' => $siswaUser->id]);

        // 1. Admin membuat Ujian CBT & Tambah Soal
        $this->actingAs($this->adminUser);
        $exam = Exam::create([
            'judul' => 'Ujian Akhir Semester Fisika',
            'mata_pelajaran' => 'Fisika',
            'durasi_menit' => 60,
            'token' => 'FISIKA100',
            'tampilkan_nilai' => true,
            'waktu_mulai' => now()->subMinute(),
            'waktu_selesai' => now()->addHour(),
        ]);

        $question = ExamQuestion::create([
            'id_exam' => $exam->id_exam,
            'pertanyaan' => 'Satuan internasional untuk gaya adalah?',
            'jenis' => 'pilihan_ganda',
            'pilihan_a' => 'Newton',
            'pilihan_b' => 'Joule',
            'pilihan_c' => 'Watt',
            'pilihan_d' => 'Pascal',
            'kunci_jawaban' => 'A',
            'bobot_nilai' => 100,
        ]);

        // 2. Siswa Mengikuti Ujian CBT
        $this->actingAs($siswaUser);
        $startResponse = $this->post("/ujian/{$exam->id_exam}/start", [
            'nama_peserta' => $siswa->nama_lengkap,
            'nis_email' => $siswa->nis,
            'kelas' => $siswa->kelas,
            'token' => 'FISIKA100',
        ]);

        $attempt = ExamAttempt::where('id_exam', $exam->id_exam)->where('nis_email', $siswa->nis)->first();
        $this->assertNotNull($attempt);
        $startResponse->assertRedirect(route('public.ujian.session', $attempt));

        // 3. Siswa Menjawab Soal (AJAX)
        $this->postJson("/ujian/session/{$attempt->id_attempt}/answer", [
            'id_question' => $question->id_question,
            'jawaban' => 'A',
        ])->assertJson(['status' => 'success']);

        // 4. Siswa Selesaikan Ujian
        $this->post("/ujian/session/{$attempt->id_attempt}/finish")
            ->assertRedirect(route('public.ujian.result', $attempt));

        $attempt->refresh();
        $this->assertEquals(100.0, (float) $attempt->skor_akhir);

        // 5. Siswa melihat skor di Halaman Hasil & Rekap Nilai
        $resultPage = $this->get("/ujian/session/{$attempt->id_attempt}/result");
        $resultPage->assertSee('100.0');

        $nilaiPage = $this->get('/siswa/nilai');
        $nilaiPage->assertSee('100.0');
    }

    /**
     * UJI 5: Form Ubah Password Mandiri oleh Siswa & Reset Password oleh Admin
     */
    public function test_siswa_change_password_and_admin_reset_password(): void
    {
        // Setup Siswa
        $siswa = Siswa::create([
            'nis' => '10005',
            'nama_lengkap' => 'Doni Kusuma',
            'email' => 'doni.kusuma@siswa.test',
            'jenis_kelamin' => 'L',
            'kelas' => 'X MIPA 1',
            'tahun_masuk' => '2026',
            'status' => 'aktif',
        ]);

        $siswaUser = User::create([
            'role_id' => $this->siswaRole->id,
            'name' => $siswa->nama_lengkap,
            'username' => '10005',
            'email' => $siswa->email,
            'password' => bcrypt('OldPass123!'),
            'status' => 'active',
        ]);
        $siswa->update(['user_id' => $siswaUser->id]);

        // 1. Siswa Mengubah Password Mandiri di Dashboard Siswa
        $this->actingAs($siswaUser);
        $this->post('/siswa/password', [
            'current_password' => 'OldPass123!',
            'password' => 'NewSecretPass123!',
            'password_confirmation' => 'NewSecretPass123!',
        ])->assertSessionHas('success');

        // Logout Siswa
        $this->post('/logout');

        // Verify Siswa Login dengan Password Baru via JSON API
        $this->postJson('/login/siswa', [
            'login' => 'doni.kusuma@siswa.test',
            'password' => 'NewSecretPass123!',
        ])->assertJson(['success' => true]);

        // 2. Admin Melakukan Reset Password Siswa
        $this->actingAs($this->adminUser);
        $this->post("/admin/siswa/{$siswa->id_siswa}/reset-password", [
            'password' => 'ResetPass123!',
        ])->assertSessionHas('success');

        // Logout Admin
        $this->post('/logout');

        // Verify Siswa Login dengan Password NIS Ter-reset ('ResetPass123!')
        $this->postJson('/login/siswa', [
            'login' => 'doni.kusuma@siswa.test',
            'password' => 'ResetPass123!',
        ])->assertJson(['success' => true]);
    }
}
