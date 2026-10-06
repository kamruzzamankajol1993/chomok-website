<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use ImageUploadTrait;

    public function edit(): View
    {
        return view('admin.setting.edit', ['setting' => Setting::current()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::current();
        $data = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'admin_panel_url' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:50'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'icon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i'],
            'header_hours_text' => ['nullable', 'string', 'max:255'],
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'tax_label' => ['required', 'string', 'max:50'],
            'tax_registration_number' => ['nullable', 'string', 'max:100'],
            'service_charge' => ['required', 'numeric', 'min:0', 'max:100'],
            'invoice_prefix' => ['required', 'string', 'max:30'],
            'invoice_starting_number' => ['required', 'string', 'regex:/^(?!0+$)\d{1,18}$/'],
            'invoice_footer_note' => ['nullable', 'string', 'max:2000'],
            'print_paper_size' => ['required', 'in:80mm,a4'],
        ]);

        $data['invoice_starting_number'] = (int) $data['invoice_starting_number'];

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->handleImageUpdate($request, $setting, 'logo', 'settings', 654, 150);
        }

        if ($request->hasFile('icon')) {
            $data['icon'] = $this->handleImageUpdate($request, $setting, 'icon', 'settings', 256, 256);
        }

        $data['tax_included'] = $request->boolean('tax_included');
        $data['show_logo_invoice'] = $request->boolean('show_logo_invoice');

        $setting->update($data);

        return back()->with('success', 'Settings updated successfully.');
    }
}
