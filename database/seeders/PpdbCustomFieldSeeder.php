<?php

namespace Database\Seeders;

use App\Models\PpdbCustomField;
use App\Models\PpdbRegistration;
use Illuminate\Database\Seeder;

class PpdbCustomFieldSeeder extends Seeder
{
    public function run(): void
    {
        PpdbCustomField::truncate();

        PpdbCustomField::create([
            'label'       => 'Ukuran Seragam Sekolah',
            'field_key'   => 'ukuran_seragam_sekolah',
            'tipe'        => 'select',
            'options'     => ['S', 'M', 'L', 'XL', 'XXL', 'Custom Jumbo'],
            'placeholder' => '-- Pilih Ukuran Seragam --',
            'help_text'   => 'Pilihan seragam batik, olahraga, dan jas almamater sekolah.',
            'is_required' => true,
            'urutan'      => 1,
            'aktif'       => true,
        ]);

        PpdbCustomField::create([
            'label'       => 'No. Kartu KKS / PKH / KIP',
            'field_key'   => 'no_kartu_kks_pkh_kip',
            'tipe'        => 'text',
            'placeholder' => 'Contoh: 123456789012',
            'help_text'   => 'Kosongkan bila tidak memiliki kartu bantuan sosial pemerintah.',
            'is_required' => false,
            'urutan'      => 2,
            'aktif'       => true,
        ]);

        PpdbCustomField::create([
            'label'       => 'Pekerjaan Utama Orang Tua / Wali',
            'field_key'   => 'pekerjaan_utama_orang_tua_wali',
            'tipe'        => 'select',
            'options'     => ['PNS / ASN', 'TNI / POLRI', 'Karyawan Swasta', 'Wiraswasta / Usaha Mandiri', 'Petani / Peternak', 'Nelayan', 'Buruh', 'Pensiunan', 'Lainnya'],
            'placeholder' => '-- Pilih Pekerjaan --',
            'help_text'   => 'Pekerjaan utama kepala keluarga / penanggung jawab siswa.',
            'is_required' => true,
            'urutan'      => 3,
            'aktif'       => true,
        ]);

        PpdbCustomField::create([
            'label'       => 'Prestasi Yang Pernah Diraih',
            'field_key'   => 'prestasi_yang_pernah_diraih',
            'tipe'        => 'textarea',
            'placeholder' => 'Contoh: Juara 1 Lomba OSN Matematika Tingkat Kabupaten (2025), Juara 2 Turnamen Futsal (2024)',
            'help_text'   => 'Tuliskan nama kejuaraan, tingkat, peringkat, dan tahun (bila ada).',
            'is_required' => false,
            'urutan'      => 4,
            'aktif'       => true,
        ]);

        PpdbCustomField::create([
            'label'       => 'Minat Ekstrakurikuler',
            'field_key'   => 'minat_ekstrakurikuler',
            'tipe'        => 'checkbox',
            'options'     => ['Pramuka', 'PMR / KSR', 'Paskibra', 'Futsal / Sepakbola', 'Basket', 'Voli', 'Seni Musik / Hadroh', 'Jurnalistik & Digital Content', 'Karya Ilmiah Remaja (KIR)', "Tahfidz Al-Qur'an"],
            'help_text'   => 'Anda dapat memilih lebih dari 1 ekstrakurikuler yang diminati.',
            'is_required' => false,
            'urutan'      => 5,
            'aktif'       => true,
        ]);

        PpdbCustomField::create([
            'label'       => 'Tinggi Badan (cm)',
            'field_key'   => 'tinggi_badan_cm',
            'tipe'        => 'number',
            'placeholder' => 'Contoh: 165',
            'help_text'   => 'Tinggi badan calon siswa dalam centimeter.',
            'is_required' => false,
            'urutan'      => 6,
            'aktif'       => true,
        ]);

        // Add dummy applicant if not existing
        $sample = PpdbRegistration::first();
        if ($sample) {
            $sample->update([
                'data_tambahan' => [
                    'ukuran_seragam_sekolah' => 'L',
                    'no_kartu_kks_pkh_kip' => '3174091234560001',
                    'pekerjaan_utama_orang_tua_wali' => 'Wiraswasta / Usaha Mandiri',
                    'prestasi_yang_pernah_diraih' => 'Juara 1 Lomba MTQ Tingkat Kecamatan (2025), Juara 2 Olimpiade IPA (2024)',
                    'minat_ekstrakurikuler' => ['Pramuka', 'Tahfidz Al-Qur\'an', 'Futsal / Sepakbola'],
                    'tinggi_badan_cm' => 168,
                ]
            ]);
        } else {
            PpdbRegistration::create([
                'no_pendaftaran' => PpdbRegistration::generateNoPendaftaran(),
                'nama_lengkap' => 'Ahmad Fauzi Rahmat',
                'nisn' => '0051234567',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2010-06-12',
                'agama' => 'Islam',
                'alamat' => 'Jl. Pendidikan No. 45, RT 03/RW 02, Kebon Jeruk',
                'sekolah_asal' => 'MTs Negeri 1 Jakarta',
                'nama_orang_tua' => 'H. Suparman',
                'no_hp' => '081234567890',
                'email' => 'ahmad.fauzi@gmail.test',
                'jurusan' => 'MIPA / IPA',
                'status' => 'pending',
                'data_tambahan' => [
                    'ukuran_seragam_sekolah' => 'L',
                    'no_kartu_kks_pkh_kip' => '3174091234560001',
                    'pekerjaan_utama_orang_tua_wali' => 'Wiraswasta / Usaha Mandiri',
                    'prestasi_yang_pernah_diraih' => 'Juara 1 Lomba MTQ Tingkat Kecamatan (2025), Juara 2 Olimpiade IPA (2024)',
                    'minat_ekstrakurikuler' => ['Pramuka', 'Tahfidz Al-Qur\'an', 'Futsal / Sepakbola'],
                    'tinggi_badan_cm' => 168,
                ]
            ]);
        }
    }
}
