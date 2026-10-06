<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ImageUploadTrait;

    public function edit(Request $request): View
    {
        return view('admin.user.profile', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'current_password' => ['nullable', 'required_with:password', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (filled($data['password'] ?? null)) {
            if (! Hash::check((string) $data['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'The current password is incorrect.',
                ]);
            }
        } else {
            unset($data['password']);
        }

        unset($data['current_password']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->handleImageUpdate($request, $user, 'image', 'users', 500, 500);
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
