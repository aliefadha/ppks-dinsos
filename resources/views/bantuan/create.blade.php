@extends('layouts.app')

@section('title', 'Tambah Bantuan - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Tambah Bantuan</h1>
    <a href="{{ route('bantuan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Form Tambah Bantuan</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('bantuan.store') }}" method="POST" data-validate data-form-handler data-autosave>
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="nama_bantuan" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Bantuan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_bantuan') border-red-500 @enderror indonesian-text"
                           id="nama_bantuan" name="nama_bantuan" value="{{ old('nama_bantuan') }}"
                           placeholder="Masukkan nama bantuan" required>
                    @error('nama_bantuan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror indonesian-text"
                              id="deskripsi" name="deskripsi" rows="4"
                              placeholder="Masukkan deskripsi bantuan" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal') border-red-500 @enderror"
                           id="tanggal" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                           required>
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors" id="submitBtn">
                    <i class="fas fa-save mr-2"></i> <span class="btn-text">Simpan</span>
                </button>
                <a href="{{ route('bantuan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection