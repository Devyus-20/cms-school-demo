<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function index()
    {
        $websiteSetting = Setting::latest()->first();
        $allPages = Page::where('aktif', true)->orderBy('urutan')->get();
        $groupedPages = [];
        $allPagesMapped = [];

        foreach ($allPages as $p) {
            $baseSlug = preg_replace('/-\d+$/', '', $p->slug);
            $g = $p->gambar;
            $gambarList = [];
            if ($g) {
                $decoded = json_decode($g, true);
                if (is_array($decoded)) {
                    $gambarList = array_values(array_filter($decoded));
                } else {
                    $gambarList = [$g];
                }
            }
            $gambarListAssets = array_map(function ($img) {
                return \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img);
            }, $gambarList);

            $pageData = [
                'id'          => $p->id_page,
                'judul'       => $p->judul ?: (Page::$profilSlugs[$baseSlug] ?? 'Profil'),
                'slug'        => $p->slug,
                'base_slug'   => $baseSlug,
                'konten'      => $p->konten ?? '',
                'gambar'      => count($gambarListAssets) > 0 ? $gambarListAssets[0] : null,
                'gambar_list' => $gambarListAssets,
                'aktif'       => (bool) $p->aktif,
            ];

            $allPagesMapped[] = $pageData;

            if (!isset($groupedPages[$baseSlug])) {
                $groupedPages[$baseSlug] = $pageData;
            }
        }

        return view('public.home', compact('websiteSetting', 'groupedPages', 'allPagesMapped'));
    }

    public function show($slug)
    {
        $post = Post::with(['category', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $post->increment('views');

        return view('public.post', compact('post'));
    }

    public function profile()
    {
        return redirect()->route('public.profil.page', ['slug' => 'sejarah']);
    }

    /** Halaman detail tunggal profil */
    public function showPageDetail($slug)
    {
        $page = Page::where('aktif', true)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('id_page', $slug);
            })
            ->firstOrFail();

        $baseSlug = preg_replace('/-\d+$/', '', $page->slug);
        $categoryLabel = Page::$profilSlugs[$baseSlug] ?? 'Profil Sekolah';

        return view('public.profil.show', compact('page', 'baseSlug', 'categoryLabel'));
    }

    /** Halaman profil berdasarkan slug (sejarah, visi-dan-misi, fasilitas, dll.) */
    public function profilPage($slug)
    {
        $baseSlug = preg_replace('/-\d+$/', '', $slug);

        $pages = Page::where('aktif', true)
            ->where(function ($q) use ($slug, $baseSlug) {
                $q->where('slug', $slug)
                  ->orWhere('slug', $baseSlug)
                  ->orWhere('slug', 'LIKE', $baseSlug . '-%');
            })
            ->orderBy('urutan')
            ->orderBy('id_page')
            ->get();

        if ($pages->isEmpty()) {
            $profilSlugs = Page::$profilSlugs;

            // Only auto-create if baseSlug or slug is a recognized standard profile slug
            if (!array_key_exists($baseSlug, $profilSlugs) && !array_key_exists($slug, $profilSlugs)) {
                abort(404);
            }

            $title = $profilSlugs[$baseSlug] ?? $profilSlugs[$slug] ?? Str::title(str_replace('-', ' ', $baseSlug));

            $defaultPage = Page::create([
                'judul'  => $title,
                'slug'   => $baseSlug,
                'konten' => "Selamat datang di halaman {$title} MA Al Ikhlas. Konten halaman ini dapat disesuaikan melalui Admin Panel.",
                'aktif'  => true,
            ]);

            $pages = collect([$defaultPage]);
        }

        $mainTitle = Page::$profilSlugs[$baseSlug] ?? $pages->first()->judul;

        return view('public.profil.page', compact('pages', 'mainTitle', 'baseSlug'));
    }

    /** Daftar galeri */
    public function galeriPage()
    {
        $galleries = Gallery::where('aktif', true)->latest('id_gallery')->get();

        return view('public.informasi.galeri', compact('galleries'));
    }

    /** Daftar konten berdasarkan tipe (artikel, berita, pengumuman) */
    public function informasiPage($tipe)
    {
        $tipeLabels = Post::$tipeLabels;
        if (!array_key_exists($tipe, $tipeLabels)) {
            abort(404);
        }

        $posts = Post::with('category')
            ->where('tipe', $tipe)
            ->where('status', 'published')
            ->latest('id_post')
            ->paginate(12);

        $label = $tipeLabels[$tipe];

        return view('public.informasi.list', compact('posts', 'tipe', 'label'));
    }

    /** Halaman PPDB */
    public function ppdbPage()
    {
        $setting = Setting::latest()->first();

        return view('public.ppdb', compact('setting'));
    }

    /** Download / Cetak Formulir Offline PPDB */
    public function downloadFormulirPpdb()
    {
        $setting = Setting::latest()->first();

        return view('public.ppdb_formulir_pdf', compact('setting'));
    }

    /** Submit formulir pendaftaran PPDB */
    public function storePpdb(Request $request)
    {
        $setting = Setting::latest()->first();
        if (!$setting || !$setting->ppdb_aktif) {
            return redirect()->back()->with('error', 'Pendaftaran PPDB saat ini sedang ditutup.');
        }

        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nisn'           => 'nullable|string|max:30',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
            'agama'          => 'required|string|max:50',
            'alamat'         => 'required|string',
            'sekolah_asal'   => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:30',
            'email'          => 'nullable|email|max:255',
            'jurusan'        => 'nullable|string|max:100',
            'berkas'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'nama_lengkap.required'   => 'Nama lengkap wajib diisi.',
            'jenis_kelamin.required'  => 'Pilih jenis kelamin.',
            'tempat_lahir.required'   => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required'  => 'Tanggal lahir wajib diisi.',
            'alamat.required'         => 'Alamat rumah wajib diisi.',
            'sekolah_asal.required'   => 'Sekolah asal (SMP/MTs) wajib diisi.',
            'nama_orang_tua.required' => 'Nama orang tua/wali wajib diisi.',
            'no_hp.required'          => 'Nomor HP/WhatsApp aktif wajib diisi.',
        ]);

        // Validate custom fields
        $customFields = \App\Models\PpdbCustomField::activeOrdered()->get();
        $dataTambahan = $request->input('data_tambahan', []);

        foreach ($customFields as $field) {
            if ($field->is_required) {
                $val = $dataTambahan[$field->field_key] ?? null;
                if (is_null($val) || (is_string($val) && trim($val) === '') || (is_array($val) && count($val) === 0)) {
                    return redirect()->back()->withInput()->with('error', "Field '{$field->label}' wajib diisi.");
                }
            }
        }

        $berkasPath = null;
        if ($request->hasFile('berkas')) {
            $berkasPath = $request->file('berkas')->store('ppdb_berkas', 'public');
        }

        $noPendaftaran = \App\Models\PpdbRegistration::generateNoPendaftaran();

        $registration = \App\Models\PpdbRegistration::create([
            'no_pendaftaran' => $noPendaftaran,
            'nama_lengkap'   => $request->nama_lengkap,
            'nisn'           => $request->nisn,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'agama'          => $request->agama ?? 'Islam',
            'alamat'         => $request->alamat,
            'sekolah_asal'   => $request->sekolah_asal,
            'nama_orang_tua' => $request->nama_orang_tua,
            'no_hp'          => $request->no_hp,
            'email'          => $request->email,
            'jurusan'        => $request->jurusan,
            'berkas'         => $berkasPath,
            'status'         => 'pending',
            'data_tambahan'  => $dataTambahan,
        ]);

        return redirect()->back()->with('ppdb_success', [
            'no_pendaftaran' => $registration->no_pendaftaran,
            'nama'           => $registration->nama_lengkap,
            'no_hp'          => $registration->no_hp,
        ]);
    }

    // ===================== API ENDPOINTS =====================

    public function website()
    {
        $setting = Setting::latest()->first();

        $heroBgs = $setting?->hero_bg_list ?? [];
        $heroBgsAssets = array_map(function ($img) {
            return \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img);
        }, $heroBgs);

        return response()->json([
            'name'             => $setting?->website_name ?? 'MA Al Ikhlas',
            'description'      => $setting?->website_description ?? 'CMS Sekolah Digital',
            'logo'             => $setting?->logo ? (\Illuminate\Support\Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo)) : asset('images/default-logo.png'),
            'logo_instansi'    => $setting?->logo_instansi ? (\Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi)) : null,
            'favicon'          => $setting?->favicon ? (\Illuminate\Support\Str::startsWith($setting->favicon, ['http://', 'https://']) ? $setting->favicon : asset($setting->favicon)) : asset('images/default-logo.png'),
            'alamat'           => $setting?->alamat ?? '',
            'email'            => $setting?->email ?? '',
            'telepon'          => $setting?->telepon ?? '',
            'facebook'         => $setting?->facebook ?? '',
            'instagram'        => $setting?->instagram ?? '',
            'youtube'          => $setting?->youtube ?? '',
            'linkedin'         => $setting?->linkedin ?? '',
            'footer'           => $setting?->footer ?? '',
            'hero_bg'          => $setting?->hero_bg ? (\Illuminate\Support\Str::startsWith($setting->hero_bg, ['http://', 'https://']) ? $setting->hero_bg : asset($setting->hero_bg)) : null,
            'hero_bgs'         => $heroBgsAssets,
            'hero_tagline'     => $setting?->hero_tagline ?? '',
            'hero_title'       => $setting?->hero_title ?? '',
            'hero_subtitle'    => $setting?->hero_subtitle ?? '',
            'hero_btn1_text'   => $setting?->hero_btn1_text ?? '',
            'hero_btn1_link'   => $setting?->hero_btn1_link ?? '',
            'hero_btn2_text'   => $setting?->hero_btn2_text ?? '',
            'hero_btn2_link'   => $setting?->hero_btn2_link ?? '',
            'hero_btn3_text'   => $setting?->hero_btn3_text ?? '',
            'hero_btn3_link'   => $setting?->hero_btn3_link ?? '',
            'google_maps'      => $setting?->google_maps ?? '',
            'ppdb_aktif'       => (bool) ($setting?->ppdb_aktif ?? false),
            'ppdb_tahun'       => $setting?->ppdb_tahun ?? '',
            'ppdb_keterangan'  => $setting?->ppdb_keterangan ?? '',
            'ppdb_link_daftar' => $setting?->ppdb_link_daftar ?? '',
            'info_pendaftaran_pembelajaran_judul'    => $setting?->info_pendaftaran_pembelajaran_judul ?? '',
            'info_pendaftaran_pembelajaran_subjudul' => $setting?->info_pendaftaran_pembelajaran_subjudul ?? '',
            'info_pendaftaran_pembelajaran_konten'   => $setting?->info_pendaftaran_pembelajaran_konten ?? '',
            'info_faq_list'                           => $setting?->info_faq_list ?? [],
        ]);
    }

    public function posts()
    {
        $posts = Post::with('category')
            ->where('status', 'published')
            ->latest('id_post')
            ->take(3)
            ->get()
            ->map(function ($post) {
                $thumb = $post->thumbnail;
                if ($thumb) {
                    $decoded = json_decode($thumb, true);
                    if (is_array($decoded) && count($decoded) > 0) {
                        $thumb = $decoded[0];
                    }
                }
                return [
                    'id'           => $post->id_post,
                    'judul'        => $post->judul,
                    'slug'         => $post->slug,
                    'tipe'         => $post->tipe,
                    'thumbnail'    => $thumb ? asset($thumb) : null,
                    'isi'          => Str::limit(strip_tags($post->isi ?? ''), 180),
                    'status'       => $post->status,
                    'category'     => $post->category?->nama ?? null,
                    'published_at' => $post->published_at?->toIso8601String(),
                ];
            });

        return response()->json($posts);
    }

    public function post($slug)
    {
        $post = Post::with(['category', 'tags'])->where('slug', $slug)->firstOrFail();
        $thumb = $post->thumbnail;
        if ($thumb) {
            $decoded = json_decode($thumb, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $thumb = $decoded[0];
            }
        }

        return response()->json([
            'id'           => $post->id_post,
            'judul'        => $post->judul,
            'slug'         => $post->slug,
            'tipe'         => $post->tipe,
            'thumbnail'    => $thumb ? asset($thumb) : null,
            'isi'          => $post->isi,
            'status'       => $post->status,
            'category'     => $post->category?->nama ?? null,
            'published_at' => $post->published_at?->toIso8601String(),
        ]);
    }

    public function pages()
    {
        $allPages = Page::where('aktif', true)->orderBy('urutan')->get();
        $grouped = [];
        $allPagesMapped = [];

        foreach ($allPages as $p) {
            $baseSlug = preg_replace('/-\d+$/', '', $p->slug);
            $g = $p->gambar;
            $gambarList = [];
            if ($g) {
                $decoded = json_decode($g, true);
                if (is_array($decoded)) {
                    $gambarList = array_values(array_filter($decoded));
                } else {
                    $gambarList = [$g];
                }
            }
            $gambarListAssets = array_map(function ($img) {
                return \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img);
            }, $gambarList);

            $pageData = [
                'id'          => $p->id_page,
                'judul'       => $p->judul ?: (Page::$profilSlugs[$baseSlug] ?? 'Profil'),
                'slug'        => $p->slug,
                'base_slug'   => $baseSlug,
                'konten'      => $p->konten ?? '',
                'gambar'      => count($gambarListAssets) > 0 ? $gambarListAssets[0] : null,
                'gambar_list' => $gambarListAssets,
                'aktif'       => (bool) $p->aktif,
            ];

            $allPagesMapped[] = $pageData;

            if (!isset($grouped[$baseSlug])) {
                $grouped[$baseSlug] = $pageData;
            }
        }

        return response()->json([
            'grouped' => array_values($grouped),
            'all'     => $allPagesMapped,
        ]);
    }

    public function galleries()
    {
        $galleries = Gallery::where('aktif', true)->latest('id_gallery')->get()->map(function ($g) {
            $img = $g->gambar;
            if ($img) {
                $decoded = json_decode($img, true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $img = $decoded[0];
                }
            }
            return [
                'id'        => $g->id_gallery,
                'judul'     => $g->judul,
                'deskripsi' => $g->deskripsi,
                'gambar'    => $img ? asset($img) : null,
                'tanggal'   => $g->tanggal?->toDateString(),
            ];
        });

        return response()->json($galleries);
    }

    public function ppdb()
    {
        $setting = Setting::latest()->first();

        return response()->json([
            'ppdb_aktif'       => (bool) ($setting?->ppdb_aktif ?? false),
            'ppdb_tahun'       => $setting?->ppdb_tahun ?? '',
            'ppdb_keterangan'  => $setting?->ppdb_keterangan ?? '',
            'ppdb_link_daftar' => $setting?->ppdb_link_daftar ?? '',
        ]);
    }
}