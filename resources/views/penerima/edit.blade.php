@extends('layouts.app')

@section('title', 'Edit Penerima - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Edit Penerima</h1>
    <a href="{{ route('penerima.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Form Edit Penerima</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('penerima.update', $penerima->id) }}" method="POST" data-validate data-form-handler data-autosave>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('nama') border-red-500 @enderror nama-input"
                               id="nama" name="nama" value="{{ old('nama', $penerima->nama) }}"
                               placeholder="Masukkan nama lengkap" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('nik') border-red-500 @enderror nik-input"
                               id="nik" name="nik" value="{{ old('nik', $penerima->nik) }}"
                               placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_kk" class="block text-sm font-medium text-gray-700 mb-2">
                            No. KK
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('no_kk') border-red-500 @enderror no_kk-input"
                               id="no_kk" name="no_kk" value="{{ old('no_kk', $penerima->no_kk) }}"
                               placeholder="Masukkan 16 digit No. KK (opsional)" maxlength="16">
                        @error('no_kk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="desil" class="block text-sm font-medium text-gray-700 mb-2">
                            Desil <span class="text-red-500">*</span>
                        </label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('desil') border-red-500 @enderror desil-input"
                               id="desil" name="desil" value="{{ old('desil', $penerima->desil) }}"
                               placeholder="Masukkan desil 1-10" min="1" max="10" required>
                        @error('desil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror"
                                id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin', $penerima->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $penerima->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('alamat') border-red-500 @enderror alamat-input"
                                  id="alamat" name="alamat" rows="3"
                                  placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $penerima->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelurahan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelurahan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('kelurahan') border-red-500 @enderror"
                               id="kelurahan" name="kelurahan" value="{{ old('kelurahan', $penerima->kelurahan) }}"
                               placeholder="Masukkan kelurahan" required>
                        @error('kelurahan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('kecamatan') border-red-500 @enderror"
                               id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $penerima->kecamatan) }}"
                               placeholder="Masukkan kecamatan" required>
                        @error('kecamatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('jenis') border-red-500 @enderror"
                               id="jenis" name="jenis" value="{{ old('jenis', $penerima->jenis) }}"
                               placeholder="Masukkan jenis (misal: PKH, BST, dll)" required>
                        @error('jenis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700 transition-colors" id="submitBtn">
                    <i class="fas fa-save mr-2"></i> <span class="btn-text">Update</span>
                </button>
                <a href="{{ route('penerima.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
