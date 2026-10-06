<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BranchController extends Controller
{
    use ImageUploadTrait;

    public function index(Request $request): View
    {
        $branches = Branch::query()
            ->visibleTo($request->user())
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.branch.partials.table', compact('branches'));
        }

        return view('admin.branch.index', compact('branches'));
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->canSeeAllBranches(), 403);

        return view('admin.branch.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->canSeeAllBranches(), 403);
        $data = $this->validated($request);
        $branch = new Branch();

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpload($request, $branch, 'image', 'branches');
        }

        try {
            Branch::query()->create($data);
        } catch (\Throwable $exception) {
            $this->deleteImage($data['image'] ?? null);
            throw $exception;
        }

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(Request $request, Branch $branch): View
    {
        $this->ensureVisible($request, $branch);
        return view('admin.branch.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $this->ensureVisible($request, $branch);
        $data = $this->validated($request, $branch);

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpdate($request, $branch, 'image', 'branches');
        }

        $branch->update($data);

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Request $request, Branch $branch): RedirectResponse
    {
        abort_unless($request->user()->canSeeAllBranches(), 403);

        if ($branch->users()->exists()) {
            return back()->with('error', 'This branch has assigned users and cannot be deleted.');
        }

        $this->deleteImage($branch->image);
        $branch->delete();
        return back()->with('success', 'Branch deleted successfully.');
    }

    private function validated(Request $request, ?Branch $branch = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:30', Rule::unique('branches', 'code')->ignore($branch?->id)],
            'address' => ['required', 'string', 'max:2000'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'google_map_link' => ['nullable', 'url', 'max:2048'],
            'map_iframe' => ['nullable', 'string', 'max:10000'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['accepting_orders'] = $request->boolean('accepting_orders');
        return $data;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && File::exists(base_path($path))) {
            File::delete(base_path($path));
        }
    }

    private function ensureVisible(Request $request, Branch $branch): void
    {
        if (! $request->user()->canSeeAllBranches() && $request->user()->branch_id !== $branch->id) {
            abort(403);
        }
    }
}
