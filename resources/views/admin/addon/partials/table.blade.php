<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th><input type="checkbox" class="table-checkbox" data-select-all {{ $addons->isEmpty() ? 'disabled' : '' }}></th>
                <th>Add-On Name</th>
                <th>Price</th>
                <th>Used In</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($addons as $addon)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $addon->id }}" form="addonBulkForm" class="table-checkbox"></td>
                    <td class="table-item-name">{{ $addon->name }}</td>
                    <td>+TK {{ number_format((float) $addon->price, ((float) $addon->price == floor((float) $addon->price)) ? 0 : 2) }}</td>
                    <td>{{ $addon->menu_items_count }} items</td>
                    <td><span class="badge-status {{ $addon->is_active ? 'is-active' : 'is-inactive' }}">{{ $addon->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="table-actions">
                            @can('addon.edit')
                                <button type="button" class="table-action-btn" data-bs-toggle="modal" data-bs-target="#addonModal" data-addon-edit
                                    data-id="{{ $addon->id }}" data-name="{{ $addon->name }}" data-price="{{ $addon->price }}"
                                    data-active="{{ $addon->is_active ? 1 : 0 }}" title="Edit"><i class="bi bi-pencil"></i></button>
                            @endcan
                            @can('addon.delete')
                                <form action="{{ route('admin.addons.destroy', $addon) }}" method="post" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="table-action-btn danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="table-empty-row">
                    <td colspan="6">
                        <div class="empty-state table-empty-state">
                            <i class="bi bi-magic"></i>
                            <h3>No add-ons found</h3>
                            <p>Create extras that customers can add to menu items.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $addons->links() }}
