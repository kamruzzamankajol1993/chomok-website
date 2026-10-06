@extends('admin.master.master')

@section('title', 'Contact Queries')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Contact Queries</span></div>
        <h1 class="page-title">Contact Queries</h1>
        <p class="page-subtitle">Messages submitted through the Contact Us page form.</p>
    </div>
</div>

<div class="admin-card">
    <div class="catalog-list-toolbar contact-query-list-toolbar">
        <form action="{{ route('admin.contact-queries.index') }}" method="get" class="menu-filter-form catalog-search-form contact-query-filter-form" data-ajax-list-form data-target="#ajax-list-container">
            <div class="topbar-search catalog-table-search flex-grow-1"><i class="bi bi-search"></i><input type="text" name="search" value="{{ request('search') }}" placeholder="Search contact queries..." autocomplete="off" data-ajax-search></div>
            <select name="status" class="form-control-admin choices-select contact-query-status-filter" data-ajax-filter>
                <option value="">All Status</option><option value="new" @selected(request('status') === 'new')>New</option><option value="pending" @selected(request('status') === 'pending')>Pending</option><option value="closed" @selected(request('status') === 'closed')>Closed</option>
            </select>
        </form>
        @if(auth()->user()->canAny(['contact-query.edit', 'contact-query.delete']))
            <form id="contactQueryBulkForm" action="{{ route('admin.contact-queries.bulk-action') }}" method="post" class="bulk-action-form contact-query-bulk-form" data-bulk-action-form>
                @csrf
                <select name="bulk_action" class="form-control-admin"><option value="">Bulk Action</option>@can('contact-query.edit')<option value="new">Mark New</option><option value="pending">Mark Pending</option><option value="closed">Mark Closed</option>@endcan @can('contact-query.delete')<option value="delete">Delete</option>@endcan</select>
                <button type="submit" class="btn-admin-outline">Apply</button>
            </form>
        @endif
    </div>

    <div id="ajax-list-container">
        @include('admin.contact-query.partials.table')
    </div>
</div>

<div class="modal fade" id="viewQueryModal" tabindex="-1" aria-labelledby="viewQueryModalLabel" aria-hidden="true" data-update-url="{{ route('admin.contact-queries.update', '__ID__') }}">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="viewQueryModalLabel">Message Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
        <form id="contactQueryStatusForm" method="post">@csrf @method('PUT')
            <div class="form-group-admin"><label class="form-label-admin">Name</label><input type="text" class="form-control-admin" data-query-name readonly></div>
            <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Email</label><input type="email" class="form-control-admin" data-query-email readonly></div><div class="form-group-admin"><label class="form-label-admin">Date</label><input type="text" class="form-control-admin" data-query-date readonly></div></div>
            <div class="form-group-admin"><label class="form-label-admin">Subject</label><input type="text" class="form-control-admin" data-query-subject readonly></div>
            <div class="form-group-admin"><label class="form-label-admin">Message</label><textarea class="form-control-admin" rows="5" data-query-message readonly></textarea></div>
            <div class="form-group-admin mb-0"><label class="form-label-admin">Status</label><select name="status" class="form-control-admin" data-query-status><option value="new">New</option><option value="pending">Pending</option><option value="closed">Closed</option></select></div>
        </form>
    </div><div class="modal-footer"><button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Close</button>@can('contact-query.edit')<button type="submit" form="contactQueryStatusForm" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save Status</button>@endcan</div></div></div>
</div>
@endsection


@push('styles')
<style>
    .contact-query-list-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: nowrap;
    }

    .contact-query-filter-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1 1 auto;
        min-width: 0;
        margin: 0;
    }

    .contact-query-filter-form .catalog-table-search {
        flex: 1 1 auto;
        min-width: 220px;
    }

    .contact-query-filter-form .contact-query-status-filter,
    .contact-query-filter-form .choices {
        flex: 0 0 180px;
        width: 180px;
        min-width: 180px;
        margin-bottom: 0;
    }

    .contact-query-bulk-form {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 0 0 auto;
        margin: 0;
    }

    .contact-query-bulk-form .form-control-admin {
        width: 180px;
        min-width: 180px;
    }

    .contact-query-bulk-form .btn-admin-outline {
        white-space: nowrap;
    }

    @media (max-width: 767.98px) {
        .contact-query-list-toolbar {
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .contact-query-filter-form {
            min-width: 520px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
(function(){
    var modal=document.getElementById('viewQueryModal'); if(!modal)return;
    var form=document.getElementById('contactQueryStatusForm');
    document.addEventListener('click',function(e){var button=e.target.closest('[data-query-view]'); if(!button)return;
        form.action=modal.dataset.updateUrl.replace('__ID__',button.dataset.id);
        modal.querySelector('.modal-title').textContent='Message From '+(button.dataset.name||'Visitor');
        modal.querySelector('[data-query-name]').value=button.dataset.name||'';
        modal.querySelector('[data-query-email]').value=button.dataset.email||'';
        modal.querySelector('[data-query-date]').value=button.dataset.date||'';
        modal.querySelector('[data-query-subject]').value=button.dataset.subject||'';
        modal.querySelector('[data-query-message]').value=button.dataset.message||'';
        modal.querySelector('[data-query-status]').value=button.dataset.status||'new';
    });
})();
</script>
@endpush
