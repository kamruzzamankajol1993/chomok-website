@extends('admin.master.master')

@section('title', 'Add-Ons')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Add-Ons</span></div>
        <h1 class="page-title">Add-Ons</h1>
        <p class="page-subtitle">Extras customers can add to a menu item, e.g. Extra Cheese, Mushrooms.</p>
    </div>
    @can('addon.create')
        <button type="button" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addonModal" data-addon-add>
            <i class="bi bi-plus-lg"></i> Add Add-On
        </button>
    @endcan
</div>

@include('admin.include.form-errors')

<div class="admin-card">
    <div class="catalog-list-toolbar">
        <form action="{{ route('admin.addons.index') }}" method="get" class="menu-filter-form catalog-search-form" data-ajax-list-form data-target="#ajax-list-container">
            <div class="topbar-search catalog-table-search">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search add-ons..." autocomplete="off" data-ajax-search>
            </div>
        </form>

        @if(auth()->user()->canAny(['addon.edit', 'addon.delete']))
            @include('admin.include.bulk-actions', [
                'formId' => 'addonBulkForm',
                'action' => route('admin.addons.bulk-action'),
                'canEdit' => auth()->user()->can('addon.edit'),
                'canDelete' => auth()->user()->can('addon.delete'),
            ])
        @endif
    </div>

    <div id="ajax-list-container">
        @include('admin.addon.partials.table')
    </div>
</div>

@if(auth()->user()->canAny(['addon.create', 'addon.edit']))
<div class="modal fade" id="addonModal" tabindex="-1" aria-labelledby="addonModalLabel" aria-hidden="true" data-store-url="{{ route('admin.addons.store') }}" data-update-url="{{ route('admin.addons.update', '__ID__') }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addonModalLabel">Add Add-On</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addonForm" action="{{ route('admin.addons.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" disabled data-method-input>

                    <div class="form-group-admin">
                        <label class="form-label-admin">Add-On Name</label>
                        <input type="text" name="name" class="form-control-admin" value="{{ old('name') }}" placeholder="e.g. Extra Cheese" required>
                    </div>

                    <div class="form-group-admin">
                        <label class="form-label-admin">Price (TK)</label>
                        <input type="number" name="price" class="form-control-admin" value="{{ old('price') }}" placeholder="e.g. 40" step="0.01" min="0" required>
                    </div>

                    <div class="form-group-admin d-flex align-items-center justify-content-between">
                        <label class="form-label-admin mb-0">Available For Selection</label>
                        <label class="form-switch-admin">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="switch-track"></span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addonForm" class="btn-admin-primary"><i class="bi bi-check-lg"></i> <span data-save-label>Save Add-On</span></button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
(function () {
    var modalElement = document.getElementById('addonModal');
    if (!modalElement) return;
    var form = document.getElementById('addonForm');
    var methodInput = form.querySelector('[data-method-input]');
    var title = modalElement.querySelector('.modal-title');
    var saveLabel = modalElement.querySelector('[data-save-label]');

    function prepareAdd() {
        form.reset();
        form.action = modalElement.dataset.storeUrl;
        methodInput.disabled = true;
        title.textContent = 'Add Add-On';
        saveLabel.textContent = 'Save Add-On';
    }

    function prepareEdit(button) {
        form.reset();
        form.action = modalElement.dataset.updateUrl.replace('__ID__', button.dataset.id);
        methodInput.disabled = false;
        form.elements.name.value = button.dataset.name || '';
        form.elements.price.value = button.dataset.price || '';
        form.elements.is_active.checked = button.dataset.active === '1';
        title.textContent = 'Edit Add-On';
        saveLabel.textContent = 'Update Add-On';
    }

    document.addEventListener('click', function (event) {
        var addButton = event.target.closest('[data-addon-add]');
        if (addButton) prepareAdd();
        var editButton = event.target.closest('[data-addon-edit]');
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
