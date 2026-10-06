<div class="admin-table-wrap">
<table class="admin-table">
    <thead><tr><th>Branch</th><th>Address</th><th>Phone</th><th>Accepting Orders</th><th>Status</th><th>Created</th><th>Image</th><th>Google Map</th><th>Map iFrame</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse($branches as $branch)
        <tr>
            <td><div class="table-item-name">{{ $branch->name }}</div><div class="table-item-sub">{{ $branch->code ?: 'No code' }}</div></td>
            <td>{{ $branch->address }} @if($branch->city)<div class="table-item-sub">{{ $branch->city }}</div>@endif</td>
            <td>{{ $branch->phone ?: '—' }}</td>
            <td><span class="badge-status {{ $branch->accepting_orders ? 'is-active' : 'is-blocked' }}">{{ $branch->accepting_orders ? 'Yes' : 'No' }}</span></td>
            <td><span class="badge-status {{ $branch->status === 'active' ? 'is-active' : 'is-blocked' }}">{{ ucfirst($branch->status) }}</span></td>
            <td>{{ $branch->created_at?->format('d/m/Y') }}</td>
            <td>@if($branch->image)<img src="{{ asset($branch->image) }}" alt="{{ $branch->name }}" style="width:60px;height:45px;object-fit:cover;border-radius:6px;">@else — @endif</td>
            <td>@if($branch->google_map_link)<a href="{{ $branch->google_map_link }}" target="_blank" rel="noopener" class="table-action-btn" data-bs-toggle="tooltip" title="Open Google Map"><i class="bi bi-geo-alt"></i></a>@else — @endif</td>
            <td>@if($branch->map_iframe)<span class="badge-status is-active">Added</span>@else — @endif</td>
            <td><div class="table-actions">
                @can('branch.edit')<a href="{{ route('admin.branches.edit', $branch) }}" class="table-action-btn" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
                @can('branch.delete')@if(auth()->user()->canSeeAllBranches())<form action="{{ route('admin.branches.destroy', $branch) }}" method="post" class="delete-form">@csrf @method('DELETE')<button type="submit" class="table-action-btn danger" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button></form>@endif @endcan
            </div></td>
        </tr>
    @empty
        <tr class="table-empty-row"><td colspan="10"><div class="empty-state table-empty-state"><i class="bi bi-shop"></i><h3>No branches found</h3><p>Try changing the filters or add a branch.</p></div></td></tr>
    @endforelse
    </tbody>
</table>
</div>
{{ $branches->links() }}
