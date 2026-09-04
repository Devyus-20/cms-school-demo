<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Catat log aktivitas user dengan cepat & mudah.
     *
     * @param string $action Nama jenis aksi (misal: 'login', 'logout', 'create', 'update', 'delete')
     * @param string $description Deskripsi aktivitas human-readable
     * @param mixed|null $subject Objek model terkait (opsional)
     * @param int|null $userId ID User (jika null, ambil dari auth()->id())
     * @return static
     */
    public static function record(string $action, string $description, mixed $subject = null, ?int $userId = null): static
    {
        $subjectType = null;
        $subjectId = null;

        if (is_object($subject)) {
            $subjectType = get_class($subject);
            $subjectId = method_exists($subject, 'getKey') ? $subject->getKey() : ($subject->id ?? null);
        }

        return static::create([
            'user_id'      => $userId ?? auth()->id(),
            'action'       => $action,
            'description'  => $description,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'ip_address'   => request() ? request()->ip() : null,
            'user_agent'   => request() ? request()->header('User-Agent') : null,
        ]);
    }
}
