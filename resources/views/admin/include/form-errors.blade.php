@if($errors->any())
<div class="alert alert-danger admin-form-alert">
    <strong>Please correct the following:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif
