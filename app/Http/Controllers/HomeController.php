<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Client;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $orders = $this->visibleOrders($user);

        if (! $user->canSeeAllBranches()) {
            $totalOrders = (clone $orders)->count();
            $totalPending = (clone $orders)->where('status', 'pending')->count();
            $totalConfirmed = (clone $orders)->where('status', 'confirmed')->count();
            $totalCancelled = (clone $orders)->where('status', 'cancelled')->count();

            $recentOrders = (clone $orders)
                ->with(['items:id,order_id,item_name,quantity', 'branch:id,name'])
                ->latest('id')
                ->limit(5)
                ->get();

            return view('admin.dashboard.user', compact(
                'totalOrders',
                'totalPending',
                'totalConfirmed',
                'totalCancelled',
                'recentOrders',
            ));
        }

        $totalOrders = (clone $orders)->count();
        $totalRevenue = (float) (clone $orders)->where('status', 'delivered')->sum('grand_total');

        $menuItems = MenuItem::query();
        if (! $user->canSeeAllBranches()) {
            $menuItems->whereHas('branches', fn (Builder $query) => $query->whereKey($user->branch_id));
        }
        $menuItemCount = (clone $menuItems)->count();
        $newMenuItemsThisWeek = (clone $menuItems)->where('created_at', '>=', now()->startOfWeek())->count();

        $users = User::query()->visibleTo($user);
        $registeredUsers = (clone $users)->count();
        $newUsersThisMonth = (clone $users)->where('created_at', '>=', now()->startOfMonth())->count();

        $thisMonthStart = now()->startOfMonth();
        $lastMonthStart = now()->copy()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->copy()->subMonthNoOverflow()->endOfMonth();

        $thisMonthOrders = (clone $orders)->whereBetween('created_at', [$thisMonthStart, now()])->count();
        $lastMonthOrders = (clone $orders)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        $orderTrend = $this->percentageChange($thisMonthOrders, $lastMonthOrders);

        $thisMonthRevenue = (float) (clone $orders)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$thisMonthStart, now()])
            ->sum('grand_total');
        $lastMonthRevenue = (float) (clone $orders)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('grand_total');
        $revenueTrend = $this->percentageChange($thisMonthRevenue, $lastMonthRevenue);

        $recentOrders = (clone $orders)
            ->with(['items:id,order_id,item_name,quantity', 'branch:id,name'])
            ->latest('id')
            ->limit(5)
            ->get();

        $topCategories = $this->topCategories($user);

        return view('admin.dashboard.index', compact(
            'totalOrders',
            'totalRevenue',
            'menuItemCount',
            'newMenuItemsThisWeek',
            'registeredUsers',
            'newUsersThisMonth',
            'orderTrend',
            'revenueTrend',
            'recentOrders',
            'topCategories',
        ));
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => ['required', 'string', 'min:2', 'max:100']]);

        $user = $request->user();
        $term = trim((string) $request->input('q'));
        $like = '%'.$term.'%';
        $results = collect();

        if ($user->can('order.view')) {
            $orders = $this->visibleOrders($user)
                ->where(function (Builder $query) use ($like): void {
                    $query->where('order_number', 'like', $like)
                        ->orWhere('customer_name', 'like', $like)
                        ->orWhere('customer_phone', 'like', $like);
                })
                ->latest('id')
                ->limit(5)
                ->get(['id', 'order_number', 'customer_name', 'customer_phone', 'grand_total', 'status']);

            foreach ($orders as $order) {
                $results->push([
                    'type' => 'Order',
                    'icon' => 'bi-receipt',
                    'title' => '#'.$order->order_number.' · '.$order->customer_name,
                    'subtitle' => $order->customer_phone.' · TK '.number_format((float) $order->grand_total, 0).' · '.ucfirst($order->status),
                    'url' => route('admin.orders.show', $order),
                ]);
            }
        }

        if ($user->can('menu-item.view')) {
            $items = MenuItem::query()
                ->with('category:id,name')
                ->when(! $user->canSeeAllBranches(), fn (Builder $query) => $query->whereHas('branches', fn (Builder $branchQuery) => $branchQuery->whereKey($user->branch_id)))
                ->where(function (Builder $query) use ($like): void {
                    $query->where('name', 'like', $like)->orWhere('description', 'like', $like);
                })
                ->limit(5)
                ->get(['id', 'category_id', 'name', 'is_active']);

            foreach ($items as $item) {
                $results->push([
                    'type' => 'Menu Item',
                    'icon' => 'bi-egg-fried',
                    'title' => $item->name,
                    'subtitle' => ($item->category?->name ?? 'Uncategorized').' · '.($item->is_active ? 'Active' : 'Inactive'),
                    'url' => route('admin.menu-items.show', $item),
                ]);
            }
        }

        if ($user->can('client.view')) {
            $clients = Client::query()->visibleTo($user)
                ->where(function (Builder $query) use ($like): void {
                    $query->where('name', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('code', 'like', $like);
                })
                ->limit(4)
                ->get(['id', 'name', 'phone', 'code']);

            foreach ($clients as $client) {
                $results->push([
                    'type' => 'Client',
                    'icon' => 'bi-person',
                    'title' => $client->name,
                    'subtitle' => $client->code.' · '.$client->phone,
                    'url' => route('admin.clients.show', $client),
                ]);
            }
        }

        if ($user->can('branch.view')) {
            $branches = Branch::query()
                ->when(! $user->canSeeAllBranches(), fn (Builder $query) => $query->whereKey($user->branch_id))
                ->where(function (Builder $query) use ($like): void {
                    $query->where('name', 'like', $like)->orWhere('code', 'like', $like)->orWhere('phone', 'like', $like);
                })
                ->limit(4)
                ->get(['id', 'name', 'code', 'phone']);

            foreach ($branches as $branch) {
                $results->push([
                    'type' => 'Branch',
                    'icon' => 'bi-shop',
                    'title' => $branch->name,
                    'subtitle' => $branch->code.' · '.$branch->phone,
                    'url' => route('admin.branches.index', ['search' => $branch->name]),
                ]);
            }
        }

        if ($user->can('user.view')) {
            $adminUsers = User::query()->visibleTo($user)
                ->where(function (Builder $query) use ($like): void {
                    $query->where('name', 'like', $like)->orWhere('email', 'like', $like)->orWhere('phone', 'like', $like);
                })
                ->limit(4)
                ->get(['id', 'name', 'email', 'phone']);

            foreach ($adminUsers as $adminUser) {
                $results->push([
                    'type' => 'User',
                    'icon' => 'bi-people',
                    'title' => $adminUser->name,
                    'subtitle' => $adminUser->email ?: ($adminUser->phone ?: 'Admin user'),
                    'url' => route('admin.users.show', $adminUser),
                ]);
            }
        }

        if ($user->can('category.view')) {
            $categories = Category::query()->where('name', 'like', $like)->limit(3)->get(['id', 'name']);
            foreach ($categories as $category) {
                $results->push([
                    'type' => 'Category',
                    'icon' => 'bi-tags',
                    'title' => $category->name,
                    'subtitle' => 'Catalog category',
                    'url' => route('admin.categories.index', ['search' => $category->name]),
                ]);
            }
        }

        if ($user->can('subcategory.view')) {
            $subcategories = Subcategory::query()->where('name', 'like', $like)->limit(3)->get(['id', 'name']);
            foreach ($subcategories as $subcategory) {
                $results->push([
                    'type' => 'Subcategory',
                    'icon' => 'bi-diagram-3',
                    'title' => $subcategory->name,
                    'subtitle' => 'Catalog subcategory',
                    'url' => route('admin.subcategories.index', ['search' => $subcategory->name]),
                ]);
            }
        }

        return response()->json([
            'results' => $results->take(12)->values(),
        ]);
    }

    private function visibleOrders(User $user): Builder
    {
        return Order::query()->visibleTo($user);
    }

    private function percentageChange(float|int $current, float|int $previous): float
    {
        if ((float) $previous === 0.0) {
            return (float) $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / abs($previous)) * 100, 1);
    }

    private function topCategories(User $user): array
    {
        $query = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->join('categories', 'categories.id', '=', 'menu_items.category_id')
            ->whereNull('orders.deleted_at')
            ->whereNull('menu_items.deleted_at')
            ->whereNull('categories.deleted_at')
            ->where('orders.status', '!=', 'cancelled');

        if (! $user->canSeeAllOrders()) {
            if ($user->branch_id) {
                $query->where('orders.branch_id', $user->branch_id);
            } else {
                return [];
            }
        }

        $rows = $query
            ->select('categories.name', DB::raw('SUM(order_items.quantity) as sold_qty'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('sold_qty')
            ->limit(5)
            ->get();

        $total = max(1, (int) $rows->sum('sold_qty'));

        return $rows->map(fn ($row): array => [
            'name' => $row->name,
            'quantity' => (int) $row->sold_qty,
            'percent' => round(((int) $row->sold_qty / $total) * 100),
        ])->all();
    }
}
