@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: '{{ session('error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#4e73df'
        });
    @endif

    @if($errors->any())
        var errorMessages = @json($errors->all());
        var errorHtml = 'Mohon perbaiki kesalahan berikut:<br><br>' +
                       errorMessages.map(function(error) {
                           return '• ' + error;
                       }).join('<br>');
        
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            html: errorHtml,
            confirmButtonText: 'OK',
            confirmButtonColor: '#f6c23e',
            didOpen: function() {
                // Add field highlighting for errors
                @foreach($errors->keys() as $key)
                    var field = document.querySelector('[name="{{ $key }}"]');
                    if (field) {
                        field.classList.add('is-invalid');
                        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                @endforeach
            }
        });
    @endif
});
</script>
@endpush