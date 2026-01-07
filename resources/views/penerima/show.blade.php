@extends('layouts.app')

@section('title', 'Detail Penerima - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 no-print">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Detail Penerima</h1>
    <div class="flex flex-col sm:flex-row gap-2">
        <a href="{{ route('penerima.edit', $penerima->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <a href="{{ route('penerima.addBantuans', $penerima) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Tambah Bantuan
        </a>
        <a href="{{ route('penerima.export', $penerima) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors no-print">
            <i class="fas fa-file-excel mr-2"></i> Cetak
        </a>
        <a href="{{ route('penerima.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Flash Messages -->
<div class="no-print">
@include('partials.flash-messages')
</div>

<!-- Detail Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Informasi Penerima</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->nama }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">NIK</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->nik }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">No. KK</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->no_kk ?? '-' }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Desil</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                        @if($penerima->desil)
                            {{ $penerima->desil }}
                            <span class="text-xs text-gray-500 ml-2">
                                @if($penerima->desil <= 5)
                                    (Dapat menerima bantuan)
                                @else
                                    (Tidak dapat menerima bantuan)
                                @endif
                            </span>
                        @else
                            -
                        @endif
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Jumlah Bantuan</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->bantuans->count() }} program bantuan</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Kategori</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->jenis }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Tanggal Dibuat</h3>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ \Carbon\Carbon::parse($penerima->created_at)->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</h3>
            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->alamat }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Kelurahan</h3>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->kelurahan }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Kecamatan</h3>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $penerima->kecamatan }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Many-to-Many Bantuan Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 flex justify-between items-center">
        <h6 class="text-lg font-semibold">Program Bantuan yang Diterima</h6>
        <a href="{{ route('penerima.addBantuans', $penerima) }}" class="inline-flex items-center px-3 py-1 bg-white text-blue-600 rounded-md hover:bg-gray-100 transition-colors text-sm no-print">
            <i class="fas fa-plus mr-1"></i> Tambah Bantuan
        </a>
    </div>
    <div class="p-6">
        @if($penerima->bantuans->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bantuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bantuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Diberikan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($penerima->bantuans as $index => $bantuan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $bantuan->nama_bantuan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($bantuan->deskripsi, 100) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bantuan->pivot->tanggal_diberikan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('penerima.detachBantuan', [$penerima, $bantuan]) }}" method="POST" onsubmit="return confirmDeleteBantuan('{{ $bantuan->nama_bantuan }}')" class="no-print">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">Penerima ini belum terdaftar dalam program bantuan mana pun.</p>
            </div>
        @endif
    </div>
</div>

<script>
// Set print date when page loads
document.addEventListener('DOMContentLoaded', function() {
    document.body.setAttribute('data-print-date', new Date().toLocaleString('id-ID'));
});

function confirmDeleteBantuan(namaBantuan) {
    return confirm('Apakah Anda yakin ingin menghapus program bantuan "' + namaBantuan + '" untuk penerima ini?');
}

function printBantuanList() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');

    // Get the current date
    const currentDate = new Date().toLocaleString('id-ID');

    // Create the HTML content for the print view
    const printContent = `
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cetak Daftar Bantuan - {{ $penerima->nama }}</title>
        <style>
            body {
                font-family: 'Inter', sans-serif;
                margin: 0;
                padding: 20px;
                color: black;
            }

            .header {
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #ddd;
                padding-bottom: 20px;
            }

            .recipient-info {
                margin-bottom: 30px;
                padding: 15px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 5px;
            }

            .recipient-info h3 {
                margin-top: 0;
                color: #333;
            }

            .info-row {
                margin-bottom: 8px;
            }

            .info-label {
                font-weight: bold;
                display: inline-block;
                width: 120px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }

            tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #666;
                border-top: 1px solid #ddd;
                padding-top: 10px;
            }

            .no-data {
                text-align: center;
                padding: 20px;
                font-style: italic;
                color: #666;
            }

            @media print {
                body {
                    padding: 10px;
                }

                .header {
                    margin-bottom: 20px;
                }

                .recipient-info {
                    margin-bottom: 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Laporan Program Bantuan yang Diterima</h1>
            <h2>PPKS Dinsos</h2>
        </div>

        <div class="recipient-info">
            <h3>Informasi Penerima</h3>
            <div class="info-row">
                <span class="info-label">Nama Lengkap:</span> {{ $penerima->nama }}
            </div>
            <div class="info-row">
                <span class="info-label">NIK:</span> {{ $penerima->nik }}
            </div>
            <div class="info-row">
                <span class="info-label">No. KK:</span> {{ $penerima->no_kk ?? '-' }}
            </div>
            <div class="info-row">
                <span class="info-label">Desil:</span>
                @if($penerima->desil)
                    {{ $penerima->desil }}
                    @if($penerima->desil <= 5)
                        (Dapat menerima bantuan)
                    @else
                        (Tidak dapat menerima bantuan)
                    @endif
                @else
                    -
                @endif
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Kelamin:</span> {{ $penerima->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </div>
            <div class="info-row">
                <span class="info-label">Alamat:</span> {{ $penerima->alamat }}, {{ $penerima->kelurahan }}, {{ $penerima->kecamatan }}
            </div>
        </div>

        <h3>Daftar Program Bantuan</h3>
        @if($penerima->bantuans->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Bantuan</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Bantuan</th>
                        <th>Tanggal Diberikan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penerima->bantuans as $index => $bantuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bantuan->nama_bantuan }}</td>
                        <td>{{ $bantuan->deskripsi }}</td>
                        <td>{{ \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y') }}</td>
                        <td>{{ $bantuan->pivot->tanggal_diberikan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                Penerima ini belum terdaftar dalam program bantuan mana pun.
            </div>
        @endif

        <div class="footer">
            <p>Laporan ini dicetak pada: ${currentDate}</p>
        </div>
    </body>
    </html>
    `;

    // Write the content to the new window
    printWindow.document.write(printContent);
    printWindow.document.close();

    // Wait for the content to load, then print
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}
</script>

<style>
@media print {
    /* Hide elements that shouldn't be printed */
    .no-print {
        display: none !important;
    }

    /* Remove padding and margins for print */
    body {
        padding: 0 !important;
        margin: 0 !important;
        background: white !important;
    }

    /* Ensure content takes full width */
    .p-6 {
        padding: 1rem !important;
    }

    /* Make cards look better in print */
    .bg-white {
        background: white !important;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    /* Remove hover effects */
    .hover\:bg-gray-50:hover {
        background-color: transparent !important;
    }

    /* Ensure text is black for better readability */
    * {
        color: black !important;
    }

    /* Style headers for print */
    .bg-gradient-to-r {
        background: #f3f4f6 !important;
        color: black !important;
        border-bottom: 2px solid #ddd !important;
    }

    /* Make sure tables are readable */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    th, td {
        border: 1px solid #ddd !important;
        padding: 8px !important;
    }

    th {
        background-color: #f3f4f6 !important;
        font-weight: bold !important;
    }

    /* Add page break before cards if needed */
    .bg-white {
        page-break-inside: avoid;
    }

    /* Add print header */
    body::before {
        content: "Laporan Detail Penerima - PPKS Dinsos";
        display: block;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ddd;
    }

    /* Add print footer */
    body::after {
        content: "Dicetak pada: " attr(data-print-date);
        display: block;
        text-align: center;
        font-size: 12px;
        margin-top: 20px;
        padding-top: 10px;
        border-top: 1px solid #ddd;
    }
}
</style>
@endsection
