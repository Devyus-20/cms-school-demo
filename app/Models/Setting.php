<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_name',
        'website_description',
        'logo',
        'logo_instansi',
        'favicon',
        'alamat',
        'email',
        'telepon',
        'facebook',
        'instagram',
        'youtube',
        'linkedin',
        'footer',
        'ppdb_aktif',
        'ppdb_tahun',
        'ppdb_keterangan',
        'ppdb_link_daftar',
        'ppdb_jurusan',
        'hero_bg',
        'hero_bgs',
        'hero_tagline',
        'hero_title',
        'hero_subtitle',
        'hero_btn1_text',
        'hero_btn1_link',
        'hero_btn2_text',
        'hero_btn2_link',
        'hero_btn3_text',
        'hero_btn3_link',
        'google_maps',
        'tanggal_live',
        'info_pendaftaran_pembelajaran_judul',
        'info_pendaftaran_pembelajaran_subjudul',
        'info_pendaftaran_pembelajaran_konten',
        'info_faq_list',
    ];

    protected $casts = [
        'ppdb_aktif' => 'boolean',
        'ppdb_jurusan' => 'array',
        'hero_bgs' => 'array',
        'tanggal_live' => 'datetime',
        'info_faq_list' => 'array',
    ];

    /**
     * Dapatkan daftar jurusan sekolah (dengan fallback ke nilai default jika kosong)
     */
    public function getJurusanListAttribute(): array
    {
        if (is_array($this->ppdb_jurusan) && count($this->ppdb_jurusan) > 0) {
            return array_values(array_filter(array_map('trim', $this->ppdb_jurusan)));
        }

        return ['MIPA', 'IPS', 'Keagamaan'];
    }

    /**
     * Dapatkan daftar foto background hero banner (dengan fallback)
     */
    public function getHeroBgListAttribute(): array
    {
        $list = [];
        if (is_array($this->hero_bgs) && count($this->hero_bgs) > 0) {
            $list = array_values(array_filter($this->hero_bgs));
        } elseif (!empty($this->hero_bg)) {
            $list = [$this->hero_bg];
        }
        return $list;
    }
}
