@extends('layouts.app')

@section('title', 'Detail Bantuan - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 no-print">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Detail Bantuan</h1>
    <div class="flex flex-col sm:flex-row gap-2">
        <a href="{{ route('bantuan.edit', $bantuan->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <a href="{{ route('bantuan.addPenerimas', $bantuan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Tambah Penerima
        </a>
        <a href="{{ route('bantuan.exportExcel', $bantuan) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors no-print">
            <i class="fas fa-file-excel mr-2"></i> Cetak
        </a>
        <a href="{{ route('bantuan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
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
        <h6 class="text-lg font-semibold">Informasi Bantuan</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Nama Bantuan</h3>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $bantuan->nama_bantuan }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Tanggal</h3>
                <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y') }}</p>
            </div>
        </div>
        
        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi</h3>
            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $bantuan->deskripsi }}</p>
        </div>
        
        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Jumlah Penerima</h3>
            <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded-md">{{ $bantuan->penerimas->count() }} orang</p>
        </div>
    </div>
</div>

<!-- Many-to-Many Penerima Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4 flex justify-between items-center">
        <h6 class="text-lg font-semibold">Penerima Program Bantuan</h6>
        <a href="{{ route('bantuan.addPenerimas', $bantuan) }}" class="inline-flex items-center px-3 py-1 bg-white text-blue-600 rounded-md hover:bg-gray-100 transition-colors text-sm no-print">
            <i class="fas fa-plus mr-1"></i> Tambah Penerima
        </a>
    </div>
    <div class="p-6">
        @if($bantuan->penerimas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecamatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Diberikan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bantuan->penerimas as $index => $penerima)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $penerima->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerima->nik }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $penerima->alamat }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerima->kelurahan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerima->kecamatan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerima->jenis }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerima->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerima->pivot->tanggal_diberikan ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('bantuan.detachPenerima', [$bantuan, $penerima]) }}" method="POST" onsubmit="return confirmDeletePenerima('{{ $penerima->nama }}')" class="no-print">
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
                <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">Belum ada penerima untuk program bantuan ini.</p>
            </div>
        @endif
    </div>
</div>

<script>
function confirmDeletePenerima(nama) {
    return confirm('Apakah Anda yakin ingin menghapus "' + nama + '" dari program bantuan ini?');
}

function printPenerimaList() {
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
        <title>Cetak Daftar Penerima - {{ $bantuan->nama_bantuan }}</title>
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
            
            .bantuan-info {
                margin-bottom: 30px;
                padding: 15px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            
            .bantuan-info h3 {
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
                padding: 10px;
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
            
            .summary {
                margin-top: 20px;
                padding: 10px;
                background-color: #f2f2f2;
                border: 1px solid #ddd;
                border-radius: 5px;
                text-align: center;
                font-weight: bold;
            }
            
            @media print {
                body {
                    padding: 10px;
                }
                
                .header {
                    margin-bottom: 20px;
                }
                
                .bantuan-info {
                    margin-bottom: 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Daftar Penerima Program Bantuan</h1>
            <h2>PPKS Dinsos</h2>
        </div>
        
        <div class="bantuan-info">
            <h3>Informasi Program Bantuan</h3>
            <div class="info-row">
                <span class="info-label">Nama Bantuan:</span> {{ $bantuan->nama_bantuan }}
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span> {{ \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y') }}
            </div>
            <div class="info-row">
                <span class="info-label">Deskripsi:</span> {{ $bantuan->deskripsi }}
            </div>
        </div>
        
        <h3>Daftar Penerima</h3>
        @if($bantuan->penerimas->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Alamat</th>
                        <th>Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Jenis</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Diberikan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bantuan->penerimas as $index => $penerima)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $penerima->nama }}</td>
                        <td>{{ $penerima->nik }}</td>
                        <td>{{ $penerima->alamat }}</td>
                        <td>{{ $penerima->kelurahan }}</td>
                        <td>{{ $penerima->kecamatan }}</td>
                        <td>{{ $penerima->jenis }}</td>
                        <td>{{ $penerima->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $penerima->pivot->tanggal_diberikan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="summary">
                Total Penerima: {{ $bantuan->penerimas->count() }} orang
            </div>
        @else
            <div class="no-data">
                Belum ada penerima untuk program bantuan ini.
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
@endsection