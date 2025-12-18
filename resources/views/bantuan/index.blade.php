@extends('layouts.app')

@section('title', 'Data Bantuan - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Data Bantuan</h1>
    <a href="{{ route('bantuan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
        <i class="fas fa-plus mr-2"></i> Tambah Bantuan
    </a>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Daftar Bantuan</h6>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bantuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Penerima</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bantuan as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $bantuan->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_bantuan }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($item->deskripsi, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->penerimas->count() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('bantuan.show', $item->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('bantuan.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900" title="Hapus" onclick="confirmDeleteBantuan({{ $item->id }}, '{{ $item->nama_bantuan }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $bantuan->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        },
        responsive: true,
        pageLength: 10,
        ordering: false
    });
});

function confirmDeleteBantuan(id, nama) {
    if (confirm('Apakah Anda yakin ingin menghapus data bantuan: ' + nama + '?\n\nData yang telah dihapus tidak dapat dikembalikan.')) {
        // Create form and submit
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/bantuan/' + id;
        
        // Add CSRF token
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        // Add DELETE method
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush