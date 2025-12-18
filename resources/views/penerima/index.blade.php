@extends('layouts.app')

@section('title', 'Data Penerima - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Data Penerima</h1>
    <div class="flex flex-col sm:flex-row gap-2">
        <a href="{{ route('penerima.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
            <i class="fas fa-user-plus mr-2"></i> Tambah
        </a>
    </div>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Daftar Penerima</h6>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="dataTable">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($penerima as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $penerima->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nik }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($item->alamat, 30) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->kelurahan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->kecamatan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jenis }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('penerima.show', $item->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('penerima.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="text-red-600 hover:text-red-900" title="Hapus" onclick="confirmDelete({{ $item->id }}, '{{ $item->nama }}')">
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
            {{ $penerima->links() }}
        </div>
    </div>
</div>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdown');
        const button = event.target.closest('button');
        
        if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
            dropdown.classList.add('hidden');
        }
    });
</script>
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

function confirmDelete(id, nama) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        html: 'Anda akan menghapus data penerima:<br><strong>' + nama + '</strong><br>Data yang telah dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {
                // Create form and submit
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '/penerima/' + id;
                
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
            });
        }
    });
}
</script>
@endpush