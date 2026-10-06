<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
        <tr>
            <th><input type="checkbox" class="table-checkbox" data-select-all></th>
            <th>User</th>
            <th>Phone</th>
            <th>Orders</th>
            <th>Total Spent</th>
            <th>Branch</th>
            <th>Joined</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($clients as $client)
            <tr>
                <td><input type="checkbox" class="table-checkbox" value="{{ $client->id }}"></td>
                <td>
                    <div class="table-item-cell">
                        <div class="table-item-thumb d-flex align-items-center justify-content-center fs-6 fw-bold avatar-fallback">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                        <div><div class="table-item-name">{{ $client->name }}</div><div class="table-item-sub">{{ $client->email ?: $client->code }}</div></div>
                    </div>
                </td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->orders_count }}</td>
                <td>TK {{ number_format((float) ($client->total_spent ?? 0), 2) }}</td>
                <td>{{ $client->branch?->name ?? '—' }}</td>
                <td>{{ $client->created_at?->format('d/m/Y') }}</td>
                <td><span class="badge-status {{ $client->status === 'active' ? 'is-active' : 'is-blocked' }}">{{ ucfirst($client->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        @can('client.view')<a href="{{ route('admin.clients.show', $client) }}" class="table-action-btn" title="View Profile"><i class="bi bi-eye"></i></a>@endcan
                        @can('client.edit')<a href="{{ route('admin.clients.edit', $client) }}" class="table-action-btn" title="Edit"><i class="bi bi-pencil"></i></a>@endcan
                        @can('client.edit')
                            <form action="{{ route('admin.clients.status', $client) }}" method="post" class="inline-action-form">@csrf @method('PATCH')
                                <button type="submit" class="table-action-btn {{ $client->status === 'active' ? 'danger' : '' }}" title="{{ $client->status === 'active' ? 'Block' : 'Activate' }}"><i class="bi bi-{{ $client->status === 'active' ? 'slash-circle' : 'check-circle' }}"></i></button>
                            </form>
                        @endcan
                        @can('client.delete')
                            <form action="{{ route('admin.clients.destroy', $client) }}" method="post" class="delete-form">@csrf @method('DELETE')
                                <button type="submit" class="table-action-btn danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="9"><div class="empty-state"><i class="bi bi-people"></i><h3>No clients found</h3><p>Client records will appear here.</p></div></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@include('admin.include.pagination', ['paginator' => $clients])
