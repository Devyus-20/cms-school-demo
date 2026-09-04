<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('urutan')->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
            'status' => ['nullable', 'boolean'],
        ]);

        Menu::create($data + ['status' => $request->boolean('status')]);

        return redirect()->route('admin.menus')->with('success', 'Menu berhasil ditambahkan.');
    }
}
