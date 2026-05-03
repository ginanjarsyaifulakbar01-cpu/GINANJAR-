@extends('layout.backend.app')

@section('content')
<div class="content">
    {{-- Dashboard Stats --}}
  

    <div class="table-card" style="background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);">
        <div class="table-header" style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #1e1b3a;">Laporan Riwayat Peminjaman</h3>
                    <p style="font-size: 12px; color: #94a3b8; margin: 0;">Data peminjaman buku perpustakaan digital (GinxAdmin)</p>
                </div>
                <a href="{{ route('laporan.cetak', request()->query()) }}" target="_blank"
                    style="background: #ef4444; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600;">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>
            </div>

            {{-- Form Filter --}}
            <form action="{{ route('laporan.index') }}" method="GET"
                style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end; background: #f8fafc; padding: 15px; border-radius: 10px;">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 11px; font-weight: 700; color: #64748b;">DARI TANGGAL</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}"
                        style="padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 11px; font-weight: 700; color: #64748b;">SAMPAI TANGGAL</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}"
                        style="padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px;">
                </div>
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 11px; font-weight: 700; color: #64748b;">STATUS</label>
                    <select name="status" style="padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px;">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="pinjam" {{ request('status') == 'pinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="proses_kembali" {{ request('status') == 'proses_kembali' ? 'selected' : '' }}>Proses Balik</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <button type="submit" style="background: #7c3aff; color: #fff; border: none; padding: 10px 25px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600;">Filter</button>
                <a href="{{ route('laporan.index') }}" style="background: #e2e8f0; color: #475569; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-size: 12px;">Reset</a>
            </form>
        </div>

        {{-- Tabel Data --}}
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 1000px;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 11px; text-transform: uppercase;">No</th>
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 11px; text-transform: uppercase;">Peminjam</th>
                        <th style="padding: 15px; text-align: left; color: #64748b; font-size: 11px; text-transform: uppercase;">Buku</th>
                        <th style="padding: 15px; text-align: center; color: #64748b; font-size: 11px; text-transform: uppercase;">Tgl Pinjam</th>
                        <th style="padding: 15px; text-align: center; color: #64748b; font-size: 11px; text-transform: uppercase;">Nominal Denda</th>
                        <th style="padding: 15px; text-align: center; color: #64748b; font-size: 11px; text-transform: uppercase;">Status Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $p)
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 13px;">
                            <td style="padding: 15px; color: #94a3b8;">{{ $data->firstItem() + $index }}</td>
                            <td style="padding: 15px; font-weight: 600; color: #1e1b3a;">{{ $p->user->name }}</td>
                            <td style="padding: 15px; color: #475569;">{{ $p->buku->judul }}</td>
                            <td style="padding: 15px; text-align: center;">{{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d/m/Y') }}</td>
                            
                            {{-- KOLOM NOMINAL DENDA (DIUBAH KE $p->denda) --}}
                            <td style="padding: 15px; text-align: center;">
                                @if($p->denda > 0)
                                    <span style="font-weight: 700; color: {{ $p->status_denda == 'lunas' ? '#10b981' : '#e11d48' }};">
                                        Rp {{ number_format($p->denda, 0, ',', '.') }}
                                        <small style="display: block; font-size: 9px; font-weight: 400; color: #64748b;">
                                            ({{ $p->is_terlambat ? 'TERLAMBAT' : 'DENDA' }})
                                        </small>
                                    </span>
                                @else 
                                    <span style="color: #94a3b8;">-</span> 
                                @endif
                            </td>

                            <td style="padding: 15px; text-align: center;">
                                @php
                                    $statusColors = [
                                        'pending' => ['bg' => '#fef9c3', 'text' => '#854d0e'],
                                        'pinjam' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                        'proses_kembali' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                        'dikembalikan' => ['bg' => '#f1f5f9', 'text' => '#475569'],
                                        'ditolak' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                    ];
                                    $color = $statusColors[$p->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
                                @endphp
                                <span style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 6px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase;">
                                    {{ str_replace('_', ' ', $p->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 50px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-folder-open" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                                Belum ada data transaksi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 25px;">
            {{ $data->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection