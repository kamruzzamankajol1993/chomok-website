<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Client::query()
            ->visibleTo($request->user())
            ->with('branch')
            ->withCount('orders')
            ->withSum(['orders as total_spent' => fn ($query) => $query->where('status', 'delivered')], 'grand_total')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = trim((string) $request->input('search'));
                $query->where(function ($inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('branch_id') && $request->user()->canSeeAllBranches(), fn ($query) => $query->where('branch_id', $request->integer('branch_id')))
            ->latest();

        $clients = $query->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return view('admin.client.partials.table', compact('clients'));
        }

        $branches = Branch::query()->visibleTo($request->user())->orderBy('name')->get();
        $totalClients = Client::query()->visibleTo($request->user())->count();

        return view('admin.client.index', compact('clients', 'branches', 'totalClients'));
    }

    public function create(): View
    {
        return view('admin.client.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $branchId = $this->branchIdFor($request);

        $client = Client::query()->create([
            'branch_id' => $branchId,
            'created_by' => $request->user()->id,
            'code' => $this->nextCode(),
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
            'can_login' => $request->boolean('can_login'),
            'password' => $request->boolean('can_login') ? $data['password'] : null,
            'status' => $data['status'],
        ]);

        return redirect()->route('admin.clients.show', $client)->with('success', 'Client created successfully.');
    }


    public function quickStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('clients', 'email')],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:3000'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'can_login' => ['nullable', 'boolean'],
            'password' => [Rule::requiredIf($request->boolean('can_login')), 'nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->user()->canSeeAllBranches()) {
            $branchId = (int) ($data['branch_id'] ?? 0);
            abort_unless($branchId && Branch::query()->whereKey($branchId)->where('status', 'active')->exists(), 422, 'Select an active branch.');
        } else {
            $branchId = (int) $request->user()->branch_id;
            abort_unless($branchId, 422, 'Your account is not assigned to a branch.');
        }

        $client = Client::query()->create([
            'branch_id' => $branchId,
            'created_by' => $request->user()->id,
            'code' => $this->nextCode(),
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
            'can_login' => $request->boolean('can_login'),
            'password' => $request->boolean('can_login') ? $data['password'] : null,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Client added successfully.',
            'client' => [
                'id' => $client->id,
                'branch_id' => $client->branch_id,
                'code' => $client->code,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'address' => $client->address,
            ],
        ], 201);
    }

    public function show(Request $request, Client $client): View
    {
        $this->ensureVisible($request, $client);
        $client->load(['branch', 'creator']);
        $orders = $client->orders()->visibleTo($request->user())->latest()->paginate(8);
        $stats = [
            'orders' => $client->orders()->count(),
            'delivered' => $client->orders()->where('status', 'delivered')->count(),
            'spent' => (float) $client->orders()->where('status', 'delivered')->sum('grand_total'),
            'last_order' => $client->orders()->latest()->first()?->created_at,
        ];

        return view('admin.client.show', compact('client', 'orders', 'stats'));
    }

    public function edit(Request $request, Client $client): View
    {
        $this->ensureVisible($request, $client);

        return view('admin.client.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $this->ensureVisible($request, $client);
        $data = $this->validated($request, $client);
        $canLogin = $request->boolean('can_login');

        $attributes = [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'address' => $data['address'] ?? null,
            'notes' => $data['notes'] ?? null,
            'can_login' => $canLogin,
            'status' => $data['status'],
        ];

        if (! $canLogin) {
            $attributes['password'] = null;
        } elseif (filled($data['password'] ?? null)) {
            $attributes['password'] = $data['password'];
        }

        $client->update($attributes);

        return redirect()->route('admin.clients.show', $client)->with('success', 'Client updated successfully.');
    }

    public function updateNotes(Request $request, Client $client): RedirectResponse
    {
        $this->ensureVisible($request, $client);
        $data = $request->validate(['notes' => ['nullable', 'string', 'max:5000']]);
        $client->update($data);

        return back()->with('success', 'Client note updated successfully.');
    }

    public function toggleStatus(Request $request, Client $client): RedirectResponse
    {
        $this->ensureVisible($request, $client);
        $client->update(['status' => $client->status === 'active' ? 'blocked' : 'active']);

        return back()->with('success', 'Client status updated successfully.');
    }

    public function destroy(Request $request, Client $client): RedirectResponse
    {
        $this->ensureVisible($request, $client);
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }

    public function export(Request $request): StreamedResponse
    {
        $clients = Client::query()->visibleTo($request->user())->with('branch')->withCount('orders')->get();

        return response()->streamDownload(function () use ($clients): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Client ID', 'Name', 'Email', 'Phone', 'Branch', 'Orders', 'Status', 'Joined']);
            foreach ($clients as $client) {
                fputcsv($handle, [
                    $client->code,
                    $client->name,
                    $client->email,
                    $client->phone,
                    $client->branch?->name,
                    $client->orders_count,
                    ucfirst($client->status),
                    $client->created_at?->format('d/m/Y'),
                ]);
            }
            fclose($handle);
        }, 'clients-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    private function validated(Request $request, ?Client $client = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable', 'email', 'max:255',
                Rule::unique('clients', 'email')->ignore($client?->id),
                Rule::requiredIf($request->boolean('can_login')),
            ],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:3000'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'can_login' => ['nullable', 'boolean'],
            'password' => [Rule::requiredIf($request->boolean('can_login') && (! $client || blank($client->getRawOriginal('password')))), 'nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', Rule::in(['active', 'blocked'])],
        ]);
    }

    private function branchIdFor(Request $request): int
    {
        if ($request->user()->branch_id) {
            return (int) $request->user()->branch_id;
        }

        $branch = Branch::query()->where('status', 'active')->orderBy('id')->first() ?? Branch::query()->orderBy('id')->first();
        abort_unless($branch, 422, 'Create a branch before creating a client.');

        return (int) $branch->id;
    }

    private function nextCode(): string
    {
        $next = ((int) Client::withTrashed()->max('id')) + 1;

        return 'CL-'.str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }

    private function ensureVisible(Request $request, Client $client): void
    {
        abort_unless($request->user()->canSeeAllBranches() || (int) $client->branch_id === (int) $request->user()->branch_id, 403);
    }
}
