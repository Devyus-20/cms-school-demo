<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $tipe  = $request->query('tipe', 'berita');
        $tipes = Post::$tipeLabels;

        if (!array_key_exists($tipe, $tipes)) {
            $tipe = 'berita';
        }

        $posts = Post::with('category')
            ->where('tipe', $tipe)
            ->latest('id_post')
            ->get();

        return view('admin.posts.index', compact('posts', 'tipe', 'tipes'));
    }

    public function create(Request $request)
    {
        $tipe       = $request->query('tipe', 'berita');
        $tipes      = Post::$tipeLabels;
        $categories = Category::all();
        $tags       = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags', 'tipe', 'tipes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'         => ['required', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', 'max:255'],
            'tipe'          => ['required', 'in:artikel,berita,pengumuman'],
            'category_id'   => ['nullable', 'exists:categories,id_category'],
            'isi'           => ['nullable', 'string'],
            'thumbnail'     => ['nullable'],
            'thumbnail_url' => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'in:draft,published'],
            'tags'          => ['nullable', 'array'],
            'tags.*'        => ['exists:tags,id_tag'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $request->validate([
                'thumbnail.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,bmp', 'max:51200'],
            ], [
                'thumbnail.*.image' => 'File yang diunggah harus berupa gambar.',
                'thumbnail.*.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, svg, atau webp.',
                'thumbnail.*.max'   => 'Ukuran gambar yang diunggah melebihi batas maksimal (50 MB per file).',
            ]);
        }

        $data['author_id'] = auth()->id();
        $data['status']    = $data['status'] ?? 'draft';

        // Auto Uniquify Slug jika judul/slug sama
        $data['slug'] = $this->makeUniqueSlug(Post::class, $request->input('slug') ?: $data['judul']);

        // Support multiple file upload
        $uploadedThumbnails = [];
        if ($request->hasFile('thumbnail')) {
            $files = is_array($request->file('thumbnail')) ? $request->file('thumbnail') : [$request->file('thumbnail')];
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('posts', 'public');
                    $uploadedThumbnails[] = '/storage/' . $path;
                }
            }
        }

        if (count($uploadedThumbnails) > 0) {
            $data['thumbnail'] = count($uploadedThumbnails) === 1 ? $uploadedThumbnails[0] : json_encode($uploadedThumbnails);
        } elseif ($request->filled('thumbnail_url')) {
            $data['thumbnail'] = $request->input('thumbnail_url');
        } else {
            unset($data['thumbnail']);
        }
        unset($data['thumbnail_url']);

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $post = Post::create($data);

        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        ActivityLog::record('create', "Membuat " . ucfirst($data['tipe']) . " baru: {$post->judul}", $post);

        return redirect()
            ->route('admin.posts', ['tipe' => $data['tipe']])
            ->with('success', ucfirst($data['tipe']) . ' berhasil dibuat.');
    }

    public function edit(Post $post)
    {
        $tipes      = Post::$tipeLabels;
        $categories = Category::all();
        $tags       = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'tipes'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'judul'         => ['required', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', 'max:255'],
            'tipe'          => ['required', 'in:artikel,berita,pengumuman'],
            'category_id'   => ['nullable', 'exists:categories,id_category'],
            'isi'           => ['nullable', 'string'],
            'thumbnail'     => ['nullable'],
            'thumbnail_url' => ['nullable', 'string', 'max:255'],
            'status'        => ['nullable', 'in:draft,published'],
            'tags'          => ['nullable', 'array'],
            'tags.*'        => ['exists:tags,id_tag'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $request->validate([
                'thumbnail.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp,bmp', 'max:51200'],
            ], [
                'thumbnail.*.image' => 'File yang diunggah harus berupa gambar.',
                'thumbnail.*.mimes' => 'Format gambar harus: jpeg, png, jpg, gif, svg, atau webp.',
                'thumbnail.*.max'   => 'Ukuran gambar yang diunggah melebihi batas maksimal (50 MB per file).',
            ]);
        }

        // Auto Uniquify Slug (abaikan ID post ini)
        $data['slug'] = $this->makeUniqueSlug(Post::class, $request->input('slug') ?: $data['judul'], $post->id_post, 'id_post');

        // Support multiple file upload
        $uploadedThumbnails = [];
        if ($request->hasFile('thumbnail')) {
            $files = is_array($request->file('thumbnail')) ? $request->file('thumbnail') : [$request->file('thumbnail')];
            foreach ($files as $file) {
                if ($file->isValid()) {
                    $path = $file->store('posts', 'public');
                    $uploadedThumbnails[] = '/storage/' . $path;
                }
            }
        }

        if (count($uploadedThumbnails) > 0) {
            $data['thumbnail'] = count($uploadedThumbnails) === 1 ? $uploadedThumbnails[0] : json_encode($uploadedThumbnails);
        } elseif ($request->filled('thumbnail_url')) {
            $data['thumbnail'] = $request->input('thumbnail_url');
        } else {
            unset($data['thumbnail']);
        }
        unset($data['thumbnail_url']);

        if ($data['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        }

        $post->update($data);
        $post->tags()->sync($data['tags'] ?? []);

        ActivityLog::record('update', "Memperbarui " . ucfirst($data['tipe']) . ": {$post->judul}", $post);

        return redirect()
            ->route('admin.posts', ['tipe' => $data['tipe']])
            ->with('success', ucfirst($data['tipe']) . ' berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        $tipe = $post->tipe;
        $judul = $post->judul;
        $post->tags()->detach();
        $post->delete();

        ActivityLog::record('delete', "Menghapus " . ucfirst($tipe) . ": {$judul}");

        return redirect()
            ->route('admin.posts', ['tipe' => $tipe])
            ->with('success', 'Konten berhasil dihapus.');
    }

    private function makeUniqueSlug($modelClass, $title, $currentId = null, $primaryKey = 'id')
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = 'post';
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
