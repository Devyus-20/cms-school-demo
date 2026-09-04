# 🚀 Panduan Hosting & VPS Gratis CMS Sekolah MA Al Ikhlas

Dokumentasi ini berisi informasi akun demo, daftar rekomendasi platform hosting & VPS gratis terbaik, serta langkah-langkah praktis untuk mempublikasikan sistem CMS Sekolah ke internet tanpa biaya langganan bulanan.

---

## 🔑 1. Informasi Akun Demo Sistem

Setelah menjalankan database seeder (`php artisan migrate --seed` atau `php artisan db:seed --class=DemoAccountSeeder`), akun demo berikut siap digunakan untuk presentasi dan pengujian sistem:

| Peran (Role) | Email / Username | Password | Deskripsi Akses & Fitur |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@demo.com` *(atau `admin_demo`)* | `password123` | Akses penuh: Kelola Pengguna, Role & Permission, Profil Sekolah, Berita/Pengumuman, Agenda, Galeri, Settings Website & PPDB. |
| **Guru / Operator** | `guru@demo.com` *(atau `guru_demo`)* | `password123` | Akses Guru & Akademik: Manajemen Siswa, Input Presensi, Kelola Nilai & Raport, Tambah Berita Sekolah. |
| **Siswa Demo** | `siswa@demo.com` *(atau `siswa_demo`)* | `password123` | Portal Siswa: Melihat Rekap Presensi Mandiri, Tugas & Materi Pembelajaran, Nilai Akademik, dan Ujian Online (CBT). |

> 💡 **Info Database Siswa:** Akun Siswa Demo di atas terhubung dengan data NIS `2026001` (Ahmad Fauzi - Kelas X MIPA 1).

---

## 🌐 2. Rekomendasi Platform Hosting & VPS 100% Gratis

Berikut adalah 3 opsi hosting dan VPS gratis terbaik yang telah diuji kompatibel dengan stack Laravel 11 + PHP 8.2 + MySQL + Vite:

### 🏆 Opsi 1: Oracle Cloud Infrastructure (OCI) Always Free VPS *(Sangat Direkomendasikan)*
* **Biaya:** Gratis Selamanya (**$0 / Permanent Free Tier**).
* **Spesifikasi:**
  - Hingga **4 OCPU ARM Ampere**, **24 GB RAM**, dan **200 GB NVMe Storage**.
  - Termasuk **Alamat IP Publik Statis Gratis**.
  - Akses penuh `root` via SSH (Bisa pasang Ubuntu 22.04 / 24.04, Docker, Nginx, PHP 8.2, MySQL, SSL Let's Encrypt).
* **Kelebihan:** Performa setara VPS berbayar kelas atas, tidak ada sleep/idle shutdown, data persisten, sangat cepat & stabil.

---

### 🥈 Opsi 2: Render.com / Koyeb + Aiven Free MySQL *(Paling Praktis Tanpa Kelola Server)*
* **Biaya:** Gratis (**Free Web Service + Free Cloud Database**).
* **Komponen:**
  - **Aplikasi Web:** Render.com (Free Web Service dengan Docker) atau Koyeb.
  - **Database MySQL:** [Aiven.io](https://aiven.io/) (Free MySQL 5GB) atau [TiDB Cloud](https://tidbcloud.com/) (Free Serverless MySQL).
* **Kelebihan:** Cukup push kode ke GitHub, Render akan otomatis melakukan build dan deploy.

---

### 🥉 Opsi 3: InfinityFree / Alwaysdata *(Web Hosting Tradisional cPanel/FTP)*
* **Biaya:** Gratis.
* **Fitur:** cPanel web hosting, phpMyAdmin, MySQL database, upload via FileZilla / FTP.
* **Kelebihan:** Cocok bagi pemula yang terbiasa dengan cPanel hosting.

---

## 🛠️ 3. Langkah-Langkah Deployment

### 📌 A. Deploy ke Oracle Cloud Always Free VPS (Metode Docker)

1. **Daftar & Buat Instance VPS:**
   - Kunjungi [oracle.com/cloud/free](https://www.oracle.com/cloud/free/) dan selesaikan pendaftaran.
   - Buat Compute Instance baru, pilih OS **Ubuntu 22.04 LTS (Minimal)** atau **Ubuntu 24.04**.
   - Unduh file SSH Private Key (`.key` / `.pem`).

2. **Login ke Server via SSH:**
   ```bash
   ssh -i your-key.key ubuntu@<IP_VPS_ANDA>
   ```

3. **Install Docker & Git di VPS:**
   ```bash
   sudo apt update && sudo apt upgrade -y
   sudo apt install -y git curl docker.io docker-compose
   sudo systemctl enable --now docker
   sudo usermod -aG docker $USER
   ```

4. **Clone Repository / Upload Folder Demo:**
   ```bash
   git clone https://github.com/username-anda/cms-school.git /home/ubuntu/cms-school
   cd /home/ubuntu/cms-school
   ```

5. **Jalankan Aplikasi dengan Docker Compose:**
   ```bash
   # Jalankan kontainer aplikasi dan MySQL secara otomatis
   docker-compose up -d --build
   ```

6. **Pasang Domain & SSL Gratis (Certbot Nginx):**
   ```bash
   sudo apt install -y certbot python3-certbot-nginx
   sudo certbot --nginx -d domainsekolahanda.com
   ```

---

### 📌 B. Deploy ke Render.com (Gratis Menggunakan GitHub)

1. Buat database MySQL gratis di [Aiven.io Console](https://console.aiven.io/) (pilih Free Tier). Catat `Host`, `Port`, `User`, `Password`, dan `Database Name`.
2. Push folder project `c:/cms-school-demo` ini ke akun GitHub / GitLab Anda.
3. Buka [dashboard.render.com](https://dashboard.render.com/) -> Pilih **New +** -> **Web Service**.
4. Hubungkan repository GitHub Anda.
5. Render akan mendeteksi file [Dockerfile](file:///c:/cms-school/Dockerfile) dan [render.yaml](file:///c:/cms-school/render.yaml).
6. Tambahkan **Environment Variables** berikut di Render:
   - `APP_ENV`: `production`
   - `APP_DEBUG`: `false`
   - `APP_KEY`: *(Jalankan `php artisan key:generate --show` lalu tempel nilainya)*
   - `DB_CONNECTION`: `mysql`
   - `DB_HOST`: *(Host dari Aiven MySQL)*
   - `DB_PORT`: *(Port dari Aiven MySQL)*
   - `DB_DATABASE`: *(Nama database)*
   - `DB_USERNAME`: *(User database)*
   - `DB_PASSWORD`: *(Password database)*
   - `RUN_MIGRATIONS`: `true`
7. Klik **Create Web Service**. Render akan otomatis mem-build frontend Vite, PHP dependencies, serta menjalankan migrasi dan akun demo. Aplikasi Anda langsung online dengan link `https://cms-school-demo.onrender.com`!

---

## 📁 4. Struktur Folder Project Demo

Project versi demo yang telah disiapkan secara terpisah dapat ditemukan di:
- **Lokasi Folder:** `c:/cms-school-demo`
- **File Siap Pakai:**
  - `Dockerfile` : Multi-stage build siap produksi (Vite + Nginx + PHP 8.2 FPM).
  - `docker-compose.yml` : Konfigurasi kontainer App + MySQL lokal / VPS.
  - `render.yaml` : Blueprint deploy otomatis untuk Render.com.
  - `.env.production.example` : Template konfigurasi production.
  - `database/seeders/DemoAccountSeeder.php` : Seeder akun demo Admin, Guru, dan Siswa.

---

## ⚡ 5. Menjalankan di Komputer Lokal (Uji Coba Demo)

Jika Anda ingin menjalankan project demo di komputer lokal Anda:

```bash
# 1. Pindah ke direktori demo
cd c:\cms-school-demo

# 2. Salin environment file
copy .env.example .env

# 3. Generate Application Key
php artisan key:generate

# 4. Jalankan migrasi dan akun demo
php artisan migrate --seed

# 5. Buat symbolic link storage aset
php artisan storage:link

# 6. Jalankan server lokal
php artisan serve
```
Buka browser di `http://127.0.0.1:8000` dan login menggunakan akun demo yang tertera pada tabel di atas.
