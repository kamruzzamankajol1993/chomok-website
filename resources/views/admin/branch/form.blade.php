@include('admin.include.form-errors')
<div class="admin-card form-surface-card choices-overflow-card">
    <div class="form-panel-header">
        <div class="form-panel-icon"><i class="bi bi-shop"></i></div>
        <div><h2>Branch Information</h2><p>Configure branch identity, contact information and operating status.</p></div>
    </div>
    <div class="form-grid-two">
        <div class="form-group-admin"><label class="form-label-admin">Branch Name <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-building"></i><input type="text" name="name" class="form-control-admin" value="{{ old('name', $branch->name ?? '') }}" required></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Branch Code</label><div class="form-control-icon-wrap"><i class="bi bi-upc"></i><input type="text" name="code" class="form-control-admin" value="{{ old('code', $branch->code ?? '') }}" placeholder="e.g. HO"></div></div>
    </div>
    <div class="form-group-admin"><label class="form-label-admin">Address <span class="required">*</span></label><textarea name="address" rows="4" class="form-control-admin" placeholder="Enter the complete branch address" required>{{ old('address', $branch->address ?? '') }}</textarea></div>
    <div class="form-grid-two">
        <div class="form-group-admin"><label class="form-label-admin">City</label><div class="form-control-icon-wrap"><i class="bi bi-geo-alt"></i><input type="text" name="city" class="form-control-admin" value="{{ old('city', $branch->city ?? '') }}"></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Phone</label><div class="form-control-icon-wrap"><i class="bi bi-telephone"></i><input type="text" name="phone" class="form-control-admin" value="{{ old('phone', $branch->phone ?? '') }}"></div></div>
    </div>
    <div class="form-grid-two align-items-end">
        <div class="form-group-admin mb-0"><label class="form-label-admin">Email</label><div class="form-control-icon-wrap"><i class="bi bi-envelope"></i><input type="email" name="email" class="form-control-admin" value="{{ old('email', $branch->email ?? '') }}"></div></div>
        <div class="form-group-admin mb-0"><label class="form-label-admin">Status</label><select name="status" class="form-control-admin choices-select"><option value="active" @selected(old('status', $branch->status ?? 'active')==='active')>Active</option><option value="inactive" @selected(old('status', $branch->status ?? 'active')==='inactive')>Inactive</option></select></div>
    </div>
    <div class="form-grid-two mt-4">
        <div class="form-group-admin">
            <label class="form-label-admin">Image</label>
            <div id="branch-image-preview-wrap" class="mb-2" @if(!isset($branch) || !$branch->image) style="display:none;" @endif>
                <img id="branch-image-preview"
                     src="{{ isset($branch) && $branch->image ? asset($branch->image) : '' }}"
                     alt="Branch image preview"
                     style="width:160px;height:105px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb;background:#f8fafc;">
            </div>
            <input type="file" id="branch-image-input" name="image" accept="image/*" class="form-control-admin">
            <div class="form-help mt-1">Selected image preview will appear above before saving.</div>
        </div>
        <div class="form-group-admin">
            <label class="form-label-admin">Google Map Link</label>
            <div class="form-control-icon-wrap"><i class="bi bi-map"></i><input type="url" name="google_map_link" class="form-control-admin" value="{{ old('google_map_link', $branch->google_map_link ?? '') }}" placeholder="https://maps.google.com/... or https://maps.app.goo.gl/..."></div>
        </div>
    </div>
    <div class="form-group-admin mt-4">
        <label class="form-label-admin">Map iFrame Code</label>
        <textarea name="map_iframe" rows="5" class="form-control-admin" placeholder='Paste Google Maps embed iframe code here, e.g. <iframe src="https://www.google.com/maps/embed?..." ...></iframe>'>{{ old('map_iframe', $branch->map_iframe ?? '') }}</textarea>
        <div class="form-help mt-1">Paste the complete iframe code copied from Google Maps (Share > Embed a map > Copy HTML). The saved iframe will be shown directly in the website home page section “Our Current Office &amp; Outlets”. If left empty, the website will continue using the branch address as the map fallback.</div>
    </div>
    <div class="form-group-admin d-flex align-items-center justify-content-between switch-row mt-4 mb-0"><div><label class="form-label-admin mb-0">Accepting Orders</label><div class="form-help">Turn this off to pause new orders for this branch.</div></div><label class="form-switch-admin"><input type="checkbox" name="accepting_orders" value="1" @checked(old('accepting_orders', $branch->accepting_orders ?? true))><span class="switch-track"></span></label></div>
    <div class="form-actions-admin"><a href="{{ route('admin.branches.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Cancel</a><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> {{ $submitLabel }}</button></div>
</div>

<script>
(function () {
    const input = document.getElementById('branch-image-input');
    const preview = document.getElementById('branch-image-preview');
    const previewWrap = document.getElementById('branch-image-preview-wrap');

    if (!input || !preview || !previewWrap) return;

    input.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            preview.src = event.target.result;
            previewWrap.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
})();
</script>
