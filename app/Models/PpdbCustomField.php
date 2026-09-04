<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PpdbCustomField extends Model
{
    use HasFactory;

    protected $table = 'ppdb_custom_fields';

    protected $fillable = [
        'label',
        'field_key',
        'tipe',
        'options',
        'placeholder',
        'help_text',
        'is_required',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Boot method for generating field_key automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->field_key)) {
                $base = Str::slug($model->label, '_');
                $key = $base;
                $count = 1;
                while (static::where('field_key', $key)->exists()) {
                    $key = $base . '_' . $count++;
                }
                $model->field_key = $key;
            }
        });
    }

    /**
     * Scope for active fields ordered by urutan
     */
    public function scopeActiveOrdered($query)
    {
        return $query->where('aktif', true)->orderBy('urutan')->orderBy('id');
    }
}
