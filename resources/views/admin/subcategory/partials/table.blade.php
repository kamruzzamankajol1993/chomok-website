<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th><input type="checkbox" class="table-checkbox" data-select-all {{ $subcategories->isEmpty() ? 'disabled' : '' }}></th>
                <th>Subcategory</th>
                <th>Parent Category</th>
                <th>Items</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subcategories as $subcategory)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $subcategory->id }}" form="subcategoryBulkForm" class="table-checkbox"></td>
                    <td class="table-item-name">{{ $subcategory->name }}</td>
                    <td>
                        <div class="table-item-cell">
                            @if($subcategory->category?->image)
                                <div class="table-item-thumb"><img src="{{ asset($subcategory->category->image) }}" alt="{{ $subcategory->category->name }}"></div>
                            @endif
                            <span>{{ $subcategory->category?->name ?? '—' }}</span>
                        </div>
                    </td>
                    <td>{{ $subcategory->menu_items_count }} items</td>
                    <td><span class="badge-status {{ $subcategory->is_active ? 'is-active' : 'is-inactive' }}">{{ $subcategory->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="table-actions">
                            @can('subcategory.edit')
                                <button type="button" class="table-action-btn" data-bs-toggle="modal" data-bs-target="#subcategoryModal" data-subcategory-edit
                                    data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->name }}" data-category-id="{{ $subcategory->category_id }}"
                                    data-active="{{ $subcategory->is_active ? 1 : 0 }}" title="Edit"><i class="bi bi-pencil"></i></button>
                            @endcan
                            @can('subcategory.delete')
                                <form action="{{ route('admin.subcategories.destroy', $subcategory) }}" method="post" class="delete-form">
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
                            <i class="bi bi-diagram-3"></i>
                            <h3>No subcategories found</h3>
                            <p>Add a subcategory under one of your food categories.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $subcategories->links() }}
