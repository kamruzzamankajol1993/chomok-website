@extends('admin.master.master')

@section('title', 'Homepage Content')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Homepage Content</span></div>
        <h1 class="page-title">Homepage Content</h1>
        <p class="page-subtitle">Manage the hero slider, about section, lower banner, and hungry section shown on {{ $restaurantSetting?->restaurant_name ?? 'the restaurant' }}'s homepage.</p>
    </div>
    @if($restaurantSetting?->website_url)
        <a href="{{ $restaurantSetting->website_url }}" target="_blank" rel="noopener" class="btn-admin-outline"><i class="bi bi-box-arrow-up-right"></i> View Homepage</a>
    @endif
</div>

@include('admin.include.form-errors')

<ul class="nav admin-tabs" id="homepageTabs" role="tablist">
    <li class="nav-item" role="presentation"><button class="nav-link active" id="slider-tab" data-bs-toggle="tab" data-bs-target="#slider-pane" type="button" role="tab">Hero Slider</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about-pane" type="button" role="tab">About Section</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner-pane" type="button" role="tab">Lower Banner</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" id="hungry-tab" data-bs-toggle="tab" data-bs-target="#hungry-pane" type="button" role="tab">Hungry Section</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="slider-pane" role="tabpanel">
        <div class="admin-card">
            <div class="admin-card-header">
                <div><h2 class="admin-card-title">Hero Slider</h2><p class="form-section-hint mb-0">The auto-rotating banner at the top of the homepage.</p></div>
                @can('website-content.edit')
                    <button type="button" class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#slideModal" data-slide-add><i class="bi bi-plus-lg"></i> Add Slide</button>
                @endcan
            </div>

            @forelse($slides as $slide)
                <div class="cms-item-row">
                    <div class="cms-item-thumb d-flex align-items-center justify-content-center fs-4">
                        @if($slide->image)<img src="{{ asset($slide->image) }}" alt="{{ $slide->title_line_1 }}">@else 🍕 @endif
                    </div>
                    <div class="cms-item-body">
                        <div class="cms-item-tag">Slide {{ $loop->iteration }} · {{ $slide->is_active ? 'Active' : 'Inactive' }}</div>
                        <div class="cms-item-title">{{ $slide->title_line_1 }} {{ $slide->title_line_2 }}</div>
                        <div class="cms-item-sub">{{ $slide->subtext }}</div>
                    </div>
                    @can('website-content.edit')
                        <div class="table-actions">
                            <button type="button" class="table-action-btn" data-bs-toggle="modal" data-bs-target="#slideModal" data-slide-edit
                                data-id="{{ $slide->id }}" data-image="{{ $slide->image ? asset($slide->image) : '' }}"
                                data-eyebrow="{{ $slide->eyebrow_text }}" data-title-one="{{ $slide->title_line_1 }}" data-title-two="{{ $slide->title_line_2 }}"
                                data-subtext="{{ $slide->subtext }}" data-button-one-text="{{ $slide->button_1_text }}" data-button-one-link="{{ $slide->button_1_link }}"
                                data-button-two-text="{{ $slide->button_2_text }}" data-button-two-link="{{ $slide->button_2_link }}"
                                data-sort="{{ $slide->sort_order }}" data-active="{{ $slide->is_active ? 1 : 0 }}" title="Edit"><i class="bi bi-pencil"></i></button>
                            <form action="{{ route('admin.website.home.slides.destroy', $slide) }}" method="post" class="delete-form d-inline">@csrf @method('DELETE')<button type="submit" class="table-action-btn danger" title="Delete"><i class="bi bi-trash"></i></button></form>
                        </div>
                    @endcan
                </div>
            @empty
                <div class="empty-table-state py-5 text-center"><i class="bi bi-images fs-1"></i><p class="mb-0 mt-2">No homepage slides added yet.</p></div>
            @endforelse
        </div>
    </div>

    <div class="tab-pane fade" id="about-pane" role="tabpanel">
        <form action="{{ route('admin.website.home.about.update') }}" method="post" enctype="multipart/form-data" data-form-loader data-loader-text="Saving homepage about section...">
            @csrf @method('PUT')
            <div class="admin-card form-loader-surface">
                <div class="admin-card-header"><div><h2 class="admin-card-title">About Section</h2><p class="form-section-hint mb-0">The "Experience Culinary Excellence" section on the homepage.</p></div></div>
                <div class="form-group-admin"><label class="form-label-admin">Badge Text</label><input type="text" name="about_badge_text" class="form-control-admin" value="{{ old('about_badge_text', $content->about_badge_text) }}"></div>
                <div class="form-row-admin">
                    <div class="form-group-admin"><label class="form-label-admin">Heading Word (Line 1)</label><input type="text" name="about_heading_line_1" class="form-control-admin" value="{{ old('about_heading_line_1', $content->about_heading_line_1) }}"></div>
                    <div class="form-group-admin"><label class="form-label-admin">Heading Line 2</label><input type="text" name="about_heading_line_2" class="form-control-admin" value="{{ old('about_heading_line_2', $content->about_heading_line_2) }}"></div>
                </div>
                <div class="form-row-admin">
                    <div class="form-group-admin"><label class="form-label-admin">Pill Image (Left)</label><div class="upload-dropzone"><img id="homeAboutPillPreview" src="{{ $content->about_pill_image ? asset($content->about_pill_image) : '' }}" alt="" class="upload-preview" style="{{ $content->about_pill_image ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag to upload</div><input type="file" name="about_pill_image" accept="image/*" class="image-preview-input" data-preview="#homeAboutPillPreview"></div></div>
                    <div class="form-group-admin"><label class="form-label-admin">Circle Image (Right)</label><div class="upload-dropzone"><img id="homeAboutCirclePreview" src="{{ $content->about_circle_image ? asset($content->about_circle_image) : '' }}" alt="" class="upload-preview" style="{{ $content->about_circle_image ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag to upload</div><input type="file" name="about_circle_image" accept="image/*" class="image-preview-input" data-preview="#homeAboutCirclePreview"></div></div>
                </div>
                <div class="form-group-admin"><label class="form-label-admin">Paragraph Text</label><textarea name="about_paragraph_text" class="form-control-admin" rows="2">{{ old('about_paragraph_text', $content->about_paragraph_text) }}</textarea></div>
                <div class="form-row-admin">
                    <div class="form-group-admin"><label class="form-label-admin">Button Text</label><input type="text" name="about_button_text" class="form-control-admin" value="{{ old('about_button_text', $content->about_button_text) }}"></div>
                    <div class="form-group-admin"><label class="form-label-admin">Trust Badge</label><input type="text" name="about_trust_badge" class="form-control-admin" value="{{ old('about_trust_badge', $content->about_trust_badge) }}"></div>
                </div>
                @can('website-content.edit')<div class="form-actions-admin"><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save About Section</button></div>@endcan
            </div>
        </form>
    </div>

    <div class="tab-pane fade" id="banner-pane" role="tabpanel">
        <div class="admin-card">
            <div class="admin-card-header">
                <div>
                    <h2 class="admin-card-title">Lower Banner</h2>
                    <p class="form-section-hint mb-0">Fixed 5-image layout. Row 1: 606×258px and 857×258px. Row 2: three images, each 478×262px.</p>
                </div>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Image</th><th>Order</th><th>Status</th><th>Link</th></tr></thead>
                    <tbody>
                    @forelse($promoCards as $promo)
                        @php
                            [$bannerWidth, $bannerHeight] = match ((int) $promo->banner_slot) {
                                1 => [606, 258],
                                2 => [857, 258],
                                default => [478, 262],
                            };
                            $rowLabel = $promo->banner_slot <= 2 ? 'Row 1' : 'Row 2';
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="table-item-img">@if($promo->image)<img src="{{ asset($promo->image) }}" alt="Lower banner slot {{ $promo->banner_slot }}">@else <i class="bi bi-image"></i> @endif</div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="table-item-name">{{ $rowLabel }} · Image {{ $promo->banner_slot <= 2 ? $promo->banner_slot : $promo->banner_slot - 2 }}</div>
                                            @can('website-content.edit')
                                                <button type="button" class="table-action-btn" data-bs-toggle="modal" data-bs-target="#promoModal" data-promo-edit
                                                    data-id="{{ $promo->id }}" data-slot="{{ $promo->banner_slot }}" data-image="{{ $promo->image ? asset($promo->image) : '' }}"
                                                    data-link="{{ $promo->link }}" data-sort="{{ $promo->sort_order }}" data-active="{{ $promo->is_active ? 1 : 0 }}"
                                                    data-width="{{ $bannerWidth }}" data-height="{{ $bannerHeight }}" title="Edit"><i class="bi bi-pencil"></i></button>
                                            @endcan
                                        </div>
                                        <div class="form-section-hint mb-0">Recommended size: {{ $bannerWidth }}×{{ $bannerHeight }}px</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $promo->sort_order }}</td>
                            <td><span class="badge-status {{ $promo->is_active ? 'is-active' : 'is-inactive' }}">{{ $promo->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>{{ $promo->link ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="empty-table-state py-5 text-center">Lower banner slots are not available. Please run the latest migration.</div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="hungry-pane" role="tabpanel">
        <form action="{{ route('admin.website.home.hungry.update') }}" method="post" enctype="multipart/form-data" data-form-loader data-loader-text="Saving hungry section...">
            @csrf @method('PUT')
            <div class="admin-card form-loader-surface">
                <div class="admin-card-header"><div><h2 class="admin-card-title">Hungry Section</h2><p class="form-section-hint mb-0">Manage the two images and text content used in the homepage hungry section.</p></div></div>
                <div class="form-row-admin">
                    <div class="form-group-admin">
                        <label class="form-label-admin">Left Image</label>
                        <div class="upload-dropzone"><img id="hungryLeftPreview" src="{{ $content->hungry_left_image ? asset($content->hungry_left_image) : '' }}" alt="" class="upload-preview" style="{{ $content->hungry_left_image ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag to upload</div><input type="file" name="hungry_left_image" accept="image/*" class="image-preview-input" data-preview="#hungryLeftPreview"></div>
                    </div>
                    <div class="form-group-admin">
                        <label class="form-label-admin">Right Image</label>
                        <div class="upload-dropzone"><img id="hungryRightPreview" src="{{ $content->hungry_right_image ? asset($content->hungry_right_image) : '' }}" alt="" class="upload-preview" style="{{ $content->hungry_right_image ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag to upload</div><input type="file" name="hungry_right_image" accept="image/*" class="image-preview-input" data-preview="#hungryRightPreview"></div>
                    </div>
                </div>
                <div class="form-row-admin">
                    <div class="form-group-admin"><label class="form-label-admin">Line One</label><input type="text" name="hungry_line_one" class="form-control-admin" value="{{ old('hungry_line_one', $content->hungry_line_one) }}"></div>
                    <div class="form-group-admin"><label class="form-label-admin">Line Two</label><input type="text" name="hungry_line_two" class="form-control-admin" value="{{ old('hungry_line_two', $content->hungry_line_two) }}"></div>
                </div>
                <div class="form-group-admin"><label class="form-label-admin">Subtext</label><textarea name="hungry_subtext" class="form-control-admin" rows="3">{{ old('hungry_subtext', $content->hungry_subtext) }}</textarea></div>
                <div class="form-group-admin"><label class="form-label-admin">Button Text</label><input type="text" name="hungry_button_text" class="form-control-admin" value="{{ old('hungry_button_text', $content->hungry_button_text) }}"></div>
                @can('website-content.edit')<div class="form-actions-admin"><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save Hungry Section</button></div>@endcan
            </div>
        </form>
    </div>
</div>

@can('website-content.edit')
<div class="modal fade" id="slideModal" tabindex="-1" aria-hidden="true" data-store-url="{{ route('admin.website.home.slides.store') }}" data-update-url="{{ route('admin.website.home.slides.update', '__ID__') }}">
    <div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Slide</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
        <form id="slideForm" method="post" enctype="multipart/form-data">@csrf <input type="hidden" name="_method" value="PUT" disabled data-method-input>
            <div class="form-group-admin"><label class="form-label-admin">Slide Image</label><div class="upload-dropzone"><img id="slidePreview" class="upload-preview" alt=""><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div><div class="upload-dropzone-hint">Wide landscape photo recommended</div><input type="file" name="image" accept="image/*" class="image-preview-input" data-preview="#slidePreview"></div></div>
            <div class="form-group-admin"><label class="form-label-admin">Eyebrow Text</label><input type="text" name="eyebrow_text" class="form-control-admin"></div>
            <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Title Line 1</label><input type="text" name="title_line_1" class="form-control-admin" required></div><div class="form-group-admin"><label class="form-label-admin">Title Line 2</label><input type="text" name="title_line_2" class="form-control-admin"></div></div>
            <div class="form-group-admin"><label class="form-label-admin">Subtext</label><textarea name="subtext" class="form-control-admin" rows="2"></textarea></div>
            <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Button 1 Text</label><input type="text" name="button_1_text" class="form-control-admin"></div><div class="form-group-admin"><label class="form-label-admin">Button 1 Link</label><input type="text" name="button_1_link" class="form-control-admin"></div></div>
            <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Button 2 Text</label><input type="text" name="button_2_text" class="form-control-admin"></div><div class="form-group-admin"><label class="form-label-admin">Button 2 Link</label><input type="text" name="button_2_link" class="form-control-admin"></div></div>
            <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Sort Order</label><input type="number" name="sort_order" class="form-control-admin" min="0" value="0"></div><div class="form-group-admin d-flex align-items-center justify-content-between"><label class="form-label-admin mb-0">Active</label><label class="form-switch-admin"><input type="checkbox" name="is_active" value="1" checked><span class="switch-track"></span></label></div></div>
        </form>
    </div><div class="modal-footer"><button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" form="slideForm" class="btn-admin-primary"><i class="bi bi-check-lg"></i> <span data-save-label>Save Slide</span></button></div></div></div>
</div>

<div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true" data-update-url="{{ route('admin.website.home.promos.update', '__ID__') }}">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Lower Banner</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
        <form id="promoForm" method="post" enctype="multipart/form-data">@csrf @method('PUT')
            <div class="form-group-admin"><label class="form-label-admin">Banner Image</label><div class="upload-dropzone"><img id="promoPreview" class="upload-preview" alt=""><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div><div class="upload-dropzone-hint" id="promoSizeHint">Recommended image size</div><input type="file" name="image" accept="image/*" class="image-preview-input" data-preview="#promoPreview"></div></div>
            <div class="form-group-admin"><label class="form-label-admin">Link</label><input type="text" name="link" class="form-control-admin" placeholder="https://example.com/menu"></div>
            <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Order</label><input type="number" name="sort_order" class="form-control-admin" min="1" required></div><div class="form-group-admin d-flex align-items-center justify-content-between"><label class="form-label-admin mb-0">Status</label><label class="form-switch-admin"><input type="checkbox" name="is_active" value="1" checked><span class="switch-track"></span></label></div></div>
        </form>
    </div><div class="modal-footer"><button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" form="promoForm" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Update Banner</button></div></div></div>
</div>
@endcan
@endsection

@push('scripts')
<script>
(function () {
    function setPreview(preview, url) { preview.src = url || ''; preview.style.display = url ? 'block' : 'none'; }

    var slideModal = document.getElementById('slideModal');
    if (slideModal) {
        var sf = document.getElementById('slideForm'), sm = sf.querySelector('[data-method-input]'), sp = document.getElementById('slidePreview');
        document.addEventListener('click', function (e) {
            var add = e.target.closest('[data-slide-add]'), edit = e.target.closest('[data-slide-edit]');
            if (add) { sf.reset(); sf.action = slideModal.dataset.storeUrl; sm.disabled = true; slideModal.querySelector('.modal-title').textContent='Add Slide'; slideModal.querySelector('[data-save-label]').textContent='Save Slide'; setPreview(sp, ''); }
            if (edit) { sf.reset(); sf.action=slideModal.dataset.updateUrl.replace('__ID__', edit.dataset.id); sm.disabled=false; sf.elements.eyebrow_text.value=edit.dataset.eyebrow||''; sf.elements.title_line_1.value=edit.dataset.titleOne||''; sf.elements.title_line_2.value=edit.dataset.titleTwo||''; sf.elements.subtext.value=edit.dataset.subtext||''; sf.elements.button_1_text.value=edit.dataset.buttonOneText||''; sf.elements.button_1_link.value=edit.dataset.buttonOneLink||''; sf.elements.button_2_text.value=edit.dataset.buttonTwoText||''; sf.elements.button_2_link.value=edit.dataset.buttonTwoLink||''; sf.elements.sort_order.value=edit.dataset.sort||0; sf.elements.is_active.checked=edit.dataset.active==='1'; slideModal.querySelector('.modal-title').textContent='Edit Slide'; slideModal.querySelector('[data-save-label]').textContent='Update Slide'; setPreview(sp, edit.dataset.image); }
        });
    }

    var promoModal = document.getElementById('promoModal');
    if (promoModal) {
        var pf = document.getElementById('promoForm'), pp = document.getElementById('promoPreview'), sizeHint = document.getElementById('promoSizeHint');
        document.addEventListener('click', function (e) {
            var edit = e.target.closest('[data-promo-edit]');
            if (!edit) return;
            pf.reset();
            pf.action = promoModal.dataset.updateUrl.replace('__ID__', edit.dataset.id);
            pf.elements.link.value = edit.dataset.link || '';
            pf.elements.sort_order.value = edit.dataset.sort || 1;
            pf.elements.is_active.checked = edit.dataset.active === '1';
            promoModal.querySelector('.modal-title').textContent = 'Edit Lower Banner · Slot ' + edit.dataset.slot;
            sizeHint.textContent = 'Recommended size: ' + edit.dataset.width + '×' + edit.dataset.height + 'px';
            setPreview(pp, edit.dataset.image);
        });
    }

    if (window.location.hash) {
        var trigger = document.querySelector('[data-bs-target="' + window.location.hash + '"]');
        if (trigger) bootstrap.Tab.getOrCreateInstance(trigger).show();
    }
})();
</script>
@endpush
