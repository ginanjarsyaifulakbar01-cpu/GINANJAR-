<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category; // Pastikan import model Category
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        $categories = Category::latest()->get();
        return view('pages.backend.category.index', compact('categories'));
    }

    /**
     * Method ini biasanya mengembalikan view form tambah data.
     * Tapi karena kita pakai MODAL di halaman index, kita bisa biarkan kosong 
     * atau hapus jika tidak digunakan.
     */
    public function create()
    {
        return view('pages.category.create');
    }

    /**
     * Menyimpan data kategori baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name',
            'icon' => 'nullable'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? 'fas fa-folder',
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu kategori (Optional).
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return view('pages.category.show', compact('category'));
    }

    /**
     * Mengambil data untuk form edit.
     * Jika Anda ingin pakai AJAX untuk edit, method ini bisa mengembalikan JSON.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('pages.category.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
        ]);

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}