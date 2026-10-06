<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactQueryController extends Controller
{
    public function index(Request $request): View
    {
        $queries = ContactQuery::query()
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.contact-query.partials.table', compact('queries'));
        }

        return view('admin.contact-query.index', compact('queries'));
    }

    public function update(Request $request, ContactQuery $contactQuery): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:new,pending,closed']]);
        $data['read_at'] = $contactQuery->read_at ?: now();
        $contactQuery->update($data);

        return back()->with('success', 'Contact query status updated successfully.');
    }

    public function markRead(ContactQuery $contactQuery): RedirectResponse
    {
        if (! $contactQuery->read_at) {
            $contactQuery->update(['read_at' => now()]);
        }

        return back();
    }

    public function destroy(ContactQuery $contactQuery): RedirectResponse
    {
        $contactQuery->delete();

        return back()->with('success', 'Contact query deleted successfully.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:contact_queries,id'],
            'bulk_action' => ['required', 'in:delete,new,pending,closed'],
        ]);

        $requiredPermission = $data['bulk_action'] === 'delete' ? 'contact-query.delete' : 'contact-query.edit';
        abort_unless($request->user()->can($requiredPermission), 403);

        if ($data['bulk_action'] === 'delete') {
            ContactQuery::query()->whereKey($data['ids'])->delete();
            return back()->with('success', 'Selected contact queries deleted successfully.');
        }

        ContactQuery::query()->whereKey($data['ids'])->update([
            'status' => $data['bulk_action'],
            'read_at' => now(),
        ]);

        return back()->with('success', 'Selected contact query statuses updated successfully.');
    }
}
