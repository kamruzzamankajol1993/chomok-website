@php
    $isEdit = filled($menuItem);
    $priceRows = old('prices', $isEdit
        ? $menuItem->prices->map(fn ($price) => [
            'size_label' => $price->size_label,
            'price' => $price->price,
            'discount_price' => $price->discount_price,
            'addons' => $price->variationAddons->map(fn ($addon) => [
                'name' => $addon->name,
                'description' => $addon->description,
                'price' => $addon->price,
            ])->all(),
        ])->all()
        : [
            ['size_label' => '', 'price' => '', 'discount_price' => '', 'addons' => []],
            ['size_label' => '', 'price' => '', 'discount_price' => '', 'addons' => []],
        ]);
    $selectedAddons = collect(old('addon_ids', $isEdit ? $menuItem->addons->modelKeys() : []))->map(fn ($id) => (int) $id)->all();
    $selectedBranches = collect(old('branch_ids', $isEdit ? $menuItem->branches->modelKeys() : $branches->modelKeys()))->map(fn ($id) => (int) $id)->all();
    $mainImageValue = old('main_image_value', $isEdit && $menuItem->images->firstWhere('is_main') ? 'existing:'.$menuItem->images->firstWhere('is_main')->id : '');
@endphp

<div class="page-header">
    <div>
        <div class="breadcrumb-admin">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> /
            <a href="{{ route('admin.menu-items.index') }}">Menu Items</a> /
            <span class="current">{{ $isEdit ? 'Edit Item' : 'Add New Item' }}</span>
        </div>
        <h1 class="page-title">{{ $isEdit ? 'Edit Menu Item' : 'Add New Menu Item' }}</h1>
        <p class="page-subtitle">{{ $isEdit ? 'Update the item details while keeping the original menu design.' : 'Fill in the details below to add a new dish to your menu.' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.menu-items.index') }}" class="btn-admin-outline">Cancel</a>
        <button type="submit" form="menuItemForm" class="btn-admin-primary"><i class="bi bi-check-lg"></i> {{ $isEdit ? 'Update Item' : 'Save Item' }}</button>
    </div>
</div>

@include('admin.include.form-errors')

<form id="menuItemForm" action="{{ $isEdit ? route('admin.menu-items.update', $menuItem) : route('admin.menu-items.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif
    <input type="hidden" name="main_image_value" id="mainImageValue" value="{{ $mainImageValue }}">
    <div id="removedImageInputs"></div>

    <div class="form-page-layout">
        <div class="form-page-main">
            <div class="admin-card mb-3">
                <h2 class="form-section-title">Photos</h2>
                <p class="form-section-hint">Upload one or more photos. Click "Set as Main" on the photo you want shown first &mdash; that's the one used across the website.</p>

                <div class="upload-dropzone">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <div class="upload-dropzone-text">Click or drag images to upload</div>
                    <div class="upload-dropzone-hint">PNG with transparent background recommended, up to 3MB each</div>
                    <input type="file" name="images[]" accept="image/*" multiple id="multiImageInput">
                </div>

                <div class="image-gallery-grid" id="imageGalleryGrid">
                    @if($isEdit)
                        @foreach($menuItem->images as $image)
                            <div class="gallery-thumb {{ $image->is_main ? 'is-main' : '' }}" data-image-kind="existing" data-image-id="{{ $image->id }}" data-main-value="existing:{{ $image->id }}">
                                <img src="{{ asset($image->image) }}" alt="{{ $menuItem->name }} photo">
                                <button type="button" class="gallery-thumb-remove" title="Remove">&times;</button>
                                <button type="button" class="gallery-thumb-main-btn">{{ $image->is_main ? 'Main' : 'Set as Main' }}</button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <p class="gallery-empty-hint" id="galleryEmptyHint" style="{{ $isEdit && $menuItem->images->isNotEmpty() ? 'display:none' : '' }}">No photos uploaded yet.</p>
            </div>

            <div class="admin-card mb-3">
                <h2 class="form-section-title">Basic Information</h2>
                <p class="form-section-hint">The name and description shown to customers.</p>

                <div class="form-group-admin">
                    <label class="form-label-admin">Item Name</label>
                    <input type="text" name="name" class="form-control-admin" value="{{ old('name', $menuItem?->name) }}" placeholder="e.g. Margherita" required>
                </div>

                <div class="form-row-admin">
                    <div class="form-group-admin">
                        <label class="form-label-admin">Category</label>
                        <select name="category_id" id="menuCategorySelect" class="form-control-admin" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) old('category_id', $menuItem?->category_id) === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-admin">
                        <label class="form-label-admin">Subcategory</label>
                        <select name="subcategory_id" id="menuSubcategorySelect" class="form-control-admin">
                            <option value="">Select Subcategory</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}" {{ (string) old('subcategory_id', $menuItem?->subcategory_id) === (string) $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group-admin">
                    <label class="form-label-admin">Description (Optional)</label>
                    <textarea name="description" class="form-control-admin" rows="2" placeholder="e.g. Sauce &amp; Cheese.">{{ old('description', $menuItem?->description) }}</textarea>
                </div>
            </div>

            <div class="admin-card">
                <h2 class="form-section-title">Pricing</h2>
                <p class="form-section-hint">Add one row per size. Use a single row if the item has only one price.</p>

                <div id="priceRows">
                    @foreach($priceRows as $index => $price)
                        @php($variationAddonRows = $price['addons'] ?? [])
                        <div class="price-row border rounded p-3 mb-3" data-price-index="{{ $index }}" data-next-addon-index="{{ count($variationAddonRows) }}">
                            <div class="form-row-admin mb-2">
                                <input type="text" name="prices[{{ $index }}][size_label]" class="form-control-admin" value="{{ $price['size_label'] ?? '' }}" placeholder="Size label (e.g. 6 Inch)">
                                <input type="number" name="prices[{{ $index }}][price]" class="form-control-admin" value="{{ $price['price'] ?? '' }}" placeholder="Regular Price (TK)" step="0.01" min="0" required>
                                <input type="number" name="prices[{{ $index }}][discount_price]" class="form-control-admin" value="{{ $price['discount_price'] ?? '' }}" placeholder="Discount Price (TK)" step="0.01" min="0">
                                <button type="button" class="table-action-btn danger flex-shrink-0" title="Remove size" data-remove-price-row><i class="bi bi-x-lg"></i></button>
                            </div>

                            <div class="mt-2">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                    <div>
                                        <strong>Variation Add-Ons <span class="text-muted fw-normal">(Optional)</span></strong>
                                        <div class="form-help">These add-ons are shown only when this size/label is selected.</div>
                                    </div>
                                    <button type="button" class="btn-admin-outline btn-sm" data-add-variation-addon><i class="bi bi-plus-lg"></i> Add Add-On</button>
                                </div>
                                <div class="variation-addon-rows">
                                    @foreach($variationAddonRows as $addonIndex => $variationAddon)
                                        <div class="form-row-admin mb-2 variation-addon-row">
                                            <input type="text" name="prices[{{ $index }}][addons][{{ $addonIndex }}][name]" class="form-control-admin" value="{{ $variationAddon['name'] ?? '' }}" placeholder="Add-on name (e.g. Extra Cheese)" required>
                                            <input type="text" name="prices[{{ $index }}][addons][{{ $addonIndex }}][description]" class="form-control-admin" value="{{ $variationAddon['description'] ?? '' }}" placeholder="Description (Optional)">
                                            <input type="number" name="prices[{{ $index }}][addons][{{ $addonIndex }}][price]" class="form-control-admin" value="{{ $variationAddon['price'] ?? '' }}" placeholder="Add-on Price (TK)" step="0.01" min="0" required>
                                            <button type="button" class="table-action-btn danger flex-shrink-0" title="Remove add-on" data-remove-variation-addon><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn-admin-outline mt-1" id="addPriceRowBtn"><i class="bi bi-plus-lg"></i> Add Another Size</button>
            </div>
        </div>

        <div class="form-page-side">
            <div class="admin-card mb-3">
                <h2 class="form-section-title">Add-Ons</h2>
                <p class="form-section-hint">Extras customers can add to this item.</p>

                <div class="d-flex flex-column gap-2">
                    @forelse($addons as $addon)
                        <label class="form-check-admin">
                            <input type="checkbox" name="addon_ids[]" value="{{ $addon->id }}" {{ in_array($addon->id, $selectedAddons, true) ? 'checked' : '' }}>
                            {{ $addon->name }} (+TK {{ number_format((float) $addon->price, ((float) $addon->price == floor((float) $addon->price)) ? 0 : 2) }})
                        </label>
                    @empty
                        <span class="form-help">No add-ons have been created yet.</span>
                    @endforelse
                </div>
                @can('addon.view')
                    <a href="{{ route('admin.addons.index') }}" class="admin-card-link d-inline-block mt-3"><i class="bi bi-plus-circle me-1"></i>Manage Add-Ons</a>
                @endcan
            </div>

            <div class="admin-card mb-3">
                <h2 class="form-section-title">Availability</h2>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <label class="form-label-admin mb-0">Available For Order</label>
                    <label class="form-switch-admin">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $menuItem?->is_active ?? true) ? 'checked' : '' }}>
                        <span class="switch-track"></span>
                    </label>
                </div>

                <div class="form-group-admin mb-0">
                    <label class="form-label-admin">Available At Branches</label>
                    @forelse($branches as $branch)
                        <label class="form-check-admin mb-2">
                            <input type="checkbox" name="branch_ids[]" value="{{ $branch->id }}" {{ in_array($branch->id, $selectedBranches, true) ? 'checked' : '' }}>
                            {{ $branch->name }}
                        </label>
                    @empty
                        <span class="form-help">No active branch is available.</span>
                    @endforelse
                </div>
            </div>

            <button type="submit" form="menuItemForm" class="btn-admin-primary w-100 justify-content-center"><i class="bi bi-check-lg"></i> {{ $isEdit ? 'Update Item' : 'Save Item' }}</button>
        </div>
    </div>
</form>

@push('scripts')
<script>
(function () {
    var priceRows = document.getElementById('priceRows');
    var addPriceButton = document.getElementById('addPriceRowBtn');
    var priceIndex = priceRows ? priceRows.querySelectorAll('.price-row').length : 0;

    function addVariationAddonRow(priceRow) {
        var rowIndex = priceRow.dataset.priceIndex;
        var addonIndex = parseInt(priceRow.dataset.nextAddonIndex || '0', 10);
        var addonRow = document.createElement('div');
        addonRow.className = 'form-row-admin mb-2 variation-addon-row';
        addonRow.innerHTML = '<input type="text" name="prices[' + rowIndex + '][addons][' + addonIndex + '][name]" class="form-control-admin" placeholder="Add-on name (e.g. Extra Cheese)" required>'
            + '<input type="text" name="prices[' + rowIndex + '][addons][' + addonIndex + '][description]" class="form-control-admin" placeholder="Description (Optional)">'
            + '<input type="number" name="prices[' + rowIndex + '][addons][' + addonIndex + '][price]" class="form-control-admin" placeholder="Add-on Price (TK)" step="0.01" min="0" required>'
            + '<button type="button" class="table-action-btn danger flex-shrink-0" title="Remove add-on" data-remove-variation-addon><i class="bi bi-x-lg"></i></button>';
        priceRow.querySelector('.variation-addon-rows').appendChild(addonRow);
        priceRow.dataset.nextAddonIndex = String(addonIndex + 1);
    }

    if (addPriceButton) {
        addPriceButton.addEventListener('click', function () {
            var row = document.createElement('div');
            row.className = 'price-row border rounded p-3 mb-3';
            row.dataset.priceIndex = String(priceIndex);
            row.dataset.nextAddonIndex = '0';
            row.innerHTML = '<div class="form-row-admin mb-2">'
                + '<input type="text" name="prices[' + priceIndex + '][size_label]" class="form-control-admin" placeholder="Size label (e.g. 6 Inch)">'
                + '<input type="number" name="prices[' + priceIndex + '][price]" class="form-control-admin" placeholder="Regular Price (TK)" step="0.01" min="0" required>'
                + '<input type="number" name="prices[' + priceIndex + '][discount_price]" class="form-control-admin" placeholder="Discount Price (TK)" step="0.01" min="0">'
                + '<button type="button" class="table-action-btn danger flex-shrink-0" title="Remove size" data-remove-price-row><i class="bi bi-x-lg"></i></button></div>'
                + '<div class="mt-2"><div class="d-flex align-items-center justify-content-between gap-2 mb-2"><div><strong>Variation Add-Ons <span class="text-muted fw-normal">(Optional)</span></strong><div class="form-help">These add-ons are shown only when this size/label is selected.</div></div><button type="button" class="btn-admin-outline btn-sm" data-add-variation-addon><i class="bi bi-plus-lg"></i> Add Add-On</button></div><div class="variation-addon-rows"></div></div>';
            priceRows.appendChild(row);
            priceIndex++;
        });
    }

    document.addEventListener('click', function (event) {
        var addVariationAddon = event.target.closest('[data-add-variation-addon]');
        if (addVariationAddon) {
            addVariationAddonRow(addVariationAddon.closest('.price-row'));
            return;
        }

        var removeVariationAddon = event.target.closest('[data-remove-variation-addon]');
        if (removeVariationAddon) {
            removeVariationAddon.closest('.variation-addon-row').remove();
            return;
        }

        var removePrice = event.target.closest('[data-remove-price-row]');
        if (!removePrice) return;
        if (priceRows.querySelectorAll('.price-row').length <= 1) {
            Swal.fire({ icon: 'warning', title: 'One price is required' });
            return;
        }
        removePrice.closest('.price-row').remove();
    });

    var categorySelect = document.getElementById('menuCategorySelect');
    var subcategorySelect = document.getElementById('menuSubcategorySelect');
    if (categorySelect && subcategorySelect) {
        var initialSubcategoryId = subcategorySelect.value;
        var allSubcategories = Array.from(subcategorySelect.options).slice(1).map(function (option) {
            return {
                value: option.value,
                label: option.textContent,
                categoryId: option.dataset.categoryId
            };
        });

        function populateSubcategories(keepInitialSelection) {
            var categoryId = categorySelect.value;
            var selectedId = keepInitialSelection ? initialSubcategoryId : '';
            var matches = allSubcategories.filter(function (subcategory) {
                return categoryId && subcategory.categoryId === categoryId;
            });

            subcategorySelect.innerHTML = '';
            var placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = !categoryId
                ? 'Select category first'
                : (matches.length ? 'Select Subcategory' : 'No subcategory available');
            subcategorySelect.appendChild(placeholder);

            matches.forEach(function (subcategory) {
                var option = document.createElement('option');
                option.value = subcategory.value;
                option.textContent = subcategory.label;
                option.dataset.categoryId = subcategory.categoryId;
                option.selected = selectedId === subcategory.value;
                subcategorySelect.appendChild(option);
            });

            subcategorySelect.disabled = !categoryId || matches.length === 0;
            if (!matches.some(function (subcategory) { return subcategory.value === selectedId; })) {
                subcategorySelect.value = '';
            }
        }

        categorySelect.addEventListener('change', function () {
            populateSubcategories(false);
        });
        populateSubcategories(true);
    }

    var input = document.getElementById('multiImageInput');
    var grid = document.getElementById('imageGalleryGrid');
    var emptyHint = document.getElementById('galleryEmptyHint');
    var mainInput = document.getElementById('mainImageValue');
    var removedInputs = document.getElementById('removedImageInputs');
    var selectedFiles = [];

    function updateEmptyHint() {
        emptyHint.style.display = grid.children.length ? 'none' : 'block';
    }

    function rebuildFileInput() {
        var transfer = new DataTransfer();
        selectedFiles.forEach(function (file) { transfer.items.add(file); });
        input.files = transfer.files;
        grid.querySelectorAll('[data-image-kind="new"]').forEach(function (thumb, index) {
            thumb.dataset.newIndex = index;
            thumb.dataset.mainValue = 'new:' + index;
        });
        if (mainInput.value.indexOf('new:') === 0) {
            var mainThumb = grid.querySelector('.gallery-thumb.is-main[data-image-kind="new"]');
            mainInput.value = mainThumb ? mainThumb.dataset.mainValue : '';
        }
    }

    function setMainThumb(thumb) {
        grid.querySelectorAll('.gallery-thumb').forEach(function (item) {
            item.classList.remove('is-main');
            item.querySelector('.gallery-thumb-main-btn').textContent = 'Set as Main';
        });
        thumb.classList.add('is-main');
        thumb.querySelector('.gallery-thumb-main-btn').textContent = 'Main';
        mainInput.value = thumb.dataset.mainValue;
    }

    function chooseFallbackMain() {
        var current = grid.querySelector('.gallery-thumb.is-main');
        if (current) {
            mainInput.value = current.dataset.mainValue;
            return;
        }
        var first = grid.querySelector('.gallery-thumb');
        if (first) setMainThumb(first);
        else mainInput.value = '';
    }

    function addNewThumb(file, index) {
        var reader = new FileReader();
        reader.onload = function (event) {
            var thumb = document.createElement('div');
            thumb.className = 'gallery-thumb';
            thumb.dataset.imageKind = 'new';
            thumb.dataset.newIndex = index;
            thumb.dataset.mainValue = 'new:' + index;
            thumb.innerHTML = '<img src="' + event.target.result + '" alt="Item photo">'
                + '<button type="button" class="gallery-thumb-remove" title="Remove">&times;</button>'
                + '<button type="button" class="gallery-thumb-main-btn">Set as Main</button>';
            grid.appendChild(thumb);
            if (!grid.querySelector('.gallery-thumb.is-main')) setMainThumb(thumb);
            updateEmptyHint();
        };
        reader.readAsDataURL(file);
    }

    input.addEventListener('change', function () {
        var incoming = Array.from(input.files);
        if (grid.children.length + incoming.length > 8) {
            Swal.fire({ icon: 'warning', title: 'Maximum 8 images', text: 'Remove an image before adding another one.' });
            input.value = '';
            return;
        }
        incoming.forEach(function (file) {
            selectedFiles.push(file);
            addNewThumb(file, selectedFiles.length - 1);
        });
        rebuildFileInput();
    });

    grid.addEventListener('click', function (event) {
        var mainButton = event.target.closest('.gallery-thumb-main-btn');
        if (mainButton) {
            setMainThumb(mainButton.closest('.gallery-thumb'));
            return;
        }

        var removeButton = event.target.closest('.gallery-thumb-remove');
        if (!removeButton) return;
        var thumb = removeButton.closest('.gallery-thumb');
        var wasMain = thumb.classList.contains('is-main');

        if (thumb.dataset.imageKind === 'existing') {
            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'remove_image_ids[]';
            hidden.value = thumb.dataset.imageId;
            removedInputs.appendChild(hidden);
        } else {
            selectedFiles.splice(parseInt(thumb.dataset.newIndex, 10), 1);
        }

        thumb.remove();
        rebuildFileInput();
        if (wasMain) chooseFallbackMain();
        updateEmptyHint();
    });

    chooseFallbackMain();
    updateEmptyHint();
})();
</script>
@endpush
