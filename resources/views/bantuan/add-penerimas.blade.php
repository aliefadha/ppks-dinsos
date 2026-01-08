@extends('layouts.app')

@section('title', 'Tambah Penerima ke ' . $bantuan->nama_bantuan . ' - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Tambah Penerima ke {{ $bantuan->nama_bantuan }}</h1>
    <a href="{{ route('bantuan.show', $bantuan) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Pilih Penerima</h6>
    </div>
     <div class="p-6">
         @if($availablePenerimas->count() > 0)
            <form action="{{ route('bantuan.storePenerimas', $bantuan) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="penerima_ids" class="block text-sm font-medium text-gray-700 mb-2">Pilih Penerima:</label>
                        <select name="penerima_ids[]" id="penerima_ids" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" multiple required size="10">
                            @foreach($availablePenerimas as $penerima)
                            <option value="{{ $penerima->id }}" class="py-2">
                                {{ $penerima->nama }} (NIK: {{ $penerima->nik }}) - Desil: {{ $penerima->desil ?? '-' }}
                            </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Tahan Ctrl/Cmd untuk memilih multiple penerima</p>
                    </div>

                    <div>
                        <label for="tanggal_diberikan" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Diberikan:</label>
                        <input type="date" name="tanggal_diberikan" id="tanggal_diberikan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                    <a href="{{ route('bantuan.show', $bantuan) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        Batal
                    </a>
                </div>
            </form>
         @else
             <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                 <div class="flex items-center">
                     <i class="fas fa-info-circle text-blue-400 mr-3"></i>
                     <div>
                          <p class="text-sm text-blue-700">Semua penerima yang dapat menerima bantuan (desil 1-5) sudah terdaftar dalam program bantuan ini.</p>
                          <p class="text-sm text-blue-700 mt-2">Penerima dengan desil kosong atau 6-10 tidak dapat menerima bantuan.</p>
                     </div>
                 </div>
             </div>
            <div class="mt-6">
                <a href="{{ route('bantuan.show', $bantuan) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Bantuan
                </a>
            </div>
        @endif
    </div>
</div>

<style>
select[multiple] option {
    padding: 8px;
}
</style>
@endsection
