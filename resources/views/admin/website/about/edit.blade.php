@extends('admin.master.master')

@section('title', 'About Page Content')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">About Page Content</span></div>
        <h1 class="page-title">About Page Content</h1>
        <p class="page-subtitle">Manage the content used by the current Chomok website About page.</p>
    </div>
    @if($restaurantSetting?->website_url)
        <a href="{{ rtrim($restaurantSetting->website_url, '/') }}/about" target="_blank" rel="noopener" class="btn-admin-outline"><i class="bi bi-box-arrow-up-right"></i> View About Page</a>
    @endif
</div>

@include('admin.include.form-errors')

<form action="{{ route('admin.website.about.update') }}" method="post" enctype="multipart/form-data" data-form-loader data-loader-text="Saving about page content...">
    @csrf
    @method('PUT')

    <div class="admin-card form-loader-surface">
        <div class="admin-card-header"><div><h2 class="admin-card-title">SEO</h2><p class="form-section-hint mb-0">Page title and search/social description.</p></div></div>
        <div class="form-group-admin"><label class="form-label-admin">Meta Title</label><input type="text" name="meta_title" class="form-control-admin" value="{{ old('meta_title', $content->meta_title) }}"></div>
        <div class="form-group-admin"><label class="form-label-admin">Meta Description</label><textarea name="meta_description" class="form-control-admin" rows="3">{{ old('meta_description', $content->meta_description) }}</textarea></div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-card-title">Story Hero</h2>
                <p class="form-section-hint mb-0">Matches the “Homemade And Hearty Feasts” section in about.php.</p>
            </div>
        </div>

        <div class="form-row-admin">
            <div class="form-group-admin">
                <label class="form-label-admin">Heading Before Image</label>
                <input type="text" name="story_heading_prefix" class="form-control-admin" value="{{ old('story_heading_prefix', $content->story_heading_prefix) }}">
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Heading After Image</label>
                <textarea name="story_heading_suffix" class="form-control-admin" rows="2">{{ old('story_heading_suffix', $content->story_heading_suffix) }}</textarea>
                <div class="form-text">Use a new line where the website heading should break.</div>
            </div>
        </div>

        <div class="form-row-admin">
            <div class="form-group-admin">
                <label class="form-label-admin">Heading Food Image</label>
                <div class="upload-dropzone">
                    <img id="storyHeadingPreview" src="{{ $content->story_heading_image ? asset($content->story_heading_image) : '' }}" alt="" class="upload-preview" style="{{ $content->story_heading_image ? 'display:block' : '' }}">
                    <i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div>
                    <input type="file" name="story_heading_image" accept="image/*" class="image-preview-input" data-about-image data-preview="#storyHeadingPreview">
                </div>
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Left Food Image</label>
                <div class="upload-dropzone">
                    <img id="storyLeftPreview" src="{{ $content->story_left_image ? asset($content->story_left_image) : '' }}" alt="" class="upload-preview" style="{{ $content->story_left_image ? 'display:block' : '' }}">
                    <i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div>
                    <input type="file" name="story_left_image" accept="image/*" class="image-preview-input" data-about-image data-preview="#storyLeftPreview">
                </div>
            </div>
        </div>

        <div class="form-row-admin">
            <div class="form-group-admin">
                <label class="form-label-admin">Section Index</label>
                <input type="text" name="story_index" class="form-control-admin" value="{{ old('story_index', $content->story_index) }}">
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Menu Button Text</label>
                <input type="text" name="story_button_text" class="form-control-admin" value="{{ old('story_button_text', $content->story_button_text) }}">
            </div>
        </div>
        <div class="form-group-admin">
            <label class="form-label-admin">Menu Button Link</label>
            <input type="text" name="story_button_link" class="form-control-admin" value="{{ old('story_button_link', $content->story_button_link) }}" placeholder="menu.php">
        </div>
        <div class="form-group-admin">
            <label class="form-label-admin">Story Description</label>
            <textarea name="story_description" class="form-control-admin" rows="4">{{ old('story_description', $content->story_description) }}</textarea>
        </div>

        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Avatar 1 Initial</label><input type="text" maxlength="5" name="story_avatar_1" class="form-control-admin" value="{{ old('story_avatar_1', $content->story_avatar_1) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Avatar 2 Initial</label><input type="text" maxlength="5" name="story_avatar_2" class="form-control-admin" value="{{ old('story_avatar_2', $content->story_avatar_2) }}"></div>
        </div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Avatar 3 Initial</label><input type="text" maxlength="5" name="story_avatar_3" class="form-control-admin" value="{{ old('story_avatar_3', $content->story_avatar_3) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Trusted Families Text</label><input type="text" name="story_trust_label" class="form-control-admin" value="{{ old('story_trust_label', $content->story_trust_label) }}"></div>
        </div>
        <div class="form-group-admin mb-0">
            <label class="form-label-admin">Right Food Image</label>
            <div class="upload-dropzone">
                <img id="storyRightPreview" src="{{ $content->story_right_image ? asset($content->story_right_image) : '' }}" alt="" class="upload-preview" style="{{ $content->story_right_image ? 'display:block' : '' }}">
                <i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div>
                <input type="file" name="story_right_image" accept="image/*" class="image-preview-input" data-about-image data-preview="#storyRightPreview">
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Mission &amp; Vision</h2><p class="form-section-hint mb-0">Text and three interior photos used by the mission/vision grid.</p></div></div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Mission Label</label><input type="text" name="mission_label" class="form-control-admin" value="{{ old('mission_label', $content->mission_label) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Vision Label</label><input type="text" name="vision_label" class="form-control-admin" value="{{ old('vision_label', $content->vision_label) }}"></div>
        </div>
        <div class="form-group-admin"><label class="form-label-admin">Mission Text</label><textarea name="mission_text" class="form-control-admin" rows="4">{{ old('mission_text', $content->mission_text) }}</textarea></div>
        <div class="form-group-admin"><label class="form-label-admin">Vision Text</label><textarea name="vision_text" class="form-control-admin" rows="3">{{ old('vision_text', $content->vision_text) }}</textarea></div>

        <div class="form-row-admin">
            <div class="form-group-admin">
                <label class="form-label-admin">Main Interior Image</label>
                <div class="upload-dropzone"><img id="missionMainPreview" src="{{ $content->mission_main_image ? asset($content->mission_main_image) : '' }}" alt="" class="upload-preview" style="{{ $content->mission_main_image ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div><input type="file" name="mission_main_image" accept="image/*" class="image-preview-input" data-about-image data-preview="#missionMainPreview"></div>
            </div>
            <div class="form-group-admin">
                <label class="form-label-admin">Secondary Interior Image 1</label>
                <div class="upload-dropzone"><img id="missionSecondOnePreview" src="{{ $content->mission_secondary_image_1 ? asset($content->mission_secondary_image_1) : '' }}" alt="" class="upload-preview" style="{{ $content->mission_secondary_image_1 ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div><input type="file" name="mission_secondary_image_1" accept="image/*" class="image-preview-input" data-about-image data-preview="#missionSecondOnePreview"></div>
            </div>
        </div>
        <div class="form-group-admin mb-0">
            <label class="form-label-admin">Secondary Interior Image 2</label>
            <div class="upload-dropzone"><img id="missionSecondTwoPreview" src="{{ $content->mission_secondary_image_2 ? asset($content->mission_secondary_image_2) : '' }}" alt="" class="upload-preview" style="{{ $content->mission_secondary_image_2 ? 'display:block' : '' }}"><i class="bi bi-cloud-arrow-up"></i><div class="upload-dropzone-text">Click or drag an image to upload</div><input type="file" name="mission_secondary_image_2" accept="image/*" class="image-preview-input" data-about-image data-preview="#missionSecondTwoPreview"></div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Services Highlight</h2><p class="form-section-hint mb-0">Matches “We Serve More Than Just Great Food” and its four service cards.</p></div></div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Heading Line 1</label><input type="text" name="services_heading_line_1" class="form-control-admin" value="{{ old('services_heading_line_1', $content->services_heading_line_1) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Heading Line 2</label><input type="text" name="services_heading_line_2" class="form-control-admin" value="{{ old('services_heading_line_2', $content->services_heading_line_2) }}"></div>
        </div>
        <div class="form-group-admin"><label class="form-label-admin">Description</label><textarea name="services_description" class="form-control-admin" rows="3">{{ old('services_description', $content->services_description) }}</textarea></div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Button Text</label><input type="text" name="services_button_text" class="form-control-admin" value="{{ old('services_button_text', $content->services_button_text) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Button Link</label><input type="text" name="services_button_link" class="form-control-admin" value="{{ old('services_button_link', $content->services_button_link) }}"></div>
        </div>

        @for($service = 1; $service <= 4; $service++)
            <div class="border rounded-3 p-3 mb-3">
                <div class="fw-semibold mb-3">Service Card {{ $service }}</div>
                <div class="form-group-admin"><label class="form-label-admin">Title</label><input type="text" name="service_{{ $service }}_title" class="form-control-admin" value="{{ old('service_'.$service.'_title', data_get($content, 'service_'.$service.'_title')) }}"></div>
                <div class="form-group-admin mb-0"><label class="form-label-admin">Description</label><textarea name="service_{{ $service }}_text" class="form-control-admin" rows="2">{{ old('service_'.$service.'_text', data_get($content, 'service_'.$service.'_text')) }}</textarea></div>
            </div>
        @endfor
    </div>

    <div class="admin-card">
        <div class="admin-card-header"><div><h2 class="admin-card-title">Customer Reviews</h2><p class="form-section-hint mb-0">Controls the testimonial heading, summary and the three review cards in about.php.</p></div></div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Eyebrow</label><input type="text" name="reviews_eyebrow" class="form-control-admin" value="{{ old('reviews_eyebrow', $content->reviews_eyebrow) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Rating Summary</label><input type="text" name="reviews_summary" class="form-control-admin" value="{{ old('reviews_summary', $content->reviews_summary) }}"></div>
        </div>
        <div class="form-row-admin">
            <div class="form-group-admin"><label class="form-label-admin">Heading Line 1</label><input type="text" name="reviews_heading_line_1" class="form-control-admin" value="{{ old('reviews_heading_line_1', $content->reviews_heading_line_1) }}"></div>
            <div class="form-group-admin"><label class="form-label-admin">Heading Line 2</label><input type="text" name="reviews_heading_line_2" class="form-control-admin" value="{{ old('reviews_heading_line_2', $content->reviews_heading_line_2) }}"></div>
        </div>
        <div class="form-group-admin"><label class="form-label-admin">Intro Text</label><textarea name="reviews_subtext" class="form-control-admin" rows="2">{{ old('reviews_subtext', $content->reviews_subtext) }}</textarea></div>

        @for($review = 1; $review <= 3; $review++)
            <div class="border rounded-3 p-3 mb-3">
                <div class="fw-semibold mb-3">Review {{ $review }}</div>
                <div class="form-row-admin">
                    <div class="form-group-admin"><label class="form-label-admin">Rating (1–5)</label><input type="number" min="1" max="5" name="review_{{ $review }}_rating" class="form-control-admin" value="{{ old('review_'.$review.'_rating', data_get($content, 'review_'.$review.'_rating')) }}"></div>
                    <div class="form-group-admin"><label class="form-label-admin">Avatar Initial</label><input type="text" maxlength="5" name="review_{{ $review }}_initial" class="form-control-admin" value="{{ old('review_'.$review.'_initial', data_get($content, 'review_'.$review.'_initial')) }}"></div>
                </div>
                <div class="form-group-admin"><label class="form-label-admin">Review Quote</label><textarea name="review_{{ $review }}_quote" class="form-control-admin" rows="3">{{ old('review_'.$review.'_quote', data_get($content, 'review_'.$review.'_quote')) }}</textarea></div>
                <div class="form-row-admin">
                    <div class="form-group-admin"><label class="form-label-admin">Customer Name</label><input type="text" name="review_{{ $review }}_name" class="form-control-admin" value="{{ old('review_'.$review.'_name', data_get($content, 'review_'.$review.'_name')) }}"></div>
                    <div class="form-group-admin"><label class="form-label-admin">Customer Role / Location</label><input type="text" name="review_{{ $review }}_role" class="form-control-admin" value="{{ old('review_'.$review.'_role', data_get($content, 'review_'.$review.'_role')) }}"></div>
                </div>
            </div>
        @endfor

        @can('website-content.edit')
            <div class="form-actions-admin"><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save About Page</button></div>
        @endcan
    </div>
</form>
@endsection

@push('scripts')
<script>
(function () {
    document.querySelectorAll('[data-about-image]').forEach(function (input) {
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            var preview = document.querySelector(input.dataset.preview || '');
            if (!file || !preview) return;
            var reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    });
})();
</script>
@endpush
