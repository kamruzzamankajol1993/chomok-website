<div class="admin-table-wrap">
    <table class="admin-table">
        <thead><tr><th><input type="checkbox" class="table-checkbox" data-select-all></th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($queries as $query)
            <tr class="{{ $query->read_at ? '' : 'table-row-unread' }}">
                <td><input type="checkbox" class="table-checkbox" name="ids[]" value="{{ $query->id }}" form="contactQueryBulkForm"></td>
                <td class="table-item-name">{{ $query->name }}</td><td>{{ $query->email }}</td><td>{{ $query->subject }}</td>
                <td><span class="query-message-preview">{{ $query->message }}</span></td><td>{{ $query->created_at?->format('d/m/Y') }}</td>
                <td><span class="badge-status {{ $query->status === 'new' ? 'is-active' : ($query->status === 'pending' ? 'is-pending' : 'is-inactive') }}">{{ ucfirst($query->status) }}</span></td>
                <td><div class="table-actions">
                    <button type="button" class="table-action-btn" data-bs-toggle="modal" data-bs-target="#viewQueryModal" data-query-view data-id="{{ $query->id }}" data-name="{{ $query->name }}" data-email="{{ $query->email }}" data-date="{{ $query->created_at?->format('d/m/Y') }}" data-subject="{{ $query->subject }}" data-message="{{ $query->message }}" data-status="{{ $query->status }}" title="View"><i class="bi bi-eye"></i></button>
                    @can('contact-query.delete')<form action="{{ route('admin.contact-queries.destroy', $query) }}" method="post" class="delete-form d-inline">@csrf @method('DELETE')<button type="submit" class="table-action-btn danger" title="Delete"><i class="bi bi-trash"></i></button></form>@endcan
                </div></td>
            </tr>
        @empty
            <tr><td colspan="8"><div class="empty-table-state py-5 text-center"><i class="bi bi-envelope fs-1"></i><p class="mb-0 mt-2">No contact queries found.</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="admin-pagination">
    <span>Showing {{ $queries->firstItem() ?? 0 }}-{{ $queries->lastItem() ?? 0 }} of {{ $queries->total() }} queries</span>
    {{ $queries->links() }}
</div>
