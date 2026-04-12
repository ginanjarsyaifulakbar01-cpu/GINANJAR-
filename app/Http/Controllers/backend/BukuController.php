<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Category;
use App\Models\Peminjaman; // Tambahkan import Model Peminjaman
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('category'); 

        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('penulis', 'like', '%' . $request->search . '%')
                ->orWhere('tahun_terbit', 'like', '%' . $request->search . '%');
        }

        $bukus = $query->latest()->paginate(5)->withQueryString();

        return view('pages.backend.buku.index', compact('bukus'));
    }

    /**
     * METHOD DETAIL BUKU (BE)
     */
    public function show($id)
    {
        // Ambil buku dengan kategori dan hitung jumlah peminjaman (biar selaras sama dashboard)
        $buku = Buku::with(['category'])->withCount('peminjaman')->findOrFail($id);
        
        // Ambil 5 riwayat peminjaman terakhir khusus buku ini
        $riwayat = Peminjaman::with('user')
                    ->where('buku_id', $id)
                    ->latest()
                    ->take(5)
                    ->get();

        return view('pages.backend.buku.show', compact('buku', 'riwayat'));
    }

    public function create()
    {
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
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            // Kita simpan ke folder 'cover-img' agar sesuai dengan data awalmu
            $data['cover'] = $request->file('cover')->store('cover-img', 'public');
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambah');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
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
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image'
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('cover-img', 'public');
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