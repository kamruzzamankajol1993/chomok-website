@extends('admin.master.master')
@section('title','Settings')
@section('content')
@php
    $restaurantFields = ['restaurant_name','website_url','admin_panel_url','address','phone','opening_time','closing_time','header_hours_text','logo','icon'];
    $taxFields = ['tax_rate','tax_label','tax_registration_number','service_charge','tax_included'];
    $activeTab = collect($taxFields)->contains(fn ($field) => $errors->has($field))
        ? 'tax'
        : (collect(['invoice_prefix','invoice_starting_number','invoice_footer_note','print_paper_size','show_logo_invoice'])->contains(fn ($field) => $errors->has($field)) ? 'invoice' : 'restaurant');
@endphp
<div class="page-header">
    <div>
        <div class="breadcrumb-admin"><a href="{{ route('admin.dashboard') }}">Dashboard</a> / <span class="current">Settings</span></div>
        <h1 class="page-title">Settings</h1>
        <p class="page-subtitle">Manage restaurant identity, tax rules and invoice configuration.</p>
    </div>
</div>
@include('admin.include.form-errors')
<form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" class="form-loader-host" data-form-loader data-loader-text="Saving settings...">
    @csrf
    @method('PUT')
    @cannot('setting.edit')<div class="alert alert-warning">You have read-only access to settings.</div>@endcannot

    <div class="admin-card settings-tab-card form-loader-surface">
        <div class="settings-tab-header">
            <div>
                <h2 class="admin-card-title">System Configuration</h2>
                <p class="form-help mb-0">Select a tab to manage each settings group.</p>
            </div>
            <div class="settings-save-status"><i class="bi bi-shield-check"></i> Protected configuration</div>
        </div>

        <ul class="nav settings-tabs" id="settings-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'restaurant' ? 'active' : '' }}" id="restaurant-tab" data-bs-toggle="tab" data-bs-target="#restaurant-pane" type="button" role="tab" aria-controls="restaurant-pane" aria-selected="{{ $activeTab === 'restaurant' ? 'true' : 'false' }}"><i class="bi bi-shop"></i><span>Restaurant Information</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'tax' ? 'active' : '' }}" id="tax-tab" data-bs-toggle="tab" data-bs-target="#tax-pane" type="button" role="tab" aria-controls="tax-pane" aria-selected="{{ $activeTab === 'tax' ? 'true' : 'false' }}"><i class="bi bi-percent"></i><span>Tax Configuration</span></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'invoice' ? 'active' : '' }}" id="invoice-tab" data-bs-toggle="tab" data-bs-target="#invoice-pane" type="button" role="tab" aria-controls="invoice-pane" aria-selected="{{ $activeTab === 'invoice' ? 'true' : 'false' }}"><i class="bi bi-receipt"></i><span>Invoice Settings</span></button>
            </li>
        </ul>

        <fieldset @cannot('setting.edit') disabled @endcannot>
            <div class="tab-content settings-tab-content" id="settings-tab-content">
                <div class="tab-pane fade {{ $activeTab === 'restaurant' ? 'show active' : '' }}" id="restaurant-pane" role="tabpanel" aria-labelledby="restaurant-tab" tabindex="0">
                    <div class="settings-pane-heading"><div class="form-panel-icon"><i class="bi bi-shop"></i></div><div><h3>Restaurant Information</h3><p>Basic identity, links, branding and operating hours.</p></div></div>
                    <div class="form-grid-two">
                        <div class="form-group-admin"><label class="form-label-admin">Restaurant Name <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-building"></i><input type="text" name="restaurant_name" class="form-control-admin" value="{{ old('restaurant_name',$setting->restaurant_name) }}" required></div></div>
                        <div class="form-group-admin"><label class="form-label-admin">Website Link</label><div class="form-control-icon-wrap"><i class="bi bi-globe2"></i><input type="url" name="website_url" class="form-control-admin" value="{{ old('website_url',$setting->website_url) }}" placeholder="https://example.com"></div></div>
                    </div>
                    <div class="form-group-admin"><label class="form-label-admin">Admin Panel Link</label><div class="form-control-icon-wrap"><i class="bi bi-link-45deg"></i><input type="url" name="admin_panel_url" class="form-control-admin" value="{{ old('admin_panel_url',$setting->admin_panel_url) }}" placeholder="https://example.com/admin"></div></div>
                    <div class="form-group-admin"><label class="form-label-admin">Address</label><div class="form-control-icon-wrap"><i class="bi bi-geo-alt"></i><textarea name="address" rows="2" class="form-control-admin" placeholder="Restaurant address">{{ old('address',$setting->address) }}</textarea></div></div>
                    <div class="form-group-admin"><label class="form-label-admin">Phone / WhatsApp Number</label><div class="form-control-icon-wrap"><i class="bi bi-whatsapp"></i><input type="tel" name="phone" class="form-control-admin" value="{{ old('phone',$setting->phone) }}" placeholder="+8801XXXXXXXXX"></div><div class="form-help">This number is used by the floating WhatsApp button on the website.</div></div>
                    <div class="form-grid-two">
                        <div class="form-group-admin"><label class="form-label-admin">Opening Time</label><div class="form-control-icon-wrap"><i class="bi bi-clock"></i><input type="text" name="opening_time" class="form-control-admin time-picker" value="{{ old('opening_time',$setting->opening_time ? substr($setting->opening_time,0,5) : '') }}"></div></div>
                        <div class="form-group-admin"><label class="form-label-admin">Closing Time</label><div class="form-control-icon-wrap"><i class="bi bi-clock-history"></i><input type="text" name="closing_time" class="form-control-admin time-picker" value="{{ old('closing_time',$setting->closing_time ? substr($setting->closing_time,0,5) : '') }}"></div></div>
                    </div>
                    <div class="form-group-admin">
                        <label class="form-label-admin">Header Opening &amp; Closing Time Text</label>
                        <div class="form-control-icon-wrap"><i class="bi bi-clock-fill"></i><input type="text" name="header_hours_text" class="form-control-admin" value="{{ old('header_hours_text', $setting->header_hours_text ?: 'Opening & Closing time (Saturday to Thursday) : 12PM to 10:30PM (Friday 2PM to 10:30PM)') }}" maxlength="255"></div>
                        <div class="form-help">Shown in the website top header. Example: Opening &amp; Closing time (Saturday to Thursday) : 12PM to 10:30PM (Friday 2PM to 10:30PM)</div>
                    </div>
                    <div class="form-grid-two settings-upload-grid">
                        <div class="form-group-admin mb-0"><label class="form-label-admin">Restaurant Logo</label><label class="upload-dropzone compact-upload"><input type="file" name="logo" accept="image/*" class="image-preview-input" data-preview="#logo-preview"><img id="logo-preview" class="upload-preview" src="{{ $setting->logo ? asset($setting->logo) : '' }}" @if(!$setting->logo) style="display:none" @endif alt="Logo preview"><div class="upload-dropzone-text"><i class="bi bi-image"></i><span>Choose logo</span><small>Preview before upload</small></div></label></div>
                        <div class="form-group-admin mb-0"><label class="form-label-admin">Browser Icon</label><label class="upload-dropzone compact-upload"><input type="file" name="icon" accept="image/*" class="image-preview-input" data-preview="#icon-preview"><img id="icon-preview" class="upload-preview icon-preview" src="{{ $setting->icon ? asset($setting->icon) : '' }}" @if(!$setting->icon) style="display:none" @endif alt="Icon preview"><div class="upload-dropzone-text"><i class="bi bi-app-indicator"></i><span>Choose icon</span><small>Square image recommended</small></div></label></div>
                    </div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'tax' ? 'show active' : '' }}" id="tax-pane" role="tabpanel" aria-labelledby="tax-tab" tabindex="0">
                    <div class="settings-pane-heading"><div class="form-panel-icon"><i class="bi bi-percent"></i></div><div><h3>Tax Configuration</h3><p>Configure VAT, registration and service-charge rules.</p></div></div>
                    <div class="form-grid-two">
                        <div class="form-group-admin"><label class="form-label-admin">VAT / Tax Rate (%) <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-percent"></i><input type="number" step="0.01" min="0" max="100" name="tax_rate" class="form-control-admin" value="{{ old('tax_rate',$setting->tax_rate) }}" required></div></div>
                        <div class="form-group-admin"><label class="form-label-admin">Tax Label <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-tag"></i><input type="text" name="tax_label" class="form-control-admin" value="{{ old('tax_label',$setting->tax_label) }}" required></div></div>
                    </div>
                    <div class="form-grid-two">
                        <div class="form-group-admin"><label class="form-label-admin">Tax Registration #</label><div class="form-control-icon-wrap"><i class="bi bi-card-text"></i><input type="text" name="tax_registration_number" class="form-control-admin" value="{{ old('tax_registration_number',$setting->tax_registration_number) }}"></div></div>
                        <div class="form-group-admin"><label class="form-label-admin">Service Charge (%) <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-cash-stack"></i><input type="number" step="0.01" min="0" max="100" name="service_charge" class="form-control-admin" value="{{ old('service_charge',$setting->service_charge) }}" required></div></div>
                    </div>
                    <div class="form-group-admin d-flex align-items-center justify-content-between switch-row mb-0"><div><label class="form-label-admin mb-0">Tax Included in Price?</label><div class="form-help">Enable when displayed menu prices already include tax.</div></div><label class="form-switch-admin"><input type="checkbox" name="tax_included" value="1" @checked(old('tax_included',$setting->tax_included))><span class="switch-track"></span></label></div>
                </div>

                <div class="tab-pane fade {{ $activeTab === 'invoice' ? 'show active' : '' }}" id="invoice-pane" role="tabpanel" aria-labelledby="invoice-tab" tabindex="0">
                    <div class="settings-pane-heading"><div class="form-panel-icon"><i class="bi bi-receipt"></i></div><div><h3>Invoice Settings</h3><p>Configure invoice numbering, footer and print preferences.</p></div></div>
                    <div class="form-grid-two">
                        <div class="form-group-admin"><label class="form-label-admin">Invoice Prefix <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-hash"></i><input type="text" name="invoice_prefix" class="form-control-admin" value="{{ old('invoice_prefix',$setting->invoice_prefix) }}" required></div></div>
                        <div class="form-group-admin"><label class="form-label-admin">Starting Number <span class="required">*</span></label><div class="form-control-icon-wrap"><i class="bi bi-123"></i><input type="text" inputmode="numeric" pattern="[0-9]+" name="invoice_starting_number" class="form-control-admin" value="{{ old('invoice_starting_number', str_pad((string) max((int) $setting->invoice_starting_number, 1), 4, '0', STR_PAD_LEFT)) }}" placeholder="0001" required></div></div>
                    </div>
                    <div class="form-group-admin"><label class="form-label-admin">Invoice Footer Note</label><textarea name="invoice_footer_note" rows="4" class="form-control-admin" placeholder="Thank you for dining with us.">{{ old('invoice_footer_note',$setting->invoice_footer_note) }}</textarea></div>
                    <div class="form-grid-two align-items-end">
                        <div class="form-group-admin mb-0"><label class="form-label-admin">Print Paper Size</label><select name="print_paper_size" class="form-control-admin choices-select"><option value="80mm" @selected(old('print_paper_size',$setting->print_paper_size)==='80mm')>80mm (Thermal)</option><option value="a4" @selected(old('print_paper_size',$setting->print_paper_size)==='a4')>A4</option></select></div>
                        <div class="form-group-admin d-flex align-items-center justify-content-between switch-row mb-0"><div><label class="form-label-admin mb-0">Show Logo on Invoice</label><div class="form-help">Display the restaurant logo in printed invoices.</div></div><label class="form-switch-admin"><input type="checkbox" name="show_logo_invoice" value="1" @checked(old('show_logo_invoice',$setting->show_logo_invoice))><span class="switch-track"></span></label></div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    @can('setting.edit')
        <div class="form-actions-admin sticky-form-actions"><button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Save Settings</button></div>
    @endcan
</form>
@endsection
