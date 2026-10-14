<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Services\BranchOrderHours;
use Illuminate\Http\JsonResponse;

class BranchOrderStatusController extends Controller
{
    public function show(Branch $branch, BranchOrderHours $hours): JsonResponse
    {
        return response()->json(collect($hours->status($branch))->only(['can_order', 'message'])->all())
            ->header('Cache-Control', 'no-store, private');
    }
}
