@extends('admin.master.master')

@section('title', 'Website Content')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css">
<style>
    .website-content-editor .note-editor.note-frame {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: none;
    }
    .website-content-editor .note-toolbar {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        padding: 8px;
    }
    .website-content-editor .note-editing-area .note-editable {
        min-height: 280px;
        background: #fff;
        color: #111827;
        font-size: 14px;
        line-height: 1.7;
        padding: 18px;
    }
    .website-content-editor .note-statusbar {
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
    }
    .website-content-editor .note-btn {
        border-color: #e5e7eb;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Website Content</span></div>
        <h1 class="page-title">Website Content</h1>
        <p class="page-subtitle">Manage Terms &amp; Conditions, Privacy Policy, Refund Policy, and Delivery Info shown on the website.</p>
    </div>
</div>

@include('admin.include.form-errors')

<form action="{{ route('admin.website.content.update') }}" method="post" data-form-loader data-loader-text="Saving website content...">
    @csrf
    @method('PUT')

    <div class="admin-card form-loader-surface website-content-editor">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-card-title">Terms &amp; Conditions</h2>
                <p class="form-section-hint mb-0">Edit the complete terms and conditions using the rich-text editor.</p>
            </div>
        </div>
        <div class="form-group-admin mb-0">
            <textarea name="terms_and_conditions" id="termsAndConditions" class="summernote-editor">{{ old('terms_and_conditions', $content->terms_and_conditions) }}</textarea>
        </div>
    </div>

    <div class="admin-card website-content-editor">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-card-title">Privacy Policy</h2>
                <p class="form-section-hint mb-0">Manage the website privacy policy and customer data information.</p>
            </div>
        </div>
        <div class="form-group-admin mb-0">
            <textarea name="privacy_policy" id="privacyPolicy" class="summernote-editor">{{ old('privacy_policy', $content->privacy_policy) }}</textarea>
        </div>
    </div>

    <div class="admin-card website-content-editor">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-card-title">Refund Policy</h2>
                <p class="form-section-hint mb-0">Manage refund, return, cancellation, and eligibility information shown on the website.</p>
            </div>
        </div>
        <div class="form-group-admin mb-0">
            <textarea name="refund_policy" id="refundPolicy" class="summernote-editor">{{ old('refund_policy', $content->refund_policy) }}</textarea>
        </div>
    </div>

    <div class="admin-card website-content-editor">
        <div class="admin-card-header">
            <div>
                <h2 class="admin-card-title">Delivery Info</h2>
                <p class="form-section-hint mb-0">Manage delivery areas, timing, charges, conditions, and related information.</p>
            </div>
        </div>
        <div class="form-group-admin mb-0">
            <textarea name="delivery_info" id="deliveryInfo" class="summernote-editor">{{ old('delivery_info', $content->delivery_info) }}</textarea>
        </div>

        @can('website-content.edit')
            <div class="form-actions-admin mt-4">
                <button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save Website Content</button>
            </div>
        @endcan
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    $(function () {
        $('.summernote-editor').summernote({
            height: 300,
            minHeight: 220,
            placeholder: 'Write website content here...',
            dialogsInBody: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush
