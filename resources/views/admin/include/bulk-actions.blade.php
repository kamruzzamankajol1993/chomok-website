<form id="{{ $formId }}" action="{{ $action }}" method="post" class="bulk-action-form" data-bulk-action-form>
    @csrf
    <select name="bulk_action" class="form-control-admin bulk-action-select" required>
        <option value="">Bulk Action</option>
        @if($canEdit)
            <option value="active">Set Active</option>
            <option value="inactive">Set Inactive</option>
        @endif
        @if($canDelete)
            <option value="delete">Delete Selected</option>
        @endif
    </select>
    <button type="submit" class="btn-admin-outline"><i class="bi bi-check2"></i> Apply</button>
</form>
