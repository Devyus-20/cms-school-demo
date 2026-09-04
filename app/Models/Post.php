<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $primaryKey = 'id_post';

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'category_id',
        'author_id',
        'judul',
        'slug',
        'tipe',
        'isi',
        'thumbnail',
        'status',
        'views',
        'published_at',
    ];

    // Tipe konten yang tersedia
    public const TIPE_ARTIKEL      = 'artikel';
    public const TIPE_BERITA       = 'berita';
    public const TIPE_PENGUMUMAN   = 'pengumuman';

    public static array $tipeLabels = [
        'artikel'    => 'Artikel',
        'berita'     => 'Berita',
        'pengumuman' => 'Pengumuman',
    ];

    public function scopeArtikel($query)
    {
        return $query->where('tipe', self::TIPE_ARTIKEL);
    }

    public function scopeBerita($query)
    {
        return $query->where('tipe', self::TIPE_BERITA);
    }

    public function scopePengumuman($query)
    {
        return $query->where('tipe', self::TIPE_PENGUMUMAN);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id_user');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }
}