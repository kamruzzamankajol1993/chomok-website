<div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Group</th><th>Permission</th><th>Guard</th><th>Created</th><th>Actions</th></tr></thead><tbody>
@forelse($permissions as $permission)
<tr><td><span class="group-badge">{{ $permission->group }}</span></td><td><div class="table-item-name">{{ $permission->name }}</div></td><td>{{ $permission->guard_name }}</td><td>{{ $permission->created_at?->format('d/m/Y') }}</td><td><div class="table-actions">
@can('permission.edit')<a href="{{ route('admin.permissions.edit-group', ['group'=>$permission->group]) }}" class="table-action-btn" data-bs-toggle="tooltip" title="Edit Group"><i class="bi bi-pencil"></i></a>@endcan
@can('permission.delete')<form action="{{ route('admin.permissions.destroy',$permission) }}" method="post" class="delete-form">@csrf @method('DELETE')<button type="submit" class="table-action-btn danger" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button></form>@endcan
</div></td></tr>
@empty<tr class="table-empty-row"><td colspan="5"><div class="empty-state table-empty-state"><i class="bi bi-shield-lock"></i><h3>No permissions found</h3><p>Try another search or add a permission group.</p></div></td></tr>@endforelse
</tbody></table></div>
{{ $permissions->links() }}
