@include('admin.include.form-errors')
<div class="admin-card form-surface-card">
    <div class="form-panel-header">
        <div class="form-panel-icon"><i class="bi bi-shield-lock"></i></div>
        <div><h2>Permission Group</h2><p>Create or update a group and its permission names.</p></div>
    </div>
    <div class="form-group-admin"><label class="form-label-admin">Group Name <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-collection"></i><input type="text" name="group" class="form-control-admin" value="{{ old('group',$group ?? '') }}" placeholder="e.g. Product Management" required></div></div>
    <div class="permission-builder-header"><div><h2 class="admin-card-title">Permission Names</h2><p class="form-help mb-0">Use the plus button to add multiple permissions under this group.</p></div><button type="button" class="btn-admin-outline" data-add-permission-row><i class="bi bi-plus-lg"></i> Add Permission</button></div>
    <div id="permission-rows" class="permission-row-list">
        @php
            $oldNames = old('permission_names');
            $rows = $oldNames ? collect($oldNames)->map(fn($name,$index)=>(object)['id'=>old('permission_ids.'.$index),'name'=>$name]) : ($permissions ?? collect([(object)['id'=>null,'name'=>'']]));
        @endphp
        @foreach($rows as $row)
        <div class="permission-builder-row">
            <input type="hidden" name="permission_ids[]" value="{{ $row->id ?? '' }}">
            <div class="form-control-icon-wrap"><i class="bi bi-key"></i><input type="text" name="permission_names[]" class="form-control-admin" value="{{ $row->name }}" placeholder="e.g. product.create" required></div>
            <button type="button" class="table-action-btn danger" data-remove-permission-row title="Remove"><i class="bi bi-trash"></i></button>
        </div>
        @endforeach
    </div>
    <template id="permission-row-template"><div class="permission-builder-row"><input type="hidden" name="permission_ids[]" value=""><div class="form-control-icon-wrap"><i class="bi bi-key"></i><input type="text" name="permission_names[]" class="form-control-admin" placeholder="e.g. product.create" required></div><button type="button" class="table-action-btn danger" data-remove-permission-row title="Remove"><i class="bi bi-trash"></i></button></div></template>
    <div class="form-actions-admin"><a href="{{ route('admin.permissions.index') }}" class="btn-admin-outline"><i class="bi bi-arrow-left"></i> Cancel</a><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> {{ $submitLabel }}</button></div>
</div>
