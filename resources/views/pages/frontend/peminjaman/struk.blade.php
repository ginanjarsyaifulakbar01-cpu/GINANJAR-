<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Peminjaman #{{ $peminjaman->id }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0;
            padding: 5mm;
            font-size: 12px;
            color: #000;
        }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .header h2 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; font-size: 10px; }
        .info-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .info-table td { vertical-align: top; padding: 2px 0; }
        .footer { margin-top: 15px; font-size: 9px; line-height: 1.2; }
        .buku-title { font-weight: bold; text-transform: uppercase; display: block; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="text-center header">
        <h2>PERPUS GINX</h2>
        <p>Sistem Perpustakaan Digital</p>
        <p>Jl. GinxAdmin No. 1, Indonesia</p>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td style="width: 35%;">ID Pinjam</td>
            <td>: #{{ $peminjaman->id }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Peminjam</td>
            <td>: {{ $peminjaman->user->name }}</td>
        </tr>
    </table>

    <div class="divider"></div>
    
    <div style="margin: 10px 0;">
        <strong>BUKU YANG DIPINJAM:</strong>
        <span class="buku-title">{{ $peminjaman->buku->judul }}</span>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td style="width: 45%;">Tgl Kembali</td>
            <td>: <strong>{{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->format('d/m/Y') }}</strong></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ strtoupper($peminjaman->status) }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="text-center footer">
        <p>HARAP KEMBALIKAN BUKU TEPAT WAKTU</p>
        <p>Denda keterlambatan berlaku sesuai aturan.</p>
        <p>*** Terima Kasih ***</p>
        <p>{{ date('Y-m-d H:i:s') }}</p>
    </div>

    <script>
        // Otomatis buka dialog print pas PDF dibuka di browser
        window.onload = function() { window.print(); }
    </script>
</body>
</html>