# 📋 Catatan Diskusi, Strategi Bisnis & Dokumentasi Sistem CMS School

Dokumen ini berisi rangkuman lengkap hasil diskusi, rekomendasi arsitektur, kalkulasi bisnis SaaS, serta perubahan teknis yang telah diimplementasikan pada proyek **CMS School**.

---

## 📄 1. Rencana Penggembangan Fitur Akademik

### A. Modul Manajemen Siswa (Presensi, Tugas & Perankingan)
* **Kebutuhan:** Sangat penting jika aplikasi difungsikan sebagai **Sistem Informasi Akademik (SIAKAD) / LMS Sekolah**.
* **Komponen Fitur:**
  - Presensi / Kehadiran Siswa (Hadir, Sakit, Izin, Alpa).
  - Pengelolaan & Pengumpulan Tugas (Assignments).
  - Rekapitulasi Nilai & Perankingan Otomatis per kelas/angkatan berdasarkan rumus:
    $$\text{Nilai Akhir} = \frac{\text{Nilai Tugas} + \text{Nilai Ulangan Harian (UH)} + \text{UTS} + \text{UAS}}{4}$$
* **Integrasi:** Terhubung langsung dengan modul Ujian Online (`Exam`) yang sudah ada di codebase.

### B. Modul Manajemen Alumni (Tracer Study)
* **Kebutuhan:** Kelanjutan alami ketika siswa kelas akhir lulus dari sekolah.
* **Fitur Utama:**
  - **Status Transition:** Eksekusi otomatis dari *Siswa Aktif* $\rightarrow$ *Alumni*.
  - **Tracer Study:** Pendataan status alumni (PTN/PTS, Bekerja, Wirausaha) yang dibutuhkan untuk penilaian **Akreditasi Sekolah**.
  - **Testimoni Alumni:** Publikasi kisah sukses alumni di halaman depan website untuk menarik calon siswa pada masa PPDB.

---

## ☁️ 2. Rekomendasi Infrastruktur & Hosting (SaaS Multi-Sekolah)

* **Server & Cloud VPS:** DigitalOcean / Hetzner / Hostinger Cloud VPS.
* **Database:** MySQL 8.0 / MariaDB (Relational SQL terbukti paling stabil untuk relasi data siswa & ujian).
* **Storage Berkas:** Local Storage (`storage/app/public`) untuk skala awal $\rightarrow$ **Cloudflare R2 / AWS S3** saat berkas foto/tugas bertambah besar.

---

## 💰 3. Estimasi Biaya Server & Strategi Harga SaaS (Multi-Sekolah)

### A. Biaya Server VPS (Infrastruktur)
* **Paket Starter (1 - 10 Sekolah):** ± **Rp 1.800.000 – Rp 2.900.000 / Tahun** *(VPS 4 GB RAM / 2 vCPU)*.
* **Paket Medium (11 - 50 Sekolah):** ± **Rp 7.500.000 / Tahun** *(VPS 8-16 GB RAM / 4 vCPU)*.
  - *Transisi dari 10 ke 11 Sekolah:* Cukup klik **Resize/Upgrade VPS** di dashboard cloud (downtime < 3 menit tanpa pindah data).

### B. Strategi Harga Bertahap (Tiered Pricing)
* **Klien Ke-1 (Promo Pilot / Testimonial):**
  - Biaya Setup: **Rp 3.000.000**
  - Biaya Langganan Tahunan: **Rp 500.000 / tahun**
* **Klien Ke-2 s/d 5:**
  - Biaya Setup: **Rp 3.500.000**
  - Biaya Langganan Tahunan: **Rp 1.000.000 / tahun**
* **Klien Ke-6 s/d 10:**
  - Biaya Setup: **Rp 4.000.000**
  - Biaya Langganan Tahunan: **Rp 1.500.000 / tahun**

#### 📊 Proyeksi Keuangan 10 Sekolah Pertama (Tahun Ke-1):
* **Total Pemasukan:** **Rp 49.000.000**
* **Modal Server Starter:** (Rp 2.500.000)
* **🔥 Profit Bersih Tahun 1:** **Rp 46.500.000**
* **Passive Income Renewal Tahun 2:** **Rp 12.000.000 / tahun** *(Bersih Rp 9,5 Juta/tahun)*.

---

## 🖼️ 4. Spesifikasi Gambar Banner Beranda
* **Ukuran Resolusi Ideal:** **1920 × 800 pixel** (atau **1920 × 1080 pixel**, Rasio 16:9 / 21:9).
* **Canva Custom Size:** `1920` (Lebar/Width) $\times$ `800` (Tinggi/Height) px.
* **Format & Ukuran Berkas:** JPG / WebP, disarankan di bawah **1 MB – 1.5 MB** per foto.

---

## 🛠️ 5. Fitur Hero Banner & Slider Foto Beranda
1. **Pengaturan Admin Panel (`SettingsController` & `admin/settings/index.blade.php`):**
   - Penambahan pengubah **Hero Tagline**, **Hero Title**, **Hero Subtitle**, dan **Teks & Link 3 Tombol Aksi**.
   - Penambahan fitur **Multiple Photo Upload** (Slider Foto) beserta daftar pratinjau foto aktif & kontrol simpan/hapus.
2. **Kustomisasi Tampilan Publik (`app.jsx` & `PublicController`):**
   - Transisi **Auto-Slider (Carousel)** foto latar setiap 5 detik dengan indikator navigasi.
   - Hilangkan overlay hijau transparan saat foto diunggah (foto tampil jernih & alami).

---

## 🔐 6. Arsitektur Pemisahan Login & Registrasi Siswa

### A. Portal Login Terpisah
- **Login Siswa**: `http://127.0.0.1:8000/login/siswa` (`LoginSiswaApp.jsx`)
  - Hanya menerima pengguna dengan peran `Siswa`. Jika Admin mencoba masuk dari portal ini, sistem akan memberitahukan untuk masuk dari Portal Admin.
- **Login Admin & Staff**: `http://127.0.0.1:8000/login/admin` (`LoginAdminApp.jsx`)
  - Menerima peran `Admin`, `Editor`, `Operator`.

### B. Form Registrasi Siswa (`/register`)
- Terdiri dari 4 kolom input: **Username**, **Email**, **Password**, dan **Confirm Password**.
- Nama pengguna (Username) fleksibel mendukung karakter spasi (contoh: `Ahmad Fauzi`).
- **Validasi Whitelist Admin**: Hanya email yang telah diinput oleh Admin di dalam sistem tabel `siswa` (halaman Data Siswa Admin) yang diizinkan menyelesaikan registrasi.

---

## 🛡️ 7. Proteksi Rute, Keamanan Akses & Ujian Online

1. **Proteksi Dashboard Admin & Siswa**:
   - Peran `Siswa` yang mengakses `/dashboard` atau `/admin/*` akan secara otomatis dialihkan ke Portal Siswa (`/siswa/dashboard`).
   - Peran `Admin` yang mengakses `/siswa/dashboard` akan secara otomatis dialihkan ke Dashboard Admin (`/dashboard`).
2. **Ujian Online Khusus Siswa (`/ujian`)**:
   - Link Ujian Online dihapuskan dari navigasi publik.
   - Akses rute `/ujian` mewajibkan pengguna login sebagai **Siswa**. Jika pengguna belum login atau bertipe Admin, sistem mengalihkan pengguna secara aman.

---

## 🎨 8. Penyamaan Sidebar Dashboard Admin & Navigasi Publik

### A. Urutan Seragam Sidebar Admin (`app.blade.php` & `DashboardApp.jsx`)
1. 🏠 **Dashboard** (`/dashboard`)
2. 🎓 **Akademik & Siswa**
   - Data Siswa (`/admin/siswa`)
   - Presensi Siswa (`/admin/presensi`)
   - Tugas Siswa (`/admin/tugas`)
   - Rekap & Perankingan (`/admin/rekap-akademik`)
   - Ujian Online (`/admin/exams`)
3. 📰 **Konten Website**
   - Halaman Profil (`/admin/pages`)
   - Galeri (`/admin/galleries`)
   - Artikel (`/admin/posts?tipe=artikel`)
   - Berita (`/admin/posts?tipe=berita`)
   - Pengumuman (`/admin/posts?tipe=pengumuman`)
4. 📋 **PPDB & Pengaturan**
   - Pendaftar PPDB (`/admin/ppdb`)
   - Settings (`/admin/settings`)
5. 🔑 **Taksonomi & Akses**
   - Kategori (`/admin/categories`)
   - Tag (`/admin/tags`)
   - User (`/admin/users`)
   - Role (`/admin/roles`)
   - Permission (`/admin/permissions`)
   - Activity Log (`/admin/activity-logs`)

### B. Konsistensi Tombol Login & Registrasi Siswa
- Tombol **Registrasi Siswa** (`/register`) dan **Login Siswa →** (`/login/siswa`) dipastikan tampil secara konsisten di seluruh halaman/modul publik (Profil Sejarah, Visi Misi, Guru, Artikel, Berita, Pengumuman, Galeri, PPDB, dll.) serta pada menu seluler (*mobile drawer*).

---

## 🌐 9. Arsitektur API, Pengujian (Postman) & Roadmap Mobile App

### A. Kondisi Arsitektur API Saat Ini
* **Arsitektur Hybrid (Monolitik Blade + REST API):** Sistem secara utama berbasis Laravel Blade (SSR), namun sudah dilengkapi beberapa endpoint REST API JSON di `PublicController` (`/api/website`, `/api/posts`, `/api/pages`, `/api/galleries`, `/api/me`).

### B. Peran Postman & Uji Coba REST API
* **Apakah Postman Wajib?** Tidak wajib secara teknis (sistem tetap berjalan tanpa Postman), namun **SANGAT DIREKOMENDASIKAN** selama tahap *development*.
* **Fungsi Utama Postman:**
  - Menguji respon endpoint API (Create, Read, Update, Delete) tanpa menunggu tampilan frontend/mobile selesai.
  - Mengelola token autentikasi (Bearer Token Sanctum) via Environment Variables.
  - Mengekspor *Postman Collection* sebagai dokumentasi API untuk developer Frontend/Mobile.

### C. Rekomendasi Roadmap Integrasi Aplikasi Mobile (Android & iOS)
1. **Pendekatan Dual-Serve (Hybrid Web & API):** Pertahankan Web Admin & Publik berbasis Blade, lalu lengkapi rute API untuk melayani aplikasi mobile dari satu backend Laravel yang sama.
2. **Setup Autentikasi Token (`Laravel Sanctum`):** Jalankan `php artisan install:api` dan gunakan `Bearer Token` untuk sesi login di Mobile App.
3. **Standarisasi JSON (`Laravel API Resources`):** Gunakan `JsonResource` untuk menjaga konsistensi struktur respon JSON.
4. **Push Notification:** Integrasi Firebase Cloud Messaging (FCM) untuk notifikasi tugas, pengumuman, dan ujian langsung ke HP siswa/orang tua.
5. **Dokumentasi Otomatis:** Gunakan package `dedoc/scramble` (`http://localhost:8000/docs/api`).
6. **Framework Mobile Disarankan:** **Flutter (Dart)** (Rekomendasi Utama) untuk menghasilkan aplikasi Android & iOS native dari satu codebase.

---

---

## 🎨 11. Custom Error Pages (Pengganti Tampilan Default Laravel)
* **Master Layout (`resources/views/errors/layout.blade.php`):**
  - Desain modern bernuansa *dark-slate & emerald* dengan ambient glow serta tipografi Google Fonts (*Plus Jakarta Sans*).
  - Terintegrasi otomatis dengan Logo & Nama Sekolah dari database pengaturan.
  - Tombol aksi: **Kembali ke Beranda**, **Halaman Sebelumnya**, dan shortcut **Masuk Portal / Dashboard Admin**.
  - Dilengkapi kontak resmi sekolah (Email & Telepon).
* **Daftar Halaman HTTP Error:**
  - `404.blade.php` (Halaman Tidak Ditemukan / Page Not Found)
  - `403.blade.php` (Akses Ditolak / Forbidden)
  - `419.blade.php` (Sesi Formulir Kedaluwarsa / CSRF)
  - `500.blade.php` (Kendala Server Internal)
  - `503.blade.php` (Pemeliharaan Berkala / Maintenance Mode)
  - `429.blade.php` (Terlalu Banyak Permintaan / Rate Limiting)

---

## 🧭 12. Pusat Laporan & Penyeragaman Desain Sidebar Admin
* **Menu Pusat Laporan di Seluruh Halaman Admin (`app.blade.php` & `DashboardApp.jsx`):**
  1. 📊 **Pusat Laporan** (`/admin/reports`)
  2. 👥 **Lap. Data Siswa** (`/admin/reports?type=siswa`)
  3. 📈 **Lap. Nilai & Ranking** (`/admin/reports?type=nilai`)
  4. 📅 **Lap. Presensi Siswa** (`/admin/reports?type=presensi`)
  5. ✅ **Lap. Hasil Ujian (CBT)** (`/admin/reports?type=ujian`)
  6. 📋 **Lap. Pendaftar PPDB** (`/admin/reports?type=ppdb`)
* **Standardisasi Visual:** Seluruh menu menggunakan ikon SVG penuh yang elegan (menghilangkan bullet dot), ukuran/padding seragam (`px-3 py-2.5 rounded-[5px] text-sm`), serta efek highlight aktif yang konsisten di semua halaman.
* **Kompilasi Frontend:** Aset React & Vite telah di-build ulang dengan `npm run build` (0 error).

---

## 📝 13. Klarifikasi Data Peserta vs Nilai pada Modul Ujian Online
* **Peserta Whitelist (`exam_participants`):** Menampung daftar siswa yang dibatasi untuk boleh mengikuti ujian. Jika opsi pembatasan tidak diaktifkan (Ujian Terbuka Umum), maka angka ini bernilai **0** karena seluruh siswa bebas mengakses tanpa didaftarkan manual terlebih dahulu.
* **Nilai Ujian (`exam_attempts`):** Menampung data siswa yang telah menyelesaikan pengerjaan ujian CBT dan nilainya tersimpan di database (halaman Hasil Ujian & Rekapitulasi).
* **Integritas Modul Ujian:** Modul khusus Ujian Online (`/admin/exams`) tetap berada dalam kondisi setup awal yang utuh.

---

## ☁️ 14. Rekomendasi VPS & Hosting Uji Coba (Free Tier / 1 Bulan)
* **Pilihan Utama (Gratis Selamanya):** **Oracle Cloud Always Free**
  - **Spesifikasi:** 4 OCPU ARM Ampere + 24 GB RAM + 200 GB Storage SSD + 10 TB Bandwidth/Bulan.
  - **Dokumentasi Resmi:** [Oracle Cloud Always Free Resources](https://docs.oracle.com/en-us/iaas/Content/FreeTier/freetier_topic-Always_Free_Resources.htm)
  - **Portal Pendaftaran:** [https://www.oracle.com/cloud/free/](https://www.oracle.com/cloud/free/)
  - **Kelebihan:** Server VPS murni (Full Root SSH), aktif 24 jam non-stop tanpa batas kedaluwarsa, siap dipasang Nginx, PHP 8.2, MySQL 8.0, dan SSL Let's Encrypt.
* **Pilihan Alternatif Uji Coba 1-2 Bulan:** **DigitalOcean ($200 Trial Credit / 60 Hari)**.
* **Rencana Selanjutnya:** Setelah akun VPS dibuat, kita akan melakukan setup server, migrasi database, dan mempersiapkan sistem terpisah untuk Owner/Master Panel.

---

## 🎨 15. Standarisasi Logo Default (Y-School) & Fitur Hapus Logo
* **Logo Default (Fallback):**
  - Menggunakan logo resmi **Y-School** (`public/images/default-logo.png`) saat pengaturan logo di sistem/database kosong.
  - Diterapkan pada Navbar Publik, Footer, Sidebar Admin, Header Portal Siswa, Header CBT, Login/Register Siswa, dan Preview Pengaturan.
  - **Kop Surat & Cetak Laporan:** Tetap bersih dan tidak terpengaruh (hanya menampilkan logo resmi jika Admin mengunggahnya).
* **Fitur Hapus Logo & Media:**
  - Menambahkan checkbox **"Hapus Logo"**, **"Hapus Logo Instansi"**, dan **"Hapus Favicon"** pada halaman [Pengaturan Sistem](/admin/settings).
  - Saat dicentang dan disimpan, berkas lama di storage server akan dihapus secara bersih dan nilai di database diatur ke `null` (kembali menggunakan logo default).
  - Perbaikan inisialisasi variabel `$setting` di awal method `SettingsController::store()` untuk mencegah error `Undefined variable $setting`.
  - Berkas fisik `public/favicon.ico` dan seluruh tag `<head>` diperbarui mengarah ke `images/default-logo.png`.

---

## 🎨 16. Rancangan Kustomisasi Tema Warna Website (Theme Color Customizer)
* **Analisis Performa:**
  - **Zero Server Overhead / Sangat Ringan (0% beban tambahan):** Menggunakan native *CSS Custom Properties (`:root`)* tanpa perlu compile ulang Vite/Tailwind saat warna diubah oleh klien.
  - Warna disimpan sebagai string kode HEX (`#059669`, `#1e40af`, `#991b1b`, dll.) di database `settings`.
* **Cakupan Elemen UI yang Menyesuaikan Tema:**
  - **Website Publik:** Tombol aksi utama (CTA PPDB & Portal Siswa), hover link menu navigasi, garis aktif menu, badge kategori berita/artikel, ikon fasilitas/kurikulum, dan ikon sosial media di footer.
  - **Portal Siswa & CBT:** Tab navigasi aktif siswa, tombol mulai/kirim ujian CBT, indikator nomor soal aktif.
  - **Form Login & Registrasi:** Tombol *Masuk* & *Daftar*, *focus ring* border input, dan tautan teks aksi.
  - **Panel Admin:** Highlight menu aktif pada sidebar, tombol tambah data/simpan, badge status.
  - **Elemen yang Dipertahankan:** Latar belakang konten tetap putih/terang dengan teks gelap kontras tinggi agar keterbacaan tetap optimal, serta warna status baku (Merah untuk Hapus/Peringatan, Kuning untuk Tertunda).
* **Mockup Visual yang Tersedia di Proyek:**
  - 🖼️ [theme_color_preview.jpg](file:///c:/cms-school/theme_color_preview.jpg): Perbandingan 3 tema warna (Emerald, Royal Blue, Crimson).
  - 🖼️ [tampilan_halaman_depan.jpg](file:///c:/cms-school/tampilan_halaman_depan.jpg): Rancangan penuh beranda website sekolah.
  - 🖼️ [tampilan_login_registrasi_simpel.jpg](file:///c:/cms-school/tampilan_login_registrasi_simpel.jpg): Desain form login & registrasi flat, simpel, dan seragam tanpa efek glow berlebih.

---

## 👑 17. Arsitektur & Rancangan Database Sistem Manajemen CMS (Master / Owner Panel)

### A. 4 Menu Inti Sederhana
1. 📊 **Dashboard Ringkas:** Status sistem *(Live / Uji Coba)*, sisa masa aktif tagihan, jumlah log aktivitas tercatat.
2. 💰 **Tanggal Live & Tagihan (Go-Live & Billing):** Input Tanggal Live resmi, pilihan siklus *(Bulanan / Tahunan)*, nominal tagihan, hitung otomatis jatuh tempo, dan peringatan tagihan.
3. 🛡️ **Master Log Aktivitas & Bersihkan Log:** Menampilkan riwayat aktivitas pengguna yang terfilter sejak `created_at >= tanggal_live`. Tombol **"Bersihkan Log Aktivitas"** eksklusif hanya ada di panel Master (tidak ada di Admin Sekolah).
4. 💾 **Backup Database Sederhana:** 1-klik unduh berkas cadangan database `.sql`.

### B. Rancangan Skema Database (ERD)

```text
┌───────────────────────────────┐               ┌───────────────────────────────┐
│     system_subscriptions      │ 1           * │       billing_invoices        │
├───────────────────────────────┤               ├───────────────────────────────┤
│ id (PK)                       │───────────────│ id (PK)                       │
│ is_live (BOOLEAN)             │               │ subscription_id (FK)          │
│ tanggal_live (DATETIME)       │               │ nomor_invoice (VARCHAR)       │
│ siklus_tagihan (VARCHAR)      │               │ periode (VARCHAR)             │
│ nominal_tagihan (DECIMAL)     │               │ nominal (DECIMAL)             │
│ tanggal_jatuh_tempo (DATE)    │               │ status (ENUM: lunas/pending)  │
│ status_layanan (VARCHAR)      │               │ tanggal_dibayar (DATE)        │
│ masa_tenggang_hari (INT)      │               │ timestamps                    │
│ timestamps                    │               └───────────────────────────────┘
└───────────────────────────────┘

┌───────────────────────────────┐               ┌───────────────────────────────┐
│             users             │ 1           * │         activity_logs         │
├───────────────────────────────┤               ├───────────────────────────────┤
│ id_user (PK)                  │───────────────│ id (PK)                       │
│ name, username, email, dll    │───────────────│ user_id (FK)                  │
└───────────────────────────────┘               │ action, description           │
                                                │ ip_address, user_agent        │
                                                │ created_at (>= tanggal_live)  │
                                                └───────────────────────────────┘
```

### C. Keamanan & Portal Login Terpisah
* **Portal Login Master:** Rute terpisah (contoh: `/master/login`) dengan role khusus `Owner / Superadmin`.
* **Proteksi Akses:** Admin sekolah, Guru, dan Siswa otomatis ditolak (403 Forbidden) jika mengakses panel Master.
* **Metode Integrasi:**
  - **Metode 1 (Modular Internal):** Rute `/master/*` terintegrasi langsung dalam codebase & database yang sama (Paling praktis & cepat).
  - **Metode 2 (REST API Terpusat):** Master Panel di server tersendiri yang berkomunikasi ke website sekolah via REST API JSON + API Key (Untuk skala SaaS multi-sekolah).

---

## 🚀 18. Panduan Berkas Penting, Penerbitan Hosting (Production), & Harmonisasi Tema PPDB

### A. Berkas-Berkas Kunci Proyek (Core Files Map)
* **Konfigurasi:** `.env`, `vite.config.js`, `package.json`, `CATATAN_DISKUSI.md`.
* **Routing:** `routes/web.php` (Pusat rute publik, auth, CBT, dan admin).
* **Controllers Utama:** `PublicController.php`, `AuthController.php`, `SettingsController.php`, `ExamController.php`, `SiswaController.php`, `ReportController.php`.
* **Database Models:** `User.php`, `Setting.php`, `Student.php`, `Exam.php`, `PpdbRegistration.php`, `ActivityLog.php`.
* **Layouts & Blade Views:** `public/layouts/app.blade.php`, `admin/layouts/app.blade.php`, `siswa/layouts/app.blade.php`, `public/ppdb.blade.php`, `errors/layout.blade.php`.
* **Frontend React:** `resources/js/app.jsx`, `DashboardApp.jsx`, `LoginSiswaApp.jsx`.
* **Aset Publik:** `public/images/default-logo.png`, `public/favicon.ico`.

### B. Berkas yang DILARANG / Tidak Boleh Diunggah ke Server Hosting
1. **`node_modules/`:** Berukuran raksasa ratusan MB. Cukup unggah hasil compile di `public/build/`.
2. **`vendor/`:** Cukup install di server menggunakan `composer install --no-dev -o` (kecuali cPanel tanpa SSH).
3. **`.env` lokal:** Jangan unggah file lokal. Buat `.env` baru di server dengan konfigurasi database server dan set `APP_ENV=production` & `APP_DEBUG=false`.
4. **Folder `.git/`:** Rawan kebocoran kode (*git exposure* jika upload ZIP/FTP manual).
5. **`bootstrap/cache/*.php`:** Hapus file cache lokal (`config.php`, dll.) agar tidak crash path Windows `C:\` di server Linux.
6. **`storage/logs/laravel.log`:** Bersihkan log lokal sebelum upload.

### C. Pengertian Mode Production (Produksi)
* **Production** adalah kondisi website yang sudah online resmi di domain publik (`https://...`), dapat diakses 24 jam oleh pengguna nyata (siswa, guru, masyarakat), dengan prioritas pada **keamanan** (menonaktifkan debugger lokal untuk mencegah kebocoran data) dan **kecepatan** (aset dikompresi & dicache).

### D. Penyesuaian Tombol & Form PPDB ke Tema Oranye / Amber
* **Tombol "Cetak / Download Formulir PDF" (Pendaftaran Offline):** Diubah dari hijau (`bg-emerald-600`) menjadi **Amber/Oranye tema (`bg-amber-500 hover:bg-amber-400 text-slate-950`)**.
* **Header & Ikon Form PPDB:** Diselaraskan ke aksen oranye/amber (`text-amber-500`, `border-amber-100`, `focus:ring-amber-400`).
* **Tombol Submit Form Online & Badge Kontak:** Diselaraskan memakai tema oranye/amber solid.






