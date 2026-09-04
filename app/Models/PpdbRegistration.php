<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    use HasFactory;

    protected $table = 'ppdb_registrations';

    protected $fillable = [
        'no_pendaftaran',
        'nama_lengkap',
        'nisn',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'sekolah_asal',
        'nama_orang_tua',
        'no_hp',
        'email',
        'jurusan',
        'berkas',
        'status',
        'catatan',
        'data_tambahan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'data_tambahan' => 'array',
    ];

    /**
     * Generate unique registration number
     * Format: PPDB-YYYY-XXXX (e.g. PPDB-2026-0001)
     */
    public static function generateNoPendaftaran(): string
    {
        $year = date('Y');
        $prefix = "PPDB-{$year}-";

        $lastRecord = self::where('no_pendaftaran', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRecord) {
            $lastNum = (int) substr($lastRecord->no_pendaftaran, strlen($prefix));
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }

        return $prefix . $nextNum;
    }
}
