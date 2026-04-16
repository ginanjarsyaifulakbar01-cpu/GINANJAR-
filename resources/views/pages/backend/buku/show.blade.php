@extends('layout.backend.app')

@section('content')
    <div class="content">
        <div class="table-card" style="padding: 30px; border-radius: 20px;">
            <div style="display: flex; gap: 40px; flex-wrap: wrap;">

                {{-- Kiri: Visual Buku --}}
                <div style="flex: 0 0 250px;">
                    <img src="{{ asset($buku->cover) }}"
                        style="width: 100%; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                    <div
                        style="margin-top: 20px; text-align: center; background: #f0eeff; padding: 15px; border-radius: 12px;">
                        <span style="display: block; font-size: 12px; color: #7c3aff; font-weight: 700;">TOTAL
                            DIPINJAM</span>
                        <h3 style="margin: 5px 0 0; color: #1e1b3a; font-weight: 800;">{{ $buku->peminjaman_count }} Kali
                        </h3>
                    </div>
                </div>

                {{-- Kanan: Detail Informasi --}}
                <div style="flex: 1;">
                    <div style="border-bottom: 2px solid #f8fafc; padding-bottom: 20px; margin-bottom: 20px;">
                        <h2 style="font-weight: 800; color: #1e1b3a; margin-bottom: 5px;">{{ $buku->judul }}</h2>
                        <span style="color: #7c3aff; font-weight: 700; font-size: 16px;">{{ $buku->penulis }}</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <small style="color: #94a3b8; font-weight: 700;">KATEGORI</small>
                            <p style="color: #1e1b3a; font-weight: 600;">{{ $buku->category->nama ?? 'Tanpa Kategori' }}</p>
                        </div>
                        <div>
                            <small style="color: #94a3b8; font-weight: 700;">TAHUN TERBIT</small>
                            <p style="color: #1e1b3a; font-weight: 600;">{{ $buku->tahun_terbit }}</p>
                        </div>
                        <div>
                            <small style="color: #94a3b8; font-weight: 700;">STOK TERSEDIA</small>
                            <p style="color: #1e1b3a; font-weight: 600;">{{ $buku->stok }} Buku</p>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <a href="{{ route('admin.dashboard') }}" class="page-btn"
                            style="text-decoration: none; background: #eee; color: #333;">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Tabel Riwayat Peminjaman Khusus Buku Ini --}}
            <div style="margin-top: 50px;">
                <h4 style="font-weight: 800; color: #1e1b3a; margin-bottom: 20px;">Riwayat Peminjaman Terakhir</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $p)
                            <tr>
                                <td style="font-weight: 600;">{{ $p->user->name }}</td>
                                <td>{{ $p->tgl_pinjam->format('d M Y') }}</td>
                                <td>{{ $p->tgl_kembali->format('d M Y') }}</td>
                                <td>
                                    <span class="badge"
                                        style="background: {{ $p->status == 'pinjam' ? '#fff4e5' : '#e6fffa' }}; color: {{ $p->status == 'pinjam' ? '#ff9800' : '#38b2ac' }}; padding: 4px 10px; border-radius: 6px; font-size: 11px;">
                                        {{ strtoupper($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada riwayat
                                    peminjaman untuk buku ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection