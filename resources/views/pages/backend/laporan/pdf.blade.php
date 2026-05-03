<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman Perpustakaan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .summary {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <h2>LAPORAN PEMINJAMAN BUKU</h2>
        <p>Periode: {{ $filter_tgl }}</p>
        <hr>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->buku->judul }}</td>
                    <td>{{ $item->tgl_pinjam }}</td>
                    <td>{{ $item->tgl_kembali }}</td>
                    <td>Rp {{ number_format($item->total_denda, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($item->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        Total Pendapatan Denda Lunas: Rp {{ number_format($total_denda, 0, ',', '.') }}
    </div>

    <div class="footer">
        Dicetak pada: {{ $tgl_cetak }}<br><br><br>
        ( Petugas Perpustakaan )
    </div>
</body>

</html>