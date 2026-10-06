<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th><input type="checkbox" class="table-checkbox" data-select-all {{ $menuItems->isEmpty() ? 'disabled' : '' }}></th>
                <th>Item</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menuItems as $menuItem)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $menuItem->id }}" form="menuItemBulkForm" class="table-checkbox"></td>
                    <td>
                        <div class="table-item-cell">
                            <div class="table-item-thumb d-flex align-items-center justify-content-center fs-5">
                                @if($menuItem->mainImage?->image)
                                    <img src="{{ asset($menuItem->mainImage->image) }}" alt="{{ $menuItem->name }}">
                                @else
                                    <i class="bi bi-egg-fried"></i>
                                @endif
                            </div>
                            <div>
                                <div class="table-item-name">{{ $menuItem->name }}</div>
                                <div class="table-item-sub">{{ $menuItem->subcategory?->name ?? $menuItem->category?->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $menuItem->category?->name ?? '—' }}</td>
                    <td>{{ $menuItem->price_display }}</td>
                    <td><span class="badge-status {{ $menuItem->is_active ? 'is-available' : 'is-out-of-stock' }}">{{ $menuItem->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <div class="table-actions">
                            @can('menu-item.view')
                                <a href="{{ route('admin.menu-items.show', $menuItem) }}" class="table-action-btn" title="View"><i class="bi bi-eye"></i></a>
                            @endcan
                            @can('menu-item.edit')
                                <a href="{{ route('admin.menu-items.edit', $menuItem) }}" class="table-action-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('menu-item.delete')
                                <form action="{{ route('admin.menu-items.destroy', $menuItem) }}" method="post" class="delete-form">
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
                            <i class="bi bi-egg-fried"></i>
                            <h3>No menu items found</h3>
                            <p>Try another filter or add a new menu item.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $menuItems->links() }}
