
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>

  <link rel="stylesheet" href="{{ asset('/') }}public/website/assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('/') }}public/website/assets/css/style.css">
  @yield('css')
</head>
<body>

<header class="site-header">

  <!-- Top info bar -->
  <div class="top-bar">
    <div class="container-fluid">
      <div class="row gx-0">
        <div class="col-lg-6 top-bar-item top-bar-left">
          <span>Monday to Saturday from 10am to 7pm</span>
        </div>
        <div class="col-lg-6 top-bar-item top-bar-right">
          <span>Our Location: Eight Avenue 487, NY...</span>
        </div>
      </div>
    </div>
  </div>

</header>

<!-- Main navigation (sticky - kept as a sibling of the header, not nested inside it,
     so it has the whole page height to stay stuck within instead of just the header's) -->
@include('website.include.header')

<!-- Floating Side Action Stack -->
@include('website.include.floating-sidebar')

<!-- Cart Offcanvas -->
@include('website.include.cart')

@yield('body')


@include('website.include.footer')

<script src="{{ asset('/') }}public/website/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('/') }}public/website/assets/js/script.js"></script>

@yield('scripts')

</body>
</html>
