<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
    Swal.fire({icon: 'success', title: 'Success', text: @json(session('success')), timer: 2200, showConfirmButton: false});
    @endif
    @if(session('error'))
    Swal.fire({icon: 'error', title: 'Error', text: @json(session('error'))});
    @endif
    @if($errors->any())
    Swal.fire({icon: 'error', title: 'Please check the form', html: @json('<ul class="text-start mb-0"><li>'.implode('</li><li>', $errors->all()).'</li></ul>')});
    @endif
});
</script>
