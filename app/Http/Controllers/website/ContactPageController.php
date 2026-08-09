<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\ContactPageContent;
use App\Models\ContactQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactPageController extends Controller
{
    public function index(): View
    {
        $content = ContactPageContent::current();
        return view('website.contact.contact', compact('content'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);
        ContactQuery::query()->create($data);
        return back()->with('success', 'Your message has been sent successfully.');
    }
}
