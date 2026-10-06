<div class="admin-table-wrap">
<table class="admin-table">
<thead><tr><th>User</th><th>Phone</th><th>Branch</th><th>Role</th><th>Access</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($users as $user)
<tr>
    <td><div class="table-item-cell">@if($user->image)<img src="{{ asset($user->image) }}" class="table-item-thumb object-fit-cover" alt="{{ $user->name }}">@else<div class="table-item-thumb d-flex align-items-center justify-content-center fs-6 fw-bold avatar-fallback">{{ strtoupper(substr($user->name,0,1)) }}</div>@endif<div><div class="table-item-name">{{ $user->name }}</div><div class="table-item-sub">{{ $user->email }}</div></div></div></td>
    <td>{{ $user->phone }}</td>
    <td>{{ $user->branch?->name ?? 'Not assigned' }}</td>
    <td>{{ $user->getRoleNames()->join(', ') ?: 'No role' }}</td>
    <td><span class="badge-status {{ $user->canSeeAllBranches() ? 'is-processing' : 'is-pending' }}">{{ $user->canSeeAllBranches() ? 'All Branches' : 'Own Branch' }}</span></td>
    <td>{{ $user->created_at?->format('d/m/Y') }}</td>
    <td><span class="badge-status {{ $user->status==='active' ? 'is-active':'is-blocked' }}">{{ ucfirst($user->status) }}</span></td>
    <td><div class="table-actions">
        <a href="{{ route('admin.users.show',$user) }}" class="table-action-btn" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></a>
        @can('user.edit')<a href="{{ route('admin.users.edit',$user) }}" class="table-action-btn" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
        @can('user.delete')<form action="{{ route('admin.users.destroy',$user) }}" method="post" class="delete-form">@csrf @method('DELETE')<button type="submit" class="table-action-btn danger" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button></form>@endcan
    </div></td>
</tr>
@empty<tr class="table-empty-row"><td colspan="8"><div class="empty-state table-empty-state"><i class="bi bi-people"></i><h3>No users found</h3><p>Try another filter or add a new user.</p></div></td></tr>@endforelse
</tbody>
</table>
</div>
{{ $users->links() }}
