<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Category; // Wajib import Model Category!
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan eager loading 'with' agar query lebih cepat saat manggil nama kategori
        $query = Buku::with('category'); 

        // SEARCH
        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('penulis', 'like', '%' . $request->search . '%')
                ->orWhere('tahun_terbit', 'like', '%' . $request->search . '%');
        }

        $bukus = $query->latest()->paginate(5)->withQueryString();

        return view('pages.backend.buku.index', compact('bukus'));
    }

    public function create()
    {
        // Ambil semua kategori untuk dikirim ke form
        $categories = Category::orderBy('name', 'asc')->get();
        return view('pages.backend.buku.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required',
            'stok' => 'required|integer',
            'category_id' => 'required|exists:categories,id', // Tambahkan validasi kategori
            'cover' => 'nullable|image'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('buku', 'public');
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambah');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        // Tambahkan kategori juga di halaman edit
        $categories = Category::orderBy('name', 'asc')->get();
        return view('pages.backend.buku.edit', compact('buku', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required',
            'stok' => 'required|integer',
            'category_id' => 'required|exists:categories,id', // Tambahkan validasi kategori
            'cover' => 'nullable|image'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            // hapus lama
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }

            $data['cover'] = $request->file('cover')->store('buku', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }
}