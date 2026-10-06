<div class="admin-table-wrap">
<table class="admin-table"><thead><tr><th>Role</th><th>Permissions</th><th>Assigned Users</th><th>Created</th><th>Actions</th></tr></thead><tbody>
@forelse($roles as $role)
<tr><td><div class="table-item-name">{{ $role->name }}</div><div class="table-item-sub">Guard: {{ $role->guard_name }}</div></td><td>{{ $role->permissions_count }}</td><td>{{ $role->users_count }}</td><td>{{ $role->created_at?->format('d/m/Y') }}</td><td><div class="table-actions">
@can('role.edit')<a href="{{ route('admin.roles.edit',$role) }}" class="table-action-btn" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
@can('role.delete')<form action="{{ route('admin.roles.destroy',$role) }}" method="post" class="delete-form">@csrf @method('DELETE')<button type="submit" class="table-action-btn danger" data-bs-toggle="tooltip" title="Delete" @disabled($role->name==='Super Admin')><i class="bi bi-trash"></i></button></form>@endcan
</div></td></tr>
@empty<tr class="table-empty-row"><td colspan="5"><div class="empty-state table-empty-state"><i class="bi bi-person-badge"></i><h3>No roles found</h3><p>Try another search or add a role.</p></div></td></tr>@endforelse
</tbody></table></div>
{{ $roles->links() }}
