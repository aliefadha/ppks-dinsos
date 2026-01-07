@extends('layouts.app')

@section('title', 'Data Penerima - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Data Penerima</h1>
    <div class="flex flex-col sm:flex-row gap-2">
        <a href="{{ route('penerima.create') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors">
            <i class="fas fa-user-plus mr-2"></i> Tambah
        </a>
    </div>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Search and Filter Section -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <form method="GET" action="{{ route('penerima.index') }}" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                <div class="relative">
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                       placeholder="Cari nama, NIK, No. KK, alamat...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Filter Jenis -->
            <div>
                <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select id="jenis"
                        name="jenis"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    @foreach($jenisOptions as $option)
                        <option value="{{ $option }}" {{ request('jenis') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kelurahan -->
            <div>
                <label for="kelurahan" class="block text-sm font-medium text-gray-700 mb-1">Kelurahan</label>
                <select id="kelurahan"
                        name="kelurahan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    @foreach($kelurahanOptions as $option)
                        <option value="{{ $option }}" {{ request('kelurahan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kecamatan -->
            <div>
                <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                <select id="kecamatan"
                        name="kecamatan"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    @foreach($kecamatanOptions as $option)
                        <option value="{{ $option }}" {{ request('kecamatan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $penerima->firstItem() ?? 0 }} - {{ $penerima->lastItem() ?? 0 }} dari {{ $penerima->total() }} data
            </div>
            <div class="flex gap-2">
                <button type="button"
                        id="resetFilters"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-redo mr-2"></i> Reset
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i> Submit
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. KK</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desil</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelurahan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecamatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->no_kk ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->desil ?? '-' }}</td>
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
    // Initialize DataTable with server-side processing disabled
    // We'll use Laravel's pagination instead
    $('#dataTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json',
            paginate: {
                previous: "←",
                next: "→"
            }
        },
        responsive: true,
        pageLength: 10,
        ordering: false,
        searching: false, // Disable built-in search as we have our own
        paging: false,    // Disable built-in pagination as we use Laravel's
        info: false       // Disable info display as we show our own
    });

    // Handle form submission for filters
    $('#filterForm').on('submit', function(e) {
        // Let the form submit normally to reload the page with filters
        return true;
    });

    // Handle reset button
    $('#resetFilters').on('click', function() {
        // Clear all filter inputs
        $('#search').val('');
        $('#jenis_kelamin').val('');
        $('#jenis').val('');
        $('#kelurahan').val('');
        $('#kecamatan').val('');

        // Submit the form to reset
        $('#filterForm').submit();
    });

    // Auto-submit form on change for dropdown filters
    $('#jenis_kelamin, #jenis, #kelurahan, #kecamatan').on('change', function() {
        $('#filterForm').submit();
    });

    // Auto-submit on search input with debounce
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500); // Wait 500ms after user stops typing
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
