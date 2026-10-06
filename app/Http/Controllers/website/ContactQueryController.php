<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ContactQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactQueryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:100'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $query = ContactQuery::query()->create($data + ['status' => 'new']);

        return response()->json([
            'message' => 'Your message has been submitted successfully.',
            'query_id' => $query->id,
        ], 201);
    }
}
