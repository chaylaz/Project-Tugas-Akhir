<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .logo-container {
            margin-bottom: 12px;
        }
        .logo-jsg {
            height: 32px;
            vertical-align: middle;
        }
        .logo-divider {
            border-left: 2px solid #cbd5e1;
            height: 32px;
            display: inline-block;
            vertical-align: middle;
            margin: 0 10px;
        }
        .logo-telkom {
            height: 36px;
            vertical-align: middle;
        }
        .header h1 {
            font-size: 15px;
            font-weight: bold;
            color: #1a202c;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header .meta {
            font-size: 11px;
            color: #718096;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th {
            background-color: #f7fafc;
            border-bottom: 2px solid #cbd5e0;
            color: #2d3748;
            font-weight: bold;
            text-align: left;
            padding: 10px 8px;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 8px;
            color: #4a5568;
            font-size: 11px;
        }
        tr:nth-child(even) td {
            background-color: #fcfdfd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 15px;
            text-align: right;
        }
        .total-box {
            display: inline-block;
            border: 1px solid #cbd5e0;
            background-color: #f7fafc;
            padding: 12px 20px;
            border-radius: 4px;
        }
        .total-box span {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }
        .total-box strong {
            font-size: 18px;
            color: #1a202c;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('logo-jsg-a.png') }}" alt="Logo JSG" class="logo-jsg">
            <span class="logo-divider"></span>
            <img src="{{ public_path('logo-telu.png') }}" alt="Logo Telkom" class="logo-telkom">
        </div>
        <h1>Laporan Pendapatan Jaya Sentosa Group</h1>
        <div class="meta">
            <strong>{{ $periodHeader }}</strong><br>
            Tanggal Unduh: {{ $downloadTime }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="25%">Nama Pelanggan</th>
                <th width="20%">Paket Layanan</th>
                <th width="15%">Periode</th>
                <th width="20%">Tanggal Pelunasan</th>
                <th width="15%" class="text-right">Jumlah (IDR)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tagihans as $index => $tagihan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $tagihan->pelanggan->nama ?? '-' }}</td>
                    <td>{{ $tagihan->paket->nama_paket ?? '-' }}</td>
                    <td>{{ $tagihan->periode }}</td>
                    <td>{{ $tagihan->updated_at ? $tagihan->updated_at->format('d M Y H:i') : '-' }}</td>
                    <td class="text-right">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #a0aec0;">Tidak ada data pendapatan untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="total-box">
            <span>Total Pendapatan</span>
            <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
        </div>
    </div>

</body>
</html>
