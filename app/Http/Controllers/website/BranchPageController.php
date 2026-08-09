<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ShopPageContent;
use Illuminate\View\View;

class BranchPageController extends Controller
{
    public function index(): View
    {
        $branches = Branch::query()->where('status', 'active')->orderBy('name')->get();
        $content = ShopPageContent::current();

        return view('website.branch.branch', compact('branches', 'content'));
    }
}
