<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Category;
use App\Models\Peminjaman; 
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    /**
     * Tampilkan daftar buku dengan fitur search dan pagination.
     */
    public function index(Request $request)
    {
        $query = Buku::with('category'); 

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%')
                  ->orWhere('tahun_terbit', 'like', '%' . $request->search . '%');
            });
        }

        $bukus = $query->latest()->paginate(5)->withQueryString();

        return view('pages.backend.buku.index', compact('bukus'));
    }

    /**
     * Tampilkan detail buku dan riwayat peminjaman.
     */
    public function show($id)
    {
        $buku = Buku::with(['category'])->withCount('peminjaman')->findOrFail($id);
        
        $riwayat = Peminjaman::with('user')
                    ->where('buku_id', $id)
                    ->latest()
                    ->take(5)
                    ->get();

        return view('pages.backend.buku.show', compact('buku', 'riwayat'));
    }

    /**
     * Form tambah buku.
     */
    public function create()
    {
        // Mengambil kategori diurutkan berdasarkan nama
        $categories = Category::orderBy('name', 'asc')->get();
        return view('pages.backend.buku.create', compact('categories'));
    }

    /**
     * Simpan data buku baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:255',
            'tahun_terbit'=> 'required',
            'stok'        => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'cover'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
        ]);

        if ($request->hasFile('cover')) {
            // Simpan file ke folder storage/app/public/cover-img
            $path = $request->file('cover')->store('cover-img', 'public');
            // Simpan path yang bisa dibaca asset() ke database
            $validatedData['cover'] = 'storage/' . $path;
        }

        Buku::create($validatedData);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambah');
    }

    /**
     * Form edit buku.
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('pages.backend.buku.edit', compact('buku', 'categories'));
    }

    /**
     * Update data buku.
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validatedData = $request->validate([
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:255',
            'tahun_terbit'=> 'required',
            'stok'        => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'cover'       => 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048'
        ]);

        if ($request->hasFile('cover-img')) {
            // Hapus cover lama jika path-nya ada di database
            if ($buku->cover) {
                // Kita bersihkan string 'storage/' agar Storage::disk bisa nemu filenya
                $oldPath = str_replace('storage/', '', $buku->cover);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Simpan cover baru
            $path = $request->file('cover')->store('cover-img', 'public');
            $validatedData['cover'] = 'storage/' . $path;
        }

        $buku->update($validatedData);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diupdate');
    }

    /**
     * Hapus data buku beserta covernya.
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover) {
            $path = str_replace('storage/', '', $buku->cover);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }
}