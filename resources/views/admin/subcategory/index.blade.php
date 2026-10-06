@extends('admin.master.master')

@section('title', 'Subcategories')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Subcategories</span></div>
        <h1 class="page-title">Subcategories</h1>
        <p class="page-subtitle">Group items within a category, e.g. Regular / Loaded / Overloaded Pizza.</p>
    </div>
    @can('subcategory.create')
        <button type="button" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#subcategoryModal" data-subcategory-add>
            <i class="bi bi-plus-lg"></i> Add Subcategory
        </button>
    @endcan
</div>

@include('admin.include.form-errors')

<div class="admin-card">
    <div class="catalog-list-toolbar">
        <form action="{{ route('admin.subcategories.index') }}" method="get" class="menu-filter-form catalog-search-form" data-ajax-list-form data-target="#ajax-list-container">
            <div class="topbar-search catalog-table-search">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search subcategories..." autocomplete="off" data-ajax-search>
            </div>
        </form>

        @if(auth()->user()->canAny(['subcategory.edit', 'subcategory.delete']))
            @include('admin.include.bulk-actions', [
                'formId' => 'subcategoryBulkForm',
                'action' => route('admin.subcategories.bulk-action'),
                'canEdit' => auth()->user()->can('subcategory.edit'),
                'canDelete' => auth()->user()->can('subcategory.delete'),
            ])
        @endif
    </div>

    <div id="ajax-list-container">
        @include('admin.subcategory.partials.table')
    </div>
</div>

@if(auth()->user()->canAny(['subcategory.create', 'subcategory.edit']))
<div class="modal fade" id="subcategoryModal" tabindex="-1" aria-labelledby="subcategoryModalLabel" aria-hidden="true" data-store-url="{{ route('admin.subcategories.store') }}" data-update-url="{{ route('admin.subcategories.update', '__ID__') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subcategoryModalLabel">Add Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="subcategoryForm" action="{{ route('admin.subcategories.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" disabled data-method-input>

                    <div class="form-group-admin">
                        <label class="form-label-admin">Subcategory Name</label>
                        <input type="text" name="name" class="form-control-admin" value="{{ old('name') }}" placeholder="e.g. Regular Pizza" required>
                    </div>

                    <div class="form-group-admin">
                        <label class="form-label-admin">Parent Category</label>
                        <select name="category_id" class="form-control-admin" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
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
                <button type="submit" form="subcategoryForm" class="btn-admin-primary"><i class="bi bi-check-lg"></i> <span data-save-label>Save Subcategory</span></button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
(function () {
    var modalElement = document.getElementById('subcategoryModal');
    if (!modalElement) return;
    var form = document.getElementById('subcategoryForm');
    var methodInput = form.querySelector('[data-method-input]');
    var title = modalElement.querySelector('.modal-title');
    var saveLabel = modalElement.querySelector('[data-save-label]');
    var categorySelect = form.elements.category_id;

    function setCategory(value) {
        categorySelect.value = value || '';
        categorySelect.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function prepareAdd() {
        form.reset();
        form.action = modalElement.dataset.storeUrl;
        methodInput.disabled = true;
        title.textContent = 'Add Subcategory';
        saveLabel.textContent = 'Save Subcategory';
        setCategory('');
    }

    function prepareEdit(button) {
        form.reset();
        form.action = modalElement.dataset.updateUrl.replace('__ID__', button.dataset.id);
        methodInput.disabled = false;
        form.elements.name.value = button.dataset.name || '';
        form.elements.is_active.checked = button.dataset.active === '1';
        setCategory(button.dataset.categoryId);
        title.textContent = 'Edit Subcategory';
        saveLabel.textContent = 'Update Subcategory';
    }

    document.addEventListener('click', function (event) {
        var addButton = event.target.closest('[data-subcategory-add]');
        if (addButton) prepareAdd();
        var editButton = event.target.closest('[data-subcategory-edit]');
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
