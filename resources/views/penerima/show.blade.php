@extends('layouts.app')

@section('title', 'Detail Penerima - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Detail Penerima</h1>
    <div class="flex flex-col sm:flex-row gap-2">
        <a href="{{ route('penerima.edit', $penerima->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <a href="{{ route('penerima.addBantuans', $penerima) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Tambah Bantuan
        </a>
        <a href="{{ route('penerima.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Detail Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
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
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Jenis</h3>
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
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 flex justify-between items-center">
        <h6 class="text-lg font-semibold">Program Bantuan yang Diterima</h6>
        <a href="{{ route('penerima.addBantuans', $penerima) }}" class="inline-flex items-center px-3 py-1 bg-white text-blue-600 rounded-md hover:bg-gray-100 transition-colors text-sm">
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
                                <form action="{{ route('penerima.detachBantuan', [$penerima, $bantuan]) }}" method="POST" onsubmit="return confirmDeleteBantuan('{{ $bantuan->nama_bantuan }}')">
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
function confirmDeleteBantuan(namaBantuan) {
    return confirm('Apakah Anda yakin ingin menghapus program bantuan "' + namaBantuan + '" untuk penerima ini?');
}
</script>
@endsection