<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS') - {{ $restaurantSetting?->restaurant_name ?? config('app.name') }}</title>
    @if($restaurantSetting?->icon)
        <link rel="icon" href="{{ asset($restaurantSetting->icon) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('public/admin/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @include('admin.include.custom-css')
    @stack('styles')
    @include('components.password-toggle-assets')
</head>
<body class="pos-page-body">
@include('admin.include.pos-header')
<main class="pos-page-main">
    @yield('content')
</main>

<script src="{{ asset('public/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('public/admin/js/script.js') }}"></script>
@include('admin.include.custom-js')
@include('admin.include.alerts')
@stack('scripts')
</body>
</html>
