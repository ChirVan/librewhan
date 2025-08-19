<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Kalibrewhan Cafe - Restaurant Management')</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon"/>

    <!-- Fonts and icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    
    <!-- Custom Branding Styles -->
    <style>
      .logo .logo-text {
        font-size: 1.25rem !important;
        letter-spacing: 0.5px;
        text-decoration: none;
      }
      .logo:hover .logo-text {
        color: #ffc107 !important;
        transition: color 0.3s ease;
      }
      
      /* Fix sidebar menu behavior - only highlight when Bootstrap collapse is shown */
      .nav-secondary .nav-item > a[aria-expanded="true"] {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-radius: 6px;
      }
      
      .nav-secondary .nav-item > a[aria-expanded="false"] {
        background-color: transparent !important;
      }
      
      .nav-secondary .nav-item > a:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
        transition: background-color 0.3s ease;
      }
      
      /* Dashboard active state */
      .nav-secondary .nav-item.active > a:not([data-bs-toggle="collapse"]) {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-radius: 6px;
      }

      .footer {
        position: relative !important;
        margin-top: auto;
      }
      

    </style>

    @stack('css')
  </head>

  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      @include('layouts.sidebar')
      <!-- End Sidebar -->

      <div class="main-panel">
        @include('layouts.header')

        @yield('content')

        @include('layouts.footer')
      </div>
    </div>

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kalibrewhan Cafe JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    @stack('scripts')
  </body>
</html>
