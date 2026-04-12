@extends('layout.backend.app')

@section('content')
<div class="content">

    <div class="table-card">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; gap: 20px;">
            <div class="table-header-left">
                <h3 style="font-size: 16px; font-weight: 700; color: #1e1b3a;">Daftar Buku</h3>
            </div>

            <div class="table-header-right" style="display: flex; align-items: center; gap: 15px;">
                <form method="GET" action="{{ route('buku.index') }}" style="position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari judul atau penulis..." 
                        style="padding: 8px 15px 8px 35px; border: 1px solid #e2e8f0; border-radius: 8px; width: 220px; font-family: inherit; font-size: 12px; transition: 0.3s;"
                        onfocus="this.style.borderColor='#7c3aff'; this.style.boxShadow='0 0 0 3px rgba(124, 58, 255, 0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                </form>

                <a href="{{ route('buku.create') }}" class="btn-add" style="background: #7c3aff; color: #fff; padding: 9px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 12px; transition: 0.3s;">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Buku</span>
                </a>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; table-layout: fixed; min-width: 800px;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="width: 50px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">No</th>
                        <th style="width: 70px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Cover</th>
                        <th style="width: 250px; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Informasi Buku</th>
                        <th style="width: 150px; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Penulis</th>
                        <th style="width: 80px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Stok</th>
                        <th style="width: 100px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Status</th>
                        <th style="width: 120px; text-align: center; padding: 12px; color: #64748b; font-size: 11px; text-transform: uppercase;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bukus as $index => $buku)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.backgroundColor='#fbf9ff'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="text-align: center; color: #94a3b8; font-weight: 500;">{{ $bukus->firstItem() + $index }}</td>
                            
                            <td style="text-align: center; padding: 10px 0;">
                                @if($buku->cover)
                                    {{-- Perbaikan: Menggunakan asset() karena path di DB adalah 'cover-img/...' --}}
                                    <img src="{{ asset($buku->cover) }}" 
                                         style="width: 45px; height: 60px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.15);" 
                                         onerror="this.onerror=null;this.src='https://placehold.co/45x60?text=No+Cover';">
                                @else
                                    <div style="width: 40px; height: 55px; background: #f1f5f9; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; border: 1px dashed #cbd5e1; margin: 0 auto;">
                                        <i class="fas fa-book" style="font-size: 14px;"></i>
                                    </div>
                                @endif
                            </td>

                            <td style="padding: 12px;">
                                <div style="font-weight: 600; color: #1e1b3a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $buku->judul }}">
                                    {{ $buku->judul }}
                                </div>
                                <div style="font-size: 11px; color: #94a3b8; margin-top: 2px;">Tahun: {{ $buku->tahun_terbit }}</div>
                            </td>

                            <td style="padding: 12px; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $buku->penulis }}
                            </td>

                            <td style="text-align: center; font-weight: 700; color: #1e1b3a;">{{ $buku->stok }}</td>

                            <td style="text-align: center;">
                                @if($buku->stok > 0)
                                    <span style="background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block;">Tersedia</span>
                                @else
                                    <span style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block;">Habis</span>
                                @endif
                            </td>

                            <td style="text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('buku.show', $buku->id) }}" style="width: 28px; height: 28px; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.2s;" title="Detail" onmouseover="this.style.background='#7c3aff'; this.style.color='#fff'" onmouseout="this.style.background='#f8fafc'; this.style.color='#64748b'">
                                        <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('buku.edit', $buku->id) }}" style="width: 28px; height: 28px; background: #eef2ff; color: #4338ca; border-radius: 6px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.2s;" title="Edit" onmouseover="this.style.background='#4338ca'; this.style.color='#fff'" onmouseout="this.style.background='#eef2ff'; this.style.color='#4338ca'">
                                        <i class="fas fa-edit" style="font-size: 10px;"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin hapus data ini?')" style="width: 28px; height: 28px; background: #fff1f2; color: #e11d48; border-radius: 6px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s;" title="Hapus" onmouseover="this.style.background='#e11d48'; this.style.color='#fff'" onmouseout="this.style.background='#fff1f2'; this.style.color='#e11d48'">
                                            <i class="fas fa-trash" style="font-size: 10px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 50px 0; color: #94a3b8;">
                                <img src="https://illustrations.popsy.co/gray/empty-folder.svg" style="width: 120px; margin-bottom: 15px; opacity: 0.5;">
                                <p style="font-size: 14px;">Belum ada data buku yang tersimpan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-row" style="display: flex; align-items: center; justify-content: space-between; margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
            <div style="font-size: 12px; color: #64748b;">
                Showing <span style="font-weight: 600; color: #1e1b3a;">{{ $bukus->firstItem() ?? 0 }}</span> to <span style="font-weight: 600; color: #1e1b3a;">{{ $bukus->lastItem() ?? 0 }}</span> of {{ $bukus->total() }} entries
            </div>

            <div class="custom-pagination">
                @if ($bukus->hasPages())
                    <ul style="display: flex; list-style: none; gap: 6px; padding: 0; margin: 0; align-items: center;">
                        @if ($bukus->onFirstPage())
                            <li class="page-item-disabled"><i class="fas fa-chevron-left"></i></li>
                        @else
                            <li><a href="{{ $bukus->previousPageUrl() }}" class="page-link-custom"><i class="fas fa-chevron-left"></i></a></li>
                        @endif

                        @foreach ($bukus->getUrlRange(max(1, $bukus->currentPage() - 1), min($bukus->lastPage(), $bukus->currentPage() + 1)) as $page => $url)
                            <li>
                                <a href="{{ $url }}" class="page-link-custom {{ $page == $bukus->currentPage() ? 'active' : '' }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endforeach

                        @if ($bukus->hasMorePages())
                            <li><a href="{{ $bukus->nextPageUrl() }}" class="page-link-custom"><i class="fas fa-chevron-right"></i></a></li>
                        @else
                            <li class="page-item-disabled"><i class="fas fa-chevron-right"></i></li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Style tetap sama seperti sebelumnya */
    .page-link-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        text-decoration: none;
        color: #64748b;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        transition: 0.3s;
    }

    .page-link-custom:hover {
        background: #f1f5f9;
        color: #7c3aff;
        border-color: #7c3aff;
    }

    .page-link-custom.active {
        background: #7c3aff;
        color: #fff;
        border-color: #7c3aff;
        box-shadow: 0 4px 10px rgba(124, 58, 255, 0.2);
    }

    .page-item-disabled {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        color: #cbd5e1;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 11px;
        cursor: not-allowed;
    }

    .table-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection