@extends('admin.master.master')

@section('title', 'Contact Page Content')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Contact Page Content</span></div>
        <h1 class="page-title">Contact Page Content</h1>
        <p class="page-subtitle">Manage the hero banner, contact details, and map shown on the Contact Us page.</p>
    </div>
    @if($restaurantSetting?->website_url)<a href="{{ rtrim($restaurantSetting->website_url, '/') }}/contact" target="_blank" rel="noopener" class="btn-admin-outline"><i class="bi bi-box-arrow-up-right"></i> View Contact Page</a>@endif
</div>

@include('admin.include.form-errors')

<form action="{{ route('admin.website.contact.update') }}" method="post" enctype="multipart/form-data" data-form-loader data-loader-text="Saving contact page content...">
    @csrf @method('PUT')
    <div class="admin-card form-loader-surface">
        <div class="admin-card-header"><div><h2 class="admin-card-title">SEO</h2><p class="form-section-hint mb-0">Page title and search/social description.</p></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Meta Title</label><input type="text" name="meta_title" class="form-control-admin" value="{{ old('meta_title', $content->meta_title) }}"></div>
        <div class="form-group-admin"><label class="form-label-admin">Meta Description</label><textarea name="meta_description" class="form-control-admin" rows="3">{{ old('meta_description', $content->meta_description) }}</textarea></div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Page Hero</h2><p class="form-section-hint mb-0">The hero banner at the top of the Contact Us page.</p></div></div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Eyebrow Text</label><input type="text" name="hero_eyebrow_text" class="form-control-admin" value="{{ old('hero_eyebrow_text', $content->hero_eyebrow_text) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Title</label><input type="text" name="hero_title" class="form-control-admin" value="{{ old('hero_title', $content->hero_title) }}"></div>
        </div>
        <div class="form-group-admin"><label class="form-label-admin">Hero Image</label><div class="upload-dropzone"><img id="contactHeroPreview" src="{{ $content->hero_image ? asset($content->hero_image) : '' }}" alt="" class="upload-preview" style="{{ $content->hero_image ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div><input type="file" name="hero_image" accept="image/*" class="image-preview-input" data-preview="#contactHeroPreview"></div></div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Contact Info</h2><p class="form-section-hint mb-0">Address, phone, email, and opening hours displayed to visitors.</p></div></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Address Icon</label><input type="text" name="address_icon" class="form-control-admin" value="{{ old('address_icon', $content->address_icon) }}"></div><div class="form-group-admin"><label class="form-label-admin">Address Heading</label><input type="text" name="address_heading" class="form-control-admin" value="{{ old('address_heading', $content->address_heading) }}"></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Address</label><input type="text" name="address" class="form-control-admin" value="{{ old('address', $content->address) }}"></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Phone Icon</label><input type="text" name="phone_icon" class="form-control-admin" value="{{ old('phone_icon', $content->phone_icon) }}"></div><div class="form-group-admin"><label class="form-label-admin">Phone Heading</label><input type="text" name="phone_heading" class="form-control-admin" value="{{ old('phone_heading', $content->phone_heading) }}"></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Phone Number</label><input type="text" name="phone_number" class="form-control-admin" value="{{ old('phone_number', $content->phone_number) }}"></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Email Icon</label><input type="text" name="email_icon" class="form-control-admin" value="{{ old('email_icon', $content->email_icon) }}"></div><div class="form-group-admin"><label class="form-label-admin">Email Heading</label><input type="text" name="email_heading" class="form-control-admin" value="{{ old('email_heading', $content->email_heading) }}"></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Email Address</label><input type="email" name="email_address" class="form-control-admin" value="{{ old('email_address', $content->email_address) }}"></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Hours Icon</label><input type="text" name="hours_icon" class="form-control-admin" value="{{ old('hours_icon', $content->hours_icon) }}"></div><div class="form-group-admin"><label class="form-label-admin">Hours Heading</label><input type="text" name="hours_heading" class="form-control-admin" value="{{ old('hours_heading', $content->hours_heading) }}"></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Opening Hours</label><input type="text" name="opening_hours" class="form-control-admin" value="{{ old('opening_hours', $content->opening_hours) }}"></div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Contact Form Settings</h2><p class="form-section-hint mb-0">Text and notification behavior for the website contact form.</p></div></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Form Heading</label><input type="text" name="form_heading" class="form-control-admin" value="{{ old('form_heading', $content->form_heading) }}"></div><div class="form-group-admin"><label class="form-label-admin">Submit Button Text</label><input type="text" name="submit_button_text" class="form-control-admin" value="{{ old('submit_button_text', $content->submit_button_text) }}"></div></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Name Label</label><input type="text" name="name_label" class="form-control-admin" value="{{ old('name_label', $content->name_label) }}"></div><div class="form-group-admin"><label class="form-label-admin">Name Placeholder</label><input type="text" name="name_placeholder" class="form-control-admin" value="{{ old('name_placeholder', $content->name_placeholder) }}"></div></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Email Label</label><input type="text" name="email_label" class="form-control-admin" value="{{ old('email_label', $content->email_label) }}"></div><div class="form-group-admin"><label class="form-label-admin">Email Placeholder</label><input type="text" name="email_placeholder" class="form-control-admin" value="{{ old('email_placeholder', $content->email_placeholder) }}"></div></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Subject Label</label><input type="text" name="subject_label" class="form-control-admin" value="{{ old('subject_label', $content->subject_label) }}"></div><div class="form-group-admin"><label class="form-label-admin">Subject Placeholder</label><input type="text" name="subject_placeholder" class="form-control-admin" value="{{ old('subject_placeholder', $content->subject_placeholder) }}"></div></div>
        <div class="form-row-admin"><div class="form-group-admin"><label class="form-label-admin">Message Label</label><input type="text" name="message_label" class="form-control-admin" value="{{ old('message_label', $content->message_label) }}"></div><div class="form-group-admin"><label class="form-label-admin">Message Placeholder</label><input type="text" name="message_placeholder" class="form-control-admin" value="{{ old('message_placeholder', $content->message_placeholder) }}"></div></div>
        <div class="form-group-admin d-flex align-items-center justify-content-between"><label class="form-label-admin mb-0">Notify Admin By Email On New Query</label><label class="form-switch-admin"><input type="checkbox" name="notify_admin_by_email" value="1" {{ old('notify_admin_by_email', $content->notify_admin_by_email) ? 'checked' : '' }}><span class="switch-track"></span></label></div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Map Location</h2><p class="form-section-hint mb-0">Location details used for the embedded Google map.</p></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Map Address / Search Query</label><input type="text" name="map_address" class="form-control-admin" value="{{ old('map_address', $content->map_address) }}"></div>
        <div class="form-group-admin"><label class="form-label-admin">Google Maps Embed URL</label><input type="url" name="map_embed_url" class="form-control-admin" value="{{ old('map_embed_url', $content->map_embed_url) }}"></div>
        @can('website-content.edit')<div class="form-actions-admin"><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save Contact Page</button></div>@endcan
    </div>
</form>
@endsection
