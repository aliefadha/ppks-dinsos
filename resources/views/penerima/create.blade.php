@extends('layouts.app')

@section('title', 'Tambah Penerima - PPKS Dinsos')

@section('content')
<!-- Page Heading -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Tambah Penerima</h1>
    <a href="{{ route('penerima.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<!-- Flash Messages -->
@include('partials.flash-messages')

<!-- Form Card -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-4">
        <h6 class="text-lg font-semibold">Form Tambah Penerima</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('penerima.store') }}" method="POST" data-validate data-form-handler data-autosave>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror nama-input"
                               id="nama" name="nama" value="{{ old('nama') }}"
                               placeholder="Masukkan nama lengkap" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nik') border-red-500 @enderror nik-input"
                                   id="nik" name="nik" value="{{ old('nik') }}"
                                   placeholder="Masukkan 16 digit NIK" maxlength="16" required>
                            <button type="button" id="checkNikBtn" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors flex items-center">
                                <i class="fas fa-search mr-2"></i> Cek NIK
                            </button>
                        </div>
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="nikCheckMessage" class="mt-1 text-sm hidden"></div>
                    </div>

                    <div>
                        <label for="no_kk" class="block text-sm font-medium text-gray-700 mb-2">
                            No. KK
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_kk') border-red-500 @enderror no_kk-input"
                               id="no_kk" name="no_kk" value="{{ old('no_kk') }}"
                               placeholder="Masukkan 16 digit No. KK (opsional)" maxlength="16">
                        @error('no_kk')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="desil" class="block text-sm font-medium text-gray-700 mb-2">
                            Desil <span class="text-red-500">*</span>
                        </label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('desil') border-red-500 @enderror desil-input"
                               id="desil" name="desil" value="{{ old('desil') }}"
                               placeholder="Masukkan desil 1-10" min="1" max="10" required>
                        @error('desil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jenis_kelamin') border-red-500 @enderror"
                                id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('alamat') border-red-500 @enderror alamat-input"
                                  id="alamat" name="alamat" rows="3"
                                  placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kelurahan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kelurahan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kelurahan') border-red-500 @enderror"
                               id="kelurahan" name="kelurahan" value="{{ old('kelurahan') }}"
                               placeholder="Masukkan kelurahan" required>
                        @error('kelurahan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kecamatan') border-red-500 @enderror"
                               id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}"
                               placeholder="Masukkan kecamatan" required>
                        @error('kecamatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jenis') border-red-500 @enderror"
                                id="jenis" name="jenis" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="disabilitas" {{ old('jenis') == 'disabilitas' ? 'selected' : '' }}>Disabilitas</option>
                            <option value="lansia" {{ old('jenis') == 'lansia' ? 'selected' : '' }}>Lansia</option>
                            <option value="pengemis" {{ old('jenis') == 'pengemis' ? 'selected' : '' }}>Pengemis</option>
                            <option value="adk" {{ old('jenis') == 'adk' ? 'selected' : '' }}>ADK</option>
                            <option value="anak_terlantar" {{ old('jenis') == 'anak_terlantar' ? 'selected' : '' }}>Anak Terlantar</option>
                        </select>
                        @error('jenis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section: Tambah Bantuan -->
            @if($availableBantuans->count() > 0)
            <div id="bantuanSection" class="mt-8 pt-8 border-t border-gray-200 hidden">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-hands-helping mr-2 text-blue-600"></i>Tambah Program Bantuan
                </h3>
                <p class="text-sm text-gray-600 mb-4">Pilih program bantuan yang akan diberikan kepada penerima ini (opsional)</p>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-base font-medium text-gray-900">Pilih Program Bantuan</label>
                        <div class="flex items-center space-x-4">
                            <button type="button" id="selectAllBantuanBtn" class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-check-square mr-1"></i> Pilih Semua
                            </button>
                            <button type="button" id="clearAllBantuanBtn" class="text-sm text-gray-600 hover:text-gray-800">
                                <i class="fas fa-square mr-1"></i> Hapus Pilihan
                            </button>
                        </div>
                    </div>

                    <div id="bantuanSelection" class="space-y-2 max-h-60 overflow-y-auto">
                        @foreach($availableBantuans as $bantuan)
                        <div class="flex items-center p-2 hover:bg-gray-100 rounded">
                            <input type="checkbox" name="bantuan_ids[]" value="{{ $bantuan->id }}"
                                   id="bantuan_{{ $bantuan->id }}"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded bantuan-checkbox">
                            <label for="bantuan_{{ $bantuan->id }}" class="ml-3 flex-1 cursor-pointer">
                                <span class="font-medium text-gray-900">{{ $bantuan->nama_bantuan }}</span>
                                <span class="text-sm text-gray-500 block">- {{ \Carbon\Carbon::parse($bantuan->tanggal)->format('d F Y') }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div id="bantuanSelectedCount" class="mt-2 text-sm text-gray-600">
                        0 program bantuan dipilih
                    </div>
                </div>

                <div class="mt-4">
                    <label for="tanggal_diberikan" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Diberikan:</label>
                    <input type="date" name="tanggal_diberikan" id="tanggal_diberikan"
                           class="w-full sm:w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           value="{{ date('Y-m-d') }}">
                </div>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors" id="submitBtn">
                    <i class="fas fa-save mr-2"></i> <span class="btn-text">Simpan</span>
                </button>
                <a href="{{ route('penerima.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkNikBtn = document.getElementById('checkNikBtn');
    const nikInput = document.getElementById('nik');
    const nikCheckMessage = document.getElementById('nikCheckMessage');
    const form = document.querySelector('form[data-validate]');

    // Real-time NIK validation
    nikInput.addEventListener('input', function() {
        const nik = this.value.trim();

        // Only show validation message if user has started typing
        if (nik.length > 0) {
            if (!/^[0-9]*$/.test(nik)) {
                showNikMessage('NIK hanya boleh mengandung angka', 'error');
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            } else if (nik.length < 16) {
                showNikMessage(`NIK harus 16 digit`, 'warning');
                this.classList.add('border-yellow-500');
                this.classList.remove('border-green-500', 'border-red-500');
            } else if (nik.length === 16) {
                if (!/^[0-9]{16}$/.test(nik)) {
                    showNikMessage('NIK harus 16 digit angka yang valid', 'error');
                    this.classList.add('border-red-500');
                    this.classList.remove('border-green-500', 'border-yellow-500');
                } else {
                    showNikMessage('NIK valid!', 'success');
                    this.classList.add('border-green-500');
                    this.classList.remove('border-red-500', 'border-yellow-500');
                }
            }
        } else {
            // Clear validation message when field is empty
            nikCheckMessage.classList.add('hidden');
            this.classList.remove('border-red-500', 'border-green-500', 'border-yellow-500');
        }
    });

    // Prevent non-numeric input
    nikInput.addEventListener('keypress', function(e) {
        // Allow backspace, delete, tab, escape, enter
        if ([8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    // Form fields to auto-fill
    const namaInput = document.getElementById('nama');
    const alamatInput = document.getElementById('alamat');
    const kelurahanInput = document.getElementById('kelurahan');
    const kecamatanInput = document.getElementById('kecamatan');
    const jenisSelect = document.getElementById('jenis');
    const jenisKelaminSelect = document.getElementById('jenis_kelamin');
    const noKkInput = document.getElementById('no_kk');
    const desilInput = document.getElementById('desil');
    const bantuanSection = document.getElementById('bantuanSection');

    // Handle desil change to show/hide bantuan section
    function toggleBantuanSection() {
        const desil = parseInt(desilInput.value);
        // Only show bantuan section if desil is filled AND is a valid value (1-5)
        if (desil && desil >= 1 && desil <= 5) {
            bantuanSection.classList.remove('hidden');
        } else {
            bantuanSection.classList.add('hidden');
        }
    }

    if (desilInput && bantuanSection) {
        desilInput.addEventListener('input', toggleBantuanSection);
        desilInput.addEventListener('change', toggleBantuanSection);
    }

    // No. KK input validation
    if (noKkInput) {
        noKkInput.addEventListener('input', function() {
            const noKk = this.value.trim();

            // Only show validation message if user has started typing
            if (noKk.length > 0) {
                if (!/^[0-9]*$/.test(noKk)) {
                    this.classList.add('border-red-500');
                    this.classList.remove('border-green-500');
                } else if (noKk.length < 16) {
                    this.classList.remove('border-red-500', 'border-green-500');
                } else if (noKk.length === 16) {
                    if (!/^[0-9]{16}$/.test(noKk)) {
                        this.classList.add('border-red-500');
                        this.classList.remove('border-green-500');
                    } else {
                        this.classList.add('border-green-500');
                        this.classList.remove('border-red-500');
                    }
                }
            } else {
                this.classList.remove('border-red-500', 'border-green-500');
            }
        });

        // Prevent non-numeric input
        noKkInput.addEventListener('keypress', function(e) {
            if ([8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    checkNikBtn.addEventListener('click', function() {
        const nik = nikInput.value.trim();

        // Validate NIK format
        if (!nik) {
            showNikMessage('Mohon masukkan NIK terlebih dahulu', 'error');
            nikInput.classList.add('border-red-500');
            return;
        }

        if (!/^[0-9]{16}$/.test(nik)) {
            showNikMessage('NIK harus 16 digit angka', 'error');
            nikInput.classList.add('border-red-500');
            return;
        }

        // Show loading state
        checkNikBtn.disabled = true;
        checkNikBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengecek...';
        showNikMessage('Sedang memeriksa NIK...', 'info');

        // Send AJAX request to check NIK
        fetch('{{ route("penerima.checkNik") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nik: nik
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.found) {
                // Auto-fill form with found data
                const penerima = data.data;

                namaInput.value = penerima.nama || '';
                alamatInput.value = penerima.alamat || '';
                kelurahanInput.value = penerima.kelurahan || '';
                kecamatanInput.value = penerima.kecamatan || '';
                noKkInput.value = penerima.no_kk || '';
                desilInput.value = penerima.desil || '';

                // Set jenis select value
                if (penerima.jenis) {
                    // Find the option with matching value
                    for (let i = 0; i < jenisSelect.options.length; i++) {
                        if (jenisSelect.options[i].value === penerima.jenis) {
                            jenisSelect.selectedIndex = i;
                            break;
                        }
                    }
                }

                // Set jenis_kelamin select value
                if (penerima.jenis_kelamin) {
                    for (let i = 0; i < jenisKelaminSelect.options.length; i++) {
                        if (jenisKelaminSelect.options[i].value === penerima.jenis_kelamin) {
                            jenisKelaminSelect.selectedIndex = i;
                            break;
                        }
                    }
                }

                showNikMessage('Data ditemukan! Form telah diisi otomatis.', 'success');

                // Scroll to top of form
                form.scrollIntoView({ behavior: 'smooth' });
            } else {
                showNikMessage('NIK tidak ditemukan dalam database. Silakan isi form manually.', 'warning');

                // Clear form fields
                namaInput.value = '';
                alamatInput.value = '';
                kelurahanInput.value = '';
                kecamatanInput.value = '';
                noKkInput.value = '';
                desilInput.value = '';
                jenisSelect.selectedIndex = 0; // Reset to first option (placeholder)
                jenisKelaminSelect.selectedIndex = 0; // Reset to first option (placeholder)
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNikMessage('Terjadi kesalahan saat memeriksa NIK. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            // Reset button state
            checkNikBtn.disabled = false;
            checkNikBtn.innerHTML = '<i class="fas fa-search mr-2"></i> Cek NIK';
        });
    });

    function showNikMessage(message, type) {
        nikCheckMessage.textContent = message;
        nikCheckMessage.classList.remove('hidden', 'text-green-600', 'text-red-600', 'text-yellow-600', 'text-blue-600');

        switch(type) {
            case 'success':
                nikCheckMessage.classList.add('text-green-600');
                break;
            case 'error':
                nikCheckMessage.classList.add('text-red-600');
                break;
            case 'warning':
                nikCheckMessage.classList.add('text-yellow-600');
                break;
            case 'info':
                nikCheckMessage.classList.add('text-blue-600');
                break;
        }
    }

    // Bantuan selection functionality
    const selectAllBantuanBtn = document.getElementById('selectAllBantuanBtn');
    const clearAllBantuanBtn = document.getElementById('clearAllBantuanBtn');
    const bantuanCheckboxes = document.querySelectorAll('.bantuan-checkbox');
    const bantuanSelectedCount = document.getElementById('bantuanSelectedCount');

    function updateBantuanSelectedCount() {
        const checkedCount = document.querySelectorAll('.bantuan-checkbox:checked').length;
        bantuanSelectedCount.textContent = `${checkedCount} program bantuan dipilih`;
    }

    if (selectAllBantuanBtn && clearAllBantuanBtn) {
        selectAllBantuanBtn.addEventListener('click', function() {
            bantuanCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateBantuanSelectedCount();
        });

        clearAllBantuanBtn.addEventListener('click', function() {
            bantuanCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateBantuanSelectedCount();
        });

        bantuanCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBantuanSelectedCount);
        });

        // Initialize count
        updateBantuanSelectedCount();
    }
});
</script>
@endsection
