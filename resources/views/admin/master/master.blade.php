<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ $restaurantSetting?->restaurant_name ?? config('app.name') }}</title>
    @if($restaurantSetting?->icon)
        <link rel="icon" href="{{ asset($restaurantSetting->icon) }}">
    @endif
    <link rel="stylesheet" href="{{ asset('public/admin/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/vendor/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @include('admin.include.custom-css')
    @stack('styles')
    @include('components.password-toggle-assets')
</head>
<body>
@include('admin.include.sidebar')
<div class="sidebar-overlay"></div>

<div class="admin-main">
    @include('admin.include.topbar')
    <main class="admin-content">
        @yield('content')
    </main>
</div>

@can('order.view')
    @include('admin.include.order-notification')
@endcan

<script src="{{ asset('public/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('public/admin/js/script.js') }}"></script>
@include('admin.include.custom-js')
@include('admin.include.alerts')
@stack('scripts')
<script>
async function initWebPush() {
    if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
        return;
    }

    try {
        const registration = await navigator.serviceWorker.register("{{ asset('public/sw.js') }}");

        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            return;
        }

        const existing = await registration.pushManager.getSubscription();
        if (existing) {
            return savePushSubscription(existing);
        }

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: "{{ config('webpush.vapid.public_key') ?? env('VAPID_PUBLIC_KEY') }}"
        });

        await savePushSubscription(subscription);

    } catch (error) {
        console.error('Web Push Error:', error);
    }
}

async function savePushSubscription(subscription) {
    const key = subscription.getKey('p256dh');
    const auth = subscription.getKey('auth');

    await fetch("{{ route('admin.push.subscription') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint,
            public_key: btoa(String.fromCharCode(...new Uint8Array(key))),
            auth_token: btoa(String.fromCharCode(...new Uint8Array(auth)))
        })
    });
}

initWebPush();
</script>
</body>
</html>
