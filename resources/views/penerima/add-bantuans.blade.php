@extends('layouts.app')

@section('title', 'Tambah Bantuan untuk ' . $penerima->nama . ' - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Tambah Bantuan untuk {{ $penerima->nama }}</h1>
    <a href="{{ route('penerima.show', $penerima) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Pilih Program Bantuan</h6>
    </div>
    <div class="p-6">
        @if($availableBantuans->count() > 0)
            <form action="{{ route('penerima.attachMultipleBantuans', $penerima) }}" method="POST" id="attachMultipleBantuanForm">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-lg font-medium text-gray-900">Pilih Program Bantuan</label>
                            <div class="flex items-center space-x-4">
                                <button type="button" id="selectAllBtn" class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-check-square mr-1"></i> Pilih Semua
                                </button>
                                <button type="button" id="clearAllBtn" class="text-sm text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-square mr-1"></i> Hapus Pilihan
                                </button>
                            </div>
                        </div>
                        
                        <div id="bantuanSelection" class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            @php
                                $attachedBantuanIds = $penerima->bantuans->pluck('id')->toArray();
                            @endphp
                            @foreach($availableBantuans as $bantuan)
                            @php
                                $isAttached = in_array($bantuan->id, $attachedBantuanIds);
                            @endphp
                            <div class="flex items-center p-2 hover:bg-gray-50 rounded {{ $isAttached ? 'bg-green-50' : '' }}">
                                <input type="checkbox" name="bantuan_ids[]" value="{{ $bantuan->id }}"
                                       id="bantuan_{{ $bantuan->id }}"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       {{ $isAttached ? 'disabled' : '' }}>
                                <label for="bantuan_{{ $bantuan->id }}" class="ml-3 flex-1 cursor-pointer {{ $isAttached ? 'text-green-700' : '' }}">
                                    <span class="font-medium">{{ $bantuan->nama_bantuan }}</span>
                                    <span class="text-sm text-gray-500 block">- {{ \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y') }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        
                        <div id="selectedCount" class="mt-2 text-sm text-gray-600">
                            0 program bantuan dipilih
                        </div>
                        
                        @error('bantuan_ids')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_diberikan" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Diberikan:</label>
                        <input type="date" name="tanggal_diberikan" id="tanggal_diberikan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    <button type="submit" id="submitBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                    <a href="{{ route('penerima.show', $penerima) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        @else
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-400 mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-700">Penerima sudah terdaftar dalam semua program bantuan yang tersedia.</p>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('penerima.show', $penerima) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Penerima
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="bantuan_ids[]"]:not(:disabled)');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const selectedCount = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('input[name="bantuan_ids[]"]:checked').length;
        selectedCount.textContent = `${checkedCount} program bantuan dipilih`;
        
        // Enable/disable submit button based on selection
        if (checkedCount > 0) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
    
    // Add event listeners to all enabled checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Select all functionality - only select enabled checkboxes
    selectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('input[name="bantuan_ids[]"]:not(:disabled)').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedCount();
    });
    
    // Clear all functionality - only clear enabled checkboxes
    clearAllBtn.addEventListener('click', function() {
        document.querySelectorAll('input[name="bantuan_ids[]"]:not(:disabled)').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    });
    
    // Initialize the count
    updateSelectedCount();
});
</script>
@endsection