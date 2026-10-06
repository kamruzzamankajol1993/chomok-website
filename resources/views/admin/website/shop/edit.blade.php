@extends('admin.master.master')

@section('title', 'Shop Page Content')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Shop Page Content</span></div>
        <h1 class="page-title">Shop Page Content</h1>
        <p class="page-subtitle">Manage the content shown above the dynamic branch list on the website Shop page.</p>
    </div>
    @if($restaurantSetting?->website_url)
        <a href="{{ rtrim($restaurantSetting->website_url, '/') }}/branch" target="_blank" rel="noopener" class="btn-admin-outline"><i class="bi bi-box-arrow-up-right"></i> View Shop Page</a>
    @endif
</div>

@include('admin.include.form-errors')

<form action="{{ route('admin.website.shop.update') }}" method="post" enctype="multipart/form-data" data-form-loader data-loader-text="Saving shop page content...">
    @csrf @method('PUT')

    <div class="admin-card form-loader-surface">
        <div class="admin-card-header"><div><h2 class="admin-card-title">SEO</h2><p class="form-section-hint mb-0">Page title and search/social description.</p></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Meta Title</label><input type="text" name="meta_title" class="form-control-admin" value="{{ old('meta_title', $content->meta_title) }}"></div>
        <div class="form-group-admin"><label class="form-label-admin">Meta Description</label><textarea name="meta_description" class="form-control-admin" rows="3">{{ old('meta_description', $content->meta_description) }}</textarea></div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Page Hero</h2><p class="form-section-hint mb-0">If no hero image is uploaded, the first active branch image is used automatically.</p></div></div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Eyebrow Text</label><input type="text" name="hero_eyebrow_text" class="form-control-admin" value="{{ old('hero_eyebrow_text', $content->hero_eyebrow_text) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Hero Title</label><input type="text" name="hero_title" class="form-control-admin" value="{{ old('hero_title', $content->hero_title) }}"></div>
        </div>
        <div class="form-group-admin">
            <label class="form-label-admin">Hero Image</label>
            <div class="upload-dropzone">
                <img id="shopHeroPreview" src="{{ $content->hero_image ? asset($content->hero_image) : '' }}" alt="" class="upload-preview" style="{{ $content->hero_image ? 'display:block' : '' }}">
                <i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div>
                <input type="file" name="hero_image" accept="image/*" class="image-preview-input" data-preview="#shopHeroPreview">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Shop Introduction</h2><p class="form-section-hint mb-0">Text shown directly before the branch list.</p></div></div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Badge Text</label><input type="text" name="intro_badge_text" class="form-control-admin" value="{{ old('intro_badge_text', $content->intro_badge_text) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Heading</label><input type="text" name="intro_heading" class="form-control-admin" value="{{ old('intro_heading', $content->intro_heading) }}"></div>
        </div>
        <div class="form-group-admin"><label class="form-label-admin">Description</label><textarea name="intro_text" class="form-control-admin" rows="4">{{ old('intro_text', $content->intro_text) }}</textarea></div>
        <div class="form-group-admin"><label class="form-label-admin">Google Map Link Text</label><input type="text" name="map_link_text" class="form-control-admin" value="{{ old('map_link_text', $content->map_link_text) }}"></div>
        @can('website-content.edit')<div class="form-actions-admin"><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save Shop Page</button></div>@endcan
    </div>
</form>
@endsection
