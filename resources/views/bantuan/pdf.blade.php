<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bantuan - {{ $bantuan->nama_bantuan }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            width: 100%;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1565c0;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .header-text {
            text-align: center;
        }

        .header h1 {
            font-size: 18px;
            color: #1565c0;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14px;
            color: #555;
            font-weight: normal;
        }

        .logo {
            width: 60px;
            height: auto;
            margin-bottom: 10px;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-card {
            background: #fff8e1;
            border: 1px solid #ffcc80;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-card h3 {
            font-size: 12px;
            color: #1565c0;
            margin-bottom: 12px;
            border-bottom: 1px solid #ffcc80;
            padding-bottom: 8px;
        }

        .info-row {
            display: flex;
            margin-bottom: 6px;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }

        .info-value {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #1565c0;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #fff3e0;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .summary {
            margin-top: 20px;
            padding: 12px;
            background-color: #e8f5e9;
            border: 1px solid #a5d6a7;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            font-style: italic;
            color: #888;
            background-color: #f9f9f9;
            border: 1px dashed #ddd;
            border-radius: 5px;
        }

        .page-break {
            page-break-before: always;
        }

        @page {
            margin: 1.5cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <img src="{{ $logoBase64 }}" alt="Logo Dinsos" class="logo">
            <div class="header-text">
                <h1>PEMERINTAH KOTA PAYAKUMBUH</h1>
                <h2>DINAS SOSIAL</h2>
                <h1 style="margin-top: 15px;">LAPORAN BANTUAN</h1>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-card">
            <h3>Informasi Bantuan</h3>
            <div class="info-row">
                <span class="info-label">Nama Bantuan:</span>
                <span class="info-value">{{ $bantuan->nama_bantuan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Deskripsi:</span>
                <span class="info-value">{{ $bantuan->deskripsi }}</span>
            </div>
        </div>
    </div>

    <h3 style="margin-bottom: 10px; color: #333;">Daftar Penerima Bantuan</h3>

    @if($bantuan->penerimas->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Nama</th>
                        <th style="width: 12%;">NIK</th>
                        <th style="width: 12%;">No KK</th>
                        <th style="width: 15%;">Alamat</th>
                        <th style="width: 10%;">Kelurahan</th>
                        <th style="width: 10%;">Kecamatan</th>
                        <th style="width: 10%;">Kategori</th>
                        <th style="width: 10%;">Desil</th>
                        <th style="width: 8%;">JK</th>
                        <th style="width: 10%;">Tgl Diberikan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bantuan->penerimas as $index => $penerima)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $penerima->nama }}</td>
                        <td>{{ $penerima->nik }}</td>
                        <td>{{ $penerima->no_kk }}</td>
                        <td>{{ $penerima->alamat }}</td>
                        <td>{{ $penerima->kelurahan }}</td>
                        <td>{{ $penerima->kecamatan }}</td>
                        <td>{{ $penerima->jenis }}</td>
                        <td>{{ $penerima->desil }}</td>
                        <td>{{ $penerima->jenis_kelamin == 'L' ? 'L' : 'P' }}</td>
                        <td>{{ $penerima->pivot->tanggal_diberikan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary">
            Total Penerima: {{ $bantuan->penerimas->count() }} orang
        </div>
    @else
        <div class="no-data">
            <p>Belum ada penerima untuk program bantuan ini.</p>
        </div>
    @endif
</body>
</html>
