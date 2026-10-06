<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPageContent;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactPageContentController extends Controller
{
    use ImageUploadTrait;

    public function edit(): View
    {
        return view('admin.website.contact.edit', ['content' => ContactPageContent::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $content = ContactPageContent::current();
        $data = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'hero_eyebrow_text' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'address_icon' => ['nullable', 'string', 'max:50'],
            'address_heading' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone_icon' => ['nullable', 'string', 'max:50'],
            'phone_heading' => ['nullable', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:100'],
            'email_icon' => ['nullable', 'string', 'max:50'],
            'email_heading' => ['nullable', 'string', 'max:100'],
            'email_address' => ['nullable', 'email', 'max:255'],
            'hours_icon' => ['nullable', 'string', 'max:50'],
            'hours_heading' => ['nullable', 'string', 'max:100'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'form_heading' => ['nullable', 'string', 'max:255'],
            'name_label' => ['nullable', 'string', 'max:100'],
            'name_placeholder' => ['nullable', 'string', 'max:255'],
            'email_label' => ['nullable', 'string', 'max:100'],
            'email_placeholder' => ['nullable', 'string', 'max:255'],
            'subject_label' => ['nullable', 'string', 'max:100'],
            'subject_placeholder' => ['nullable', 'string', 'max:255'],
            'message_label' => ['nullable', 'string', 'max:100'],
            'message_placeholder' => ['nullable', 'string', 'max:255'],
            'submit_button_text' => ['nullable', 'string', 'max:100'],
            'map_address' => ['nullable', 'string', 'max:1000'],
            'map_embed_url' => ['nullable', 'url', 'max:2000'],
        ]);

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $this->handleImageUpdate($request, $content, 'hero_image', 'website/contact', 1368, 766);
        }
        $data['notify_admin_by_email'] = $request->boolean('notify_admin_by_email');
        $content->update($data);

        return back()->with('success', 'Contact page content updated successfully.');
    }
}
