<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest('id_gallery')->get();

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'deskripsi'  => ['nullable', 'string'],
            'gambar'     => ['nullable'],
            'gambar_url' => ['nullable', 'string', 'max:255'],
            'tanggal'    => ['nullable', 'date'],
            'aktif'      => ['nullable', 'boolean'],
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

        $aktif = $request->has('aktif') ? 1 : 0;
        $tanggal = $data['tanggal'] ?? date('Y-m-d');
        $judulBase = $data['judul'];
        $deskripsi = $data['deskripsi'] ?? null;

        // Support multiple file upload pada Galeri Foto
        if ($request->hasFile('gambar')) {
            $files = is_array($request->file('gambar')) ? $request->file('gambar') : [$request->file('gambar')];
            $count = 1;
            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('galleries', 'public');
                    $judul = count($files) > 1 ? "{$judulBase} ({$count})" : $judulBase;

                    Gallery::create([
                        'judul'     => $judul,
                        'deskripsi' => $deskripsi,
                        'gambar'    => '/storage/' . $path,
                        'tanggal'   => $tanggal,
                        'aktif'     => $aktif,
                    ]);
                    $count++;
                }
            }
            return redirect()->route('admin.galleries')->with('success', count($files) . ' Foto galeri berhasil ditambahkan.');
        } elseif ($request->filled('gambar_url')) {
            Gallery::create([
                'judul'     => $judulBase,
                'deskripsi' => $deskripsi,
                'gambar'    => $request->input('gambar_url'),
                'tanggal'   => $tanggal,
                'aktif'     => $aktif,
            ]);
            return redirect()->route('admin.galleries')->with('success', 'Foto galeri berhasil ditambahkan.');
        }

        return back()->withErrors(['gambar' => 'Pilih setidaknya 1 file foto dari komputer atau masukkan URL gambar.'])->withInput();
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'deskripsi'  => ['nullable', 'string'],
            'gambar'     => ['nullable'],
            'gambar_url' => ['nullable', 'string', 'max:255'],
            'tanggal'    => ['nullable', 'date'],
            'aktif'      => ['nullable', 'boolean'],
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

        if ($request->hasFile('gambar')) {
            $files = is_array($request->file('gambar')) ? $request->file('gambar') : [$request->file('gambar')];
            $path = $files[0]->store('galleries', 'public');
            $data['gambar'] = '/storage/' . $path;
        } elseif ($request->filled('gambar_url')) {
            $data['gambar'] = $request->input('gambar_url');
        } else {
            unset($data['gambar']);
        }
        unset($data['gambar_url']);

        $gallery->update($data);

        return redirect()->route('admin.galleries')->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return redirect()->route('admin.galleries')->with('success', 'Foto galeri berhasil dihapus.');
    }
}
