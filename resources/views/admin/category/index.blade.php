@extends('admin.master.master')

@section('title', 'Categories')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Categories</span></div>
        <h1 class="page-title">Food Categories</h1>
        <p class="page-subtitle">Manage the main categories shown on your menu.</p>
    </div>
    @can('category.create')
        <button type="button" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" data-category-add>
            <i class="bi bi-plus-lg"></i> Add Category
        </button>
    @endcan
</div>

@include('admin.include.form-errors')

<div class="admin-card">
    <div class="catalog-list-toolbar">
        <form action="{{ route('admin.categories.index') }}" method="get" class="menu-filter-form catalog-search-form" data-ajax-list-form data-target="#ajax-list-container">
            <div class="topbar-search catalog-table-search">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." autocomplete="off" data-ajax-search>
            </div>
        </form>

        @if(auth()->user()->canAny(['category.edit', 'category.delete']))
            @include('admin.include.bulk-actions', [
                'formId' => 'categoryBulkForm',
                'action' => route('admin.categories.bulk-action'),
                'canEdit' => auth()->user()->can('category.edit'),
                'canDelete' => auth()->user()->can('category.delete'),
            ])
        @endif
    </div>

    <div id="ajax-list-container">
        @include('admin.category.partials.table')
    </div>
</div>

@if(auth()->user()->canAny(['category.create', 'category.edit']))
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true" data-store-url="{{ route('admin.categories.store') }}" data-update-url="{{ route('admin.categories.update', '__ID__') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" disabled data-method-input>

                    <div class="form-group-admin">
                        <label class="form-label-admin">Category Name</label>
                        <input type="text" name="name" class="form-control-admin" value="{{ old('name') }}" placeholder="e.g. Pizza" required>
                    </div>

                    <div class="form-group-admin">
                        <label class="form-label-admin">Category Image</label>
                        <div class="upload-dropzone">
                            <img src="" alt="Category preview" class="upload-preview" data-category-preview>
                            <i class="bi bi-cloud-arrow-up"></i>
                            <div class="upload-dropzone-text">Click or drag an image to upload</div>
                            <div class="upload-dropzone-hint">PNG or JPG, up to 3MB</div>
                            <input type="file" name="image" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group-admin">
                        <label class="form-label-admin">Description (Optional)</label>
                        <textarea name="description" class="form-control-admin" rows="3" placeholder="Short description shown on the menu page">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group-admin d-flex align-items-center justify-content-between">
                        <label class="form-label-admin mb-0">Show On Website</label>
                        <label class="form-switch-admin">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="switch-track"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="categoryForm" class="btn-admin-primary"><i class="bi bi-check-lg"></i> <span data-save-label>Save Category</span></button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
(function () {
    var modalElement = document.getElementById('categoryModal');
    if (!modalElement) return;
    var form = document.getElementById('categoryForm');
    var methodInput = form.querySelector('[data-method-input]');
    var preview = form.querySelector('[data-category-preview]');
    var fileInput = form.querySelector('input[name="image"]');
    var title = modalElement.querySelector('.modal-title');
    var saveLabel = modalElement.querySelector('[data-save-label]');

    function prepareAdd() {
        form.reset();
        form.action = modalElement.dataset.storeUrl;
        methodInput.disabled = true;
        title.textContent = 'Add Category';
        saveLabel.textContent = 'Save Category';
        preview.src = '';
        preview.style.display = 'none';
        fileInput.value = '';
    }

    function prepareEdit(button) {
        form.reset();
        form.action = modalElement.dataset.updateUrl.replace('__ID__', button.dataset.id);
        methodInput.disabled = false;
        form.elements.name.value = button.dataset.name || '';
        form.elements.description.value = button.dataset.description || '';
        form.elements.is_active.checked = button.dataset.active === '1';
        title.textContent = 'Edit Category';
        saveLabel.textContent = 'Update Category';
        fileInput.value = '';
        if (button.dataset.image) {
            preview.src = button.dataset.image;
            preview.style.display = 'block';
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    document.addEventListener('click', function (event) {
        var addButton = event.target.closest('[data-category-add]');
        if (addButton) prepareAdd();
        var editButton = event.target.closest('[data-category-edit]');
        if (editButton) prepareEdit(editButton);
    });

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(modalElement).show();
        });
    @endif
})();
</script>
@endpush
