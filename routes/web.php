<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExamAdminController;
use App\Http\Controllers\ExamPublicController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PpdbAdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RegisterSiswaController;
use App\Http\Controllers\ReportAdminController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SiswaAdminController;
use App\Http\Controllers\SiswaPortalController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

// ======================== SYSTEM & DATABASE INITIALIZER ========================
Route::get('/init-db', function () {
    $dbConnection = config('database.default');
    $dbHost = config("database.connections.{$dbConnection}.host", 'localhost');
    $dbName = config("database.connections.{$dbConnection}.database", 'unknown');
    
    $logs = [];
    $error = null;
    $success = false;

    try {
        // Test connection
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $logs[] = "Koneksi database ({$dbConnection} -> {$dbHost}/{$dbName}) BERHASIL!";

        // Run migrations
        \Illuminate\Support\Facades\Artisan::call('migrate --force');
        $logs[] = "Migrasi tabel:\n" . trim(\Illuminate\Support\Facades\Artisan::output());

        // Run seeder
        \Illuminate\Support\Facades\Artisan::call('db:seed --force');
        $logs[] = "Seeding data default & akun demo:\n" . trim(\Illuminate\Support\Facades\Artisan::output());

        $success = true;
    } catch (\Throwable $e) {
        $error = $e->getMessage();
        $logs[] = "ERROR: " . $e->getMessage();
    }

    $statusColor = $success ? 'emerald' : 'rose';
    $statusTitle = $success ? 'Database Berhasil Diinisialisasi! 🎉' : 'Inisialisasi Database Mengalami Kendala';
    $statusDesc = $success 
        ? 'Seluruh tabel dan akun demo telah siap digunakan.' 
        : 'Periksa pesan error di bawah ini untuk memastikan koneksi database sudah benar.';

    $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inisialisasi Database - CMS Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-{$statusColor}-500/20 text-{$statusColor}-400 border border-{$statusColor}-500/30 flex items-center justify-center font-bold text-xl">
                {$statusColor[0]}
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-white">{$statusTitle}</h1>
                <p class="text-xs sm:text-sm text-slate-400">{$statusDesc}</p>
            </div>
        </div>

        <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-4 font-mono text-xs text-slate-300 overflow-x-auto max-h-64 whitespace-pre-wrap">
HTML;
    $html .= htmlspecialchars(implode("\n\n", $logs));
    $html .= <<<HTML
        </div>

        <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-4 space-y-3">
            <h2 class="text-xs uppercase font-bold text-amber-400 tracking-wider">Kredensial Akun Demo:</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-700/80">
                    <div class="font-bold text-white">Super Admin</div>
                    <div class="text-slate-400">admin@demo.com</div>
                    <div class="text-amber-300 font-mono text-[11px]">password123</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-700/80">
                    <div class="font-bold text-white">Guru / Operator</div>
                    <div class="text-slate-400">guru@demo.com</div>
                    <div class="text-amber-300 font-mono text-[11px]">password123</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-700/80">
                    <div class="font-bold text-white">Siswa Demo</div>
                    <div class="text-slate-400">siswa@demo.com</div>
                    <div class="text-amber-300 font-mono text-[11px]">password123</div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="/" class="flex-1 text-center px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-sm transition-all shadow-lg shadow-emerald-500/20">
                Buka Halaman Utama Website &rarr;
            </a>
            <a href="/login/admin" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-sm transition-all border border-slate-700">
                Login Admin
            </a>
        </div>
    </div>
</body>
</html>
HTML;

    return response($html, $success ? 200 : 500)->header('Content-Type', 'text/html; charset=utf-8');
})->name('system.init-db');

Route::get('/health', fn() => response()->json(['status' => 'ok', 'time' => now()->toIso8601String()]));

// ======================== PUBLIC PAGES ========================
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/profil/detail/{slug}', [PublicController::class, 'showPageDetail'])->name('public.profil.detail');
Route::get('/profil/{slug}', [PublicController::class, 'profilPage'])->name('public.profil.page');
Route::get('/informasi/{tipe}', [PublicController::class, 'informasiPage'])->name('public.informasi');
Route::get('/galeri', [PublicController::class, 'galeriPage'])->name('public.galeri');
Route::get('/ppdb', [PublicController::class, 'ppdbPage'])->name('public.ppdb');
Route::post('/ppdb', [PublicController::class, 'storePpdb'])->name('public.ppdb.store');
Route::get('/ppdb/download-formulir', [PublicController::class, 'downloadFormulirPpdb'])->name('public.ppdb.download-formulir');
Route::get('/berita/{slug}', [PublicController::class, 'show'])->name('public.post.show');

// ======================== UJIAN ONLINE PUBLIK ========================
Route::get('/ujian', [ExamPublicController::class, 'index'])->name('public.ujian.index');
Route::get('/ujian/{exam}/confirm', [ExamPublicController::class, 'confirm'])->name('public.ujian.confirm');
Route::post('/ujian/{exam}/start', [ExamPublicController::class, 'start'])->name('public.ujian.start');
Route::get('/ujian/session/{attempt}', [ExamPublicController::class, 'session'])->name('public.ujian.session');
Route::post('/ujian/session/{attempt}/answer', [ExamPublicController::class, 'saveAnswer'])->name('public.ujian.saveAnswer');
Route::post('/ujian/session/{attempt}/finish', [ExamPublicController::class, 'finish'])->name('public.ujian.finish');
Route::get('/ujian/session/{attempt}/result', [ExamPublicController::class, 'result'])->name('public.ujian.result');

// ======================== PUBLIC API ========================
Route::get('/api/website', [PublicController::class, 'website'])->name('api.website');
Route::get('/api/posts', [PublicController::class, 'posts'])->name('api.posts');
Route::get('/api/posts/{slug}', [PublicController::class, 'post'])->name('api.post');
Route::get('/api/pages', [PublicController::class, 'pages'])->name('api.pages');
Route::get('/api/galleries', [PublicController::class, 'galleries'])->name('api.galleries');
// Rute Login Terpisah (Siswa & Admin)
Route::get('/login', fn() => redirect('/login/siswa'))->name('login');
Route::get('/login/siswa', [AuthController::class, 'showLoginSiswaForm'])->name('login.siswa');
Route::post('/login/siswa', [AuthController::class, 'loginSiswa'])->name('login.siswa.store');

Route::get('/login/admin', [AuthController::class, 'showLoginAdminForm'])->name('login.admin');
Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin.store');

// Registrasi Siswa
Route::get('/register', [RegisterSiswaController::class, 'showRegisterForm'])->name('register');
Route::post('/register/siswa', [RegisterSiswaController::class, 'register'])->name('register.siswa.store');

// ======================== AUTH & GUEST ========================
Route::middleware('guest')->group(function () {
    // Forgot Password & Reset Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ======================== ADMIN & PROTECTED (auth required) ========================
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/api/me', [AuthController::class, 'me'])->name('api.me');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ======================== PORTAL SISWA ========================
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/presensi', [SiswaPortalController::class, 'presensi'])->name('presensi');
        Route::get('/tugas', [SiswaPortalController::class, 'tugas'])->name('tugas');
        Route::post('/tugas/{tugas}', [SiswaPortalController::class, 'storeTugas'])->name('tugas.store');
        Route::get('/nilai', [SiswaPortalController::class, 'nilai'])->name('nilai');
        Route::get('/nilai/cetak', [SiswaPortalController::class, 'printDetailNilaiSaya'])->name('nilai.cetak');
        Route::get('/password', [SiswaPortalController::class, 'showChangePasswordForm'])->name('password');
        Route::post('/password', [SiswaPortalController::class, 'updatePassword'])->name('password.update');
    });

    Route::prefix('admin')->name('admin.')->group(function () {

        // --- User Management ---
        Route::middleware('permission:Kelola User')->group(function () {
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
            Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

            Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
            Route::get('/roles/create', [AdminController::class, 'createRole'])->name('roles.create');
            Route::post('/roles', [AdminController::class, 'storeRole'])->name('roles.store');
            Route::get('/roles/{role}/edit', [AdminController::class, 'editRole'])->name('roles.edit');
            Route::put('/roles/{role}', [AdminController::class, 'updateRole'])->name('roles.update');
            Route::delete('/roles/{role}', [AdminController::class, 'deleteRole'])->name('roles.delete');

            Route::get('/permissions', [AdminController::class, 'permissions'])->name('permissions');
            Route::get('/permissions/create', [AdminController::class, 'createPermission'])->name('permissions.create');
            Route::post('/permissions', [AdminController::class, 'storePermission'])->name('permissions.store');
            Route::get('/permissions/{permission}/edit', [AdminController::class, 'editPermission'])->name('permissions.edit');
            Route::put('/permissions/{permission}', [AdminController::class, 'updatePermission'])->name('permissions.update');
            Route::delete('/permissions/{permission}', [AdminController::class, 'deletePermission'])->name('permissions.delete');

            // Activity Log
            Route::get('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index');
            Route::delete('/activity-logs', [\App\Http\Controllers\ActivityLogController::class, 'destroyAll'])->name('activity-logs.destroy-all');
        });

        // --- Modul Manajemen Siswa & Akademik ---
        Route::middleware('permission:Kelola Akademik,Kelola User')->group(function () {
            // Data Siswa (CRUD & Whitelist Email)
            Route::get('/siswa', [SiswaAdminController::class, 'indexSiswa'])->name('siswa.index');
            Route::get('/siswa/create', [SiswaAdminController::class, 'createSiswa'])->name('siswa.create');
            Route::post('/siswa', [SiswaAdminController::class, 'storeSiswa'])->name('siswa.store');
            Route::get('/siswa/{siswa}/edit', [SiswaAdminController::class, 'editSiswa'])->name('siswa.edit');
            Route::put('/siswa/{siswa}', [SiswaAdminController::class, 'updateSiswa'])->name('siswa.update');
            Route::post('/siswa/{siswa}/reset-password', [SiswaAdminController::class, 'resetPasswordSiswa'])->name('siswa.reset-password');
            Route::delete('/siswa/{siswa}', [SiswaAdminController::class, 'deleteSiswa'])->name('siswa.delete');

            // Presensi Siswa
            Route::get('/presensi', [SiswaAdminController::class, 'indexPresensi'])->name('presensi.index');
            Route::get('/presensi/print', [SiswaAdminController::class, 'printPresensiBulanan'])->name('presensi.print');
            Route::post('/presensi', [SiswaAdminController::class, 'storePresensi'])->name('presensi.store');

            // Tugas & Penilaian
            Route::get('/tugas', [SiswaAdminController::class, 'indexTugas'])->name('tugas.index');
            Route::get('/tugas/create', [SiswaAdminController::class, 'createTugas'])->name('tugas.create');
            Route::post('/tugas', [SiswaAdminController::class, 'storeTugas'])->name('tugas.store');
            Route::get('/tugas/{tugas}', [SiswaAdminController::class, 'showTugas'])->name('tugas.show');
            Route::put('/tugas/pengumpulan/{pengumpulan}/nilai', [SiswaAdminController::class, 'updateNilaiTugas'])->name('tugas.nilai.update');
            Route::delete('/tugas/{tugas}', [SiswaAdminController::class, 'deleteTugas'])->name('tugas.delete');

            // Rekapitulasi & Perankingan
            Route::get('/rekap-akademik', [SiswaAdminController::class, 'rekapAkademik'])->name('rekap.index');
            Route::get('/rekap-akademik/print', [SiswaAdminController::class, 'printRekapAkademik'])->name('rekap.print');
            Route::get('/rekap-akademik/print-siswa/{siswa}', [SiswaAdminController::class, 'printDetailNilaiSiswa'])->name('rekap.print-siswa');
            Route::post('/rekap-akademik/nilai-manual', [SiswaAdminController::class, 'storeNilaiManual'])->name('rekap.nilai-manual.store');
        });

        // --- Pusat Laporan (Reports Center) ---
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportAdminController::class, 'index'])->name('index');

            // Print Routes (PDF / KOP Sekolah)
            Route::get('/print/siswa', [ReportAdminController::class, 'printSiswa'])->name('print.siswa');
            Route::get('/print/nilai', [ReportAdminController::class, 'printNilai'])->name('print.nilai');
            Route::get('/print/presensi', [ReportAdminController::class, 'printPresensi'])->name('print.presensi');
            Route::get('/print/ujian', [ReportAdminController::class, 'printUjian'])->name('print.ujian');
            Route::get('/print/ppdb', [ReportAdminController::class, 'printPpdb'])->name('print.ppdb');
            Route::get('/print/activity', [ReportAdminController::class, 'printActivity'])->name('print.activity');

            // Export CSV / Excel Routes
            Route::get('/export/siswa', [ReportAdminController::class, 'exportSiswaCsv'])->name('export.siswa');
            Route::get('/export/nilai', [ReportAdminController::class, 'exportNilaiCsv'])->name('export.nilai');
            Route::get('/export/presensi', [ReportAdminController::class, 'exportPresensiCsv'])->name('export.presensi');
            Route::get('/export/ujian', [ReportAdminController::class, 'exportUjianCsv'])->name('export.ujian');
            Route::get('/export/ppdb', [ReportAdminController::class, 'exportPpdbCsv'])->name('export.ppdb');
            Route::get('/export/activity', [ReportAdminController::class, 'exportActivityCsv'])->name('export.activity');
        });

        // --- Settings & PPDB ---
        Route::middleware('permission:Kelola Settings')->group(function () {
            Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
            Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');

            // Data Pendaftar PPDB
            Route::get('/ppdb', [PpdbAdminController::class, 'index'])->name('ppdb.index');
            Route::get('/ppdb/create', [PpdbAdminController::class, 'create'])->name('ppdb.create');
            Route::post('/ppdb', [PpdbAdminController::class, 'store'])->name('ppdb.store');

            // Pengaturan Field Kustom Formulir PPDB (Tambah Data Sesuai Kebutuhan Sekolah)
            Route::get('/ppdb/fields', [\App\Http\Controllers\PpdbCustomFieldController::class, 'index'])->name('ppdb.fields.index');
            Route::post('/ppdb/fields', [\App\Http\Controllers\PpdbCustomFieldController::class, 'store'])->name('ppdb.fields.store');
            Route::put('/ppdb/fields/{field}', [\App\Http\Controllers\PpdbCustomFieldController::class, 'update'])->name('ppdb.fields.update');
            Route::post('/ppdb/fields/{field}/toggle', [\App\Http\Controllers\PpdbCustomFieldController::class, 'toggle'])->name('ppdb.fields.toggle');
            Route::delete('/ppdb/fields/{field}', [\App\Http\Controllers\PpdbCustomFieldController::class, 'destroy'])->name('ppdb.fields.delete');
            Route::get('/ppdb/print-all', [PpdbAdminController::class, 'printAll'])->name('ppdb.print-all');
            Route::get('/ppdb/{id}/print', [PpdbAdminController::class, 'printSingle'])->name('ppdb.print-single');
            Route::get('/ppdb/{id}', [PpdbAdminController::class, 'show'])->name('ppdb.show');
            Route::put('/ppdb/{id}/status', [PpdbAdminController::class, 'updateStatus'])->name('ppdb.update-status');
            Route::delete('/ppdb/{id}', [PpdbAdminController::class, 'destroy'])->name('ppdb.delete');
        });

        // --- Website Content ---
        Route::middleware('permission:Kelola Website')->group(function () {
            // Halaman Profil
            Route::get('/pages', [PageController::class, 'index'])->name('pages');
            Route::get('/pages/section/{category}', [PageController::class, 'section'])->name('pages.section');
            Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
            Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
            Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
            Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
            Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('pages.delete');

            // Galeri
            Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries');
            Route::get('/galleries/create', [GalleryController::class, 'create'])->name('galleries.create');
            Route::post('/galleries', [GalleryController::class, 'store'])->name('galleries.store');
            Route::get('/galleries/{gallery}/edit', [GalleryController::class, 'edit'])->name('galleries.edit');
            Route::put('/galleries/{gallery}', [GalleryController::class, 'update'])->name('galleries.update');
            Route::delete('/galleries/{gallery}', [GalleryController::class, 'destroy'])->name('galleries.delete');

            // Kategori & Tag
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/tags', [TagController::class, 'index'])->name('tags');
            Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
        });

        // --- Konten: Artikel, Berita, Pengumuman ---
        Route::middleware('permission:Tambah Berita')->group(function () {
            Route::get('/posts', [PostController::class, 'index'])->name('posts');
            Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
            Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
            Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
            Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
            Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.delete');
        });

        // --- Modul Ujian / Ulangan Online ---
        Route::middleware('permission:Kelola Website')->group(function () {
            Route::get('/exams', [ExamAdminController::class, 'index'])->name('exams.index');
            Route::get('/exams/create', [ExamAdminController::class, 'create'])->name('exams.create');
            Route::post('/exams', [ExamAdminController::class, 'store'])->name('exams.store');
            Route::get('/exams/{exam}/edit', [ExamAdminController::class, 'edit'])->name('exams.edit');
            Route::put('/exams/{exam}', [ExamAdminController::class, 'update'])->name('exams.update');
            Route::delete('/exams/{exam}', [ExamAdminController::class, 'destroy'])->name('exams.delete');
            Route::get('/exams/{exam}/questions', [ExamAdminController::class, 'questions'])->name('exams.questions');
            Route::post('/exams/{exam}/questions', [ExamAdminController::class, 'storeQuestion'])->name('exams.questions.store');
            Route::get('/exams/{exam}/questions/template', [ExamAdminController::class, 'downloadQuestionTemplate'])->name('exams.questions.template');
            Route::post('/exams/{exam}/questions/import-file', [ExamAdminController::class, 'importQuestionsFile'])->name('exams.questions.import_file');
            Route::post('/exams/{exam}/questions/import-text', [ExamAdminController::class, 'importQuestionsText'])->name('exams.questions.import_text');
            Route::put('/exams/questions/{question}', [ExamAdminController::class, 'updateQuestion'])->name('exams.questions.update');
            Route::delete('/exams/questions/{question}', [ExamAdminController::class, 'destroyQuestion'])->name('exams.questions.delete');
            Route::get('/exams/{exam}/participants', [ExamAdminController::class, 'participants'])->name('exams.participants');
            Route::post('/exams/{exam}/participants', [ExamAdminController::class, 'storeParticipant'])->name('exams.participants.store');
            Route::delete('/exams/participants/{participant}', [ExamAdminController::class, 'destroyParticipant'])->name('exams.participants.delete');
            Route::get('/exams/{exam}/results', [ExamAdminController::class, 'results'])->name('exams.results');
            Route::get('/exams/{exam}/export-csv', [ExamAdminController::class, 'exportCsv'])->name('exams.export.csv');
            Route::get('/exams/{exam}/export-print', [ExamAdminController::class, 'exportPrint'])->name('exams.export.print');
            Route::get('/exams/results/{attempt}', [ExamAdminController::class, 'showResult'])->name('exams.results.detail');
            Route::get('/exams/results/{attempt}/print', [ExamAdminController::class, 'printAttempt'])->name('exams.results.print');
            Route::put('/exams/answers/{answer}/grade', [ExamAdminController::class, 'updateEssayScore'])->name('exams.answers.grade');
        });
    });
});