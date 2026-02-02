@props([
    'data' => 'Data',
    'message' => 'Menghapus ' . $data . ' akan menghapus seluruh data terkait.',
])

<script>
    function confirmDelete(form) {
        Swal.fire({
            title: 'Hapus ' + @json($data) + ' ?',
            text: @json($message),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }
</script>
