<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('kategori')->orderBy('nama_menu')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu'    => 'required|string|max:150',
            'kategori'     => 'required|in:reguler,bundling',
            'deskripsi'    => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'harga_normal' => 'required|integer|min:0',
            'diskon'       => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['nama_menu', 'kategori', 'deskripsi', 'harga_normal', 'diskon']);
        $data['diskon'] = $data['diskon'] ?? 0;
        $data['harga_c1'] = $data['harga_normal'] - $data['diskon'];
        $data['is_bundle'] = $request->kategori === 'bundling';

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu', 'public');
            $data['gambar'] = $path;
        }

        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'nama_menu'    => 'required|string|max:150',
            'kategori'     => 'required|in:reguler,bundling',
            'deskripsi'    => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'harga_normal' => 'required|integer|min:0',
            'diskon'       => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['nama_menu', 'kategori', 'deskripsi', 'harga_normal', 'diskon']);
        $data['diskon'] = $data['diskon'] ?? 0;
        $data['harga_c1'] = $data['harga_normal'] - $data['diskon'];
        $data['is_bundle'] = $request->kategori === 'bundling';

        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $path = $request->file('gambar')->store('menu', 'public');
            $data['gambar'] = $path;
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_active' => false]);

        return back()->with('success', 'Menu berhasil dinonaktifkan.');
    }

    public function toggleActive($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_active' => !$menu->is_active]);

        return back()->with('success', 'Status menu berhasil diubah.');
    }
}
