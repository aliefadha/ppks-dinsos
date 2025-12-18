@extends('layouts.app')

@section('title', 'Detail Bantuan - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Detail Bantuan</h1>
    <div class="flex flex-col sm:flex-row gap-2">
        <a href="{{ route('bantuan.edit', $bantuan->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <a href="{{ route('bantuan.addPenerimas', $bantuan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Tambah Penerima
        </a>
        <a href="{{ route('bantuan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Detail Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
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
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 flex justify-between items-center">
        <h6 class="text-lg font-semibold">Penerima Program Bantuan</h6>
        <a href="{{ route('bantuan.addPenerimas', $bantuan) }}" class="inline-flex items-center px-3 py-1 bg-white text-blue-600 rounded-md hover:bg-gray-100 transition-colors text-sm">
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
                                <form action="{{ route('bantuan.detachPenerima', [$bantuan, $penerima]) }}" method="POST" onsubmit="return confirmDeletePenerima('{{ $penerima->nama }}')">
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
</script>
@endsection