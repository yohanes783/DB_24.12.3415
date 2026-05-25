<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Pastikan baris ini ada untuk membuat slug

class CategoryController extends Controller
{
    // 1. Menampilkan halaman utama + Fitur Pencarian (Soal 3)
    public function index(Request $request)
    {
        $search = $request->get('search');

        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })->latest()->get();

        return view('admin.category', compact('categories', 'search'));
    }

    // 2. Fungsi Simpan Kategori Baru + Otomatis Slug
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori tidak boleh kosong!',
            'name.unique' => 'Nama kategori ini sudah terdaftar!',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    // 3. Fungsi Perbarui Kategori (UNTUK MENYELANDANGKAN ERROR UPDATE)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.required' => 'Nama kategori tidak boleh kosong!',
            'name.unique' => 'Nama kategori ini sudah digunakan!',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Ikut perbarui slug jika nama diganti
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. Fungsi Hapus Kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}
