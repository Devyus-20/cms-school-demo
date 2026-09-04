<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $profilSlugs = Page::$profilSlugs;

        // Hitung jumlah konten per kategori profil
        $allPages = Page::all();
        $categoryCounts = [];
        foreach ($profilSlugs as $slug => $label) {
            $categoryCounts[$slug] = $allPages->filter(function ($p) use ($slug) {
                $base = preg_replace('/-\d+$/', '', $p->slug);
                return $base === $slug || $p->slug === $slug;
            })->count();
        }

        return view('admin.pages.index', compact('profilSlugs', 'categoryCounts'));
    }

    public function section($category)
    {
        $profilSlugs = Page::$profilSlugs;
        if (!array_key_exists($category, $profilSlugs)) {
            abort(404);
        }

        $categoryTitle = $profilSlugs[$category];

        $pages = Page::where(function ($q) use ($category) {
            $q->where('slug', $category)
              ->orWhere('slug', 'LIKE', $category . '-%');
        })->orderBy('urutan')->orderBy('id_page')->get();

        return view('admin.pages.section', compact('category', 'categoryTitle', 'pages', 'profilSlugs'));
    }

    public function create(Request $request)
    {
        $profilSlugs = Page::$profilSlugs;
        $presetCategory = $request->query('category');
        $presetTitle = null;
        $presetSlug = null;

        if ($presetCategory && array_key_exists($presetCategory, $profilSlugs)) {
            $presetTitle = $profilSlugs[$presetCategory];
            $presetSlug = $presetCategory;
        }

        return view('admin.pages.create', compact('profilSlugs', 'presetCategory', 'presetTitle', 'presetSlug'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255'],
            'konten'       => ['nullable', 'string'],
            'gambar'       => ['nullable'],
            'gambar_url'   => ['nullable', 'string', 'max:255'],
            'urutan'       => ['nullable', 'integer'],
            'aktif'        => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,bmp', 'max:51200'],
            ], [
                'gambar.*.image' => 'File yang diunggah harus berupa gambar.',
                'gambar.*.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, svg, atau webp.',
                'gambar.*.max'   => 'Ukuran gambar yang diunggah melebihi batas maksimal (50 MB per file).',
            ]);
        }

        $data['aktif'] = $request->has('aktif') ? 1 : 0;
        
        // Auto Uniquify Slug jika sudah ada di database
        $data['slug'] = $this->makeUniqueSlug(Page::class, $request->input('slug') ?: $data['judul']);

        // Support upload multiple gambar dari komputer lokal
        $uploadedImages = [];
        if ($request->hasFile('gambar')) {
            $files = is_array($request->file('gambar')) ? $request->file('gambar') : [$request->file('gambar')];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('pages', 'public');
                    $uploadedImages[] = '/storage/' . $path;
                }
            }
        }

        if (count($uploadedImages) > 0) {
            $data['gambar'] = count($uploadedImages) === 1 ? $uploadedImages[0] : json_encode($uploadedImages);
        } elseif ($request->filled('gambar_url')) {
            $data['gambar'] = $request->input('gambar_url');
        } else {
            unset($data['gambar']);
        }
        unset($data['gambar_url']);

        $newPage = Page::create($data);

        ActivityLog::record('create', "Membuat halaman profil baru: {$newPage->judul}", $newPage);

        return redirect()->route('admin.pages')->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function edit(Page $page)
    {
        $profilSlugs = Page::$profilSlugs;

        return view('admin.pages.edit', compact('page', 'profilSlugs'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255'],
            'konten'       => ['nullable', 'string'],
            'gambar'       => ['nullable'],
            'gambar_url'   => ['nullable', 'string', 'max:255'],
            'urutan'       => ['nullable', 'integer'],
            'aktif'        => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,bmp', 'max:51200'],
            ], [
                'gambar.*.image' => 'File yang diunggah harus berupa gambar.',
                'gambar.*.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, svg, atau webp.',
                'gambar.*.max'   => 'Ukuran gambar yang diunggah melebihi batas maksimal (50 MB per file).',
            ]);
        }

        $data['aktif'] = $request->has('aktif') ? 1 : 0;

        // Auto Uniquify Slug (abaikan ID halaman ini)
        $data['slug'] = $this->makeUniqueSlug(Page::class, $request->input('slug') ?: $data['judul'], $page->id_page, 'id_page');

        // Handling upload gambar baru & penggabungan foto lama
        $existingImages = [];
        if ($request->has('keep_existing_gambar') && $page->gambar) {
            $decoded = json_decode($page->gambar, true);
            $existingImages = is_array($decoded) ? $decoded : [$page->gambar];
        }

        $uploadedImages = [];
        if ($request->hasFile('gambar')) {
            $files = is_array($request->file('gambar')) ? $request->file('gambar') : [$request->file('gambar')];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('pages', 'public');
                    $uploadedImages[] = '/storage/' . $path;
                }
            }
        }

        $allImages = array_merge($existingImages, $uploadedImages);

        if (count($allImages) > 0) {
            $data['gambar'] = count($allImages) === 1 ? $allImages[0] : json_encode(array_values($allImages));
        } elseif ($request->filled('gambar_url')) {
            $data['gambar'] = $request->input('gambar_url');
        } else {
            if ($request->has('keep_existing_gambar')) {
                unset($data['gambar']);
            } else {
                $data['gambar'] = null;
            }
        }
        unset($data['gambar_url']);

        $page->update($data);

        ActivityLog::record('update', "Memperbarui halaman profil: {$page->judul}", $page);

        return redirect()->route('admin.pages')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page)
    {
        $pageTitle = $page->judul;
        $page->delete();

        ActivityLog::record('delete', "Menghapus halaman profil: {$pageTitle}");

        return redirect()->route('admin.pages')->with('success', 'Halaman berhasil dihapus.');
    }

    /** Helper method untuk buat slug unik otomatis */
    private function makeUniqueSlug($modelClass, $title, $currentId = null, $primaryKey = 'id')
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'halaman';
        }

        $originalSlug = $slug;
        $count = 1;

        while ($modelClass::where('slug', $slug)
            ->when($currentId, fn($q) => $q->where($primaryKey, '!=', $currentId))
            ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
