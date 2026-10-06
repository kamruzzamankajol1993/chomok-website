<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th><input type="checkbox" class="table-checkbox" data-select-all {{ $categories->isEmpty() ? 'disabled' : '' }}></th>
                <th>Category</th>
                <th>Subcategories</th>
                <th>Items</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $category->id }}" form="categoryBulkForm" class="table-checkbox"></td>
                    <td>
                        <div class="table-item-cell">
                            <div class="table-item-thumb d-flex align-items-center justify-content-center fs-5">
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                                @else
                                    <i class="bi bi-tags-fill"></i>
                                @endif
                            </div>
                            <div class="table-item-name">{{ $category->name }}</div>
                        </div>
                    </td>
                    <td>{{ $category->subcategories_count }}</td>
                    <td>{{ $category->menu_items_count }} items</td>
                    <td><span class="badge-status {{ $category->is_active ? 'is-active' : 'is-inactive' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="table-actions">
                            @can('category.edit')
                                <button type="button" class="table-action-btn" data-bs-toggle="modal" data-bs-target="#categoryModal" data-category-edit
                                    data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}"
                                    data-active="{{ $category->is_active ? 1 : 0 }}" data-image="{{ $category->image ? asset($category->image) : '' }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @endcan
                            @can('category.delete')
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="post" class="delete-form">
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
                            <i class="bi bi-tags"></i>
                            <h3>No categories found</h3>
                            <p>Add your first food category to get started.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $categories->links() }}
