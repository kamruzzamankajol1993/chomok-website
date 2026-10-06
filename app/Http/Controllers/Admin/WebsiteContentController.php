<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.website.content.edit', [
            'content' => WebsiteContent::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'terms_and_conditions' => ['nullable', 'string'],
            'privacy_policy' => ['nullable', 'string'],
            'refund_policy' => ['nullable', 'string'],
            'delivery_info' => ['nullable', 'string'],
        ]);

        WebsiteContent::current()->update($data);

        return back()->with('success', 'Website content updated successfully.');
    }
}
