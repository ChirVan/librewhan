<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- QUICK TEST: CDN fallback -->
        {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> Should be removed, might conflict with assets/css/bootstrap.min.css, by 天使   --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        @php
            $bs = file_exists(public_path('assets/css/bootstrap.min.css')) ? filemtime(public_path('assets/css/bootstrap.min.css')) : null;
            $pl = file_exists(public_path('assets/css/plugins.min.css')) ? filemtime(public_path('assets/css/plugins.min.css')) : null;
            $ka = file_exists(public_path('assets/css/kaiadmin.min.css')) ? filemtime(public_path('assets/css/kaiadmin.min.css')) : null;
        @endphp

        <!-- Local legacy styles (cache-busted) -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}{{ $bs ? '?v='.$bs : '' }}">
        <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}{{ $pl ? '?v='.$pl : '' }}">
        <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}{{ $ka ? '?v='.$ka : '' }}">

        <!-- Vite / app styles -->
        @if(app()->environment('local') || file_exists(public_path('build')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

            <!-- Responsive sidebar/layout helpers (custom) -->
        <style>
            :root{
                --sidebar-width: 260px;         /* full width */
                --sidebar-collapsed-width: 84px; /* collapsed width */
                --header-height: 70px;         /* adjust if your header is taller */
            }

            /* Base layout */
            .app-shell { min-height:100vh; display:flex; flex-direction:column; }

            /* Sidebar (fixed) */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: var(--sidebar-width);
                overflow-y: auto;
                z-index: 1040;
                transition: width .18s ease, transform .18s ease;
                background-color: #0f1720; /* keep your existing dark theme or remove */
            }

            /* collapsed state */
            body.sidebar-collapsed .sidebar { width: var(--sidebar-collapsed-width); }
            body.sidebar-collapsed .sidebar .sidebar-wrapper .sidebar-content .nav .nav-item .sub-item { display: none; }
            body.sidebar-collapsed .sidebar .logo-text { opacity: 0; transition: opacity .15s; }

            /* Main content wrapper sits to the right of the sidebar */
            .content-wrapper {
                margin-left: var(--sidebar-width);
                transition: margin-left .18s ease;
                min-height: 100vh;
                display: block;
            }

            /* Collapsed state shrinks the content margin */
            body.sidebar-collapsed .content-wrapper {
                margin-left: var(--sidebar-collapsed-width);
            }

            /* Keep header above content and sidebar (if you want header to overlay) */
            .main-header {
                position: relative;
                z-index: 1050;
                /* if you want a fixed header, change to fixed and add top margin to .content-wrapper */
            }

            /* Footer should appear after content; no change usually needed */
            footer.footer { z-index: 1000; }

            /* Mobile: sidebar overlays content and content has no left margin */
            @media (max-width: 991.98px) {
                .sidebar {
                transform: translateX(-100%);
                box-shadow: 0 6px 24px rgba(2,6,23,0.6);
                }

                body.sidebar-open .sidebar {
                transform: translateX(0);
                }

                .content-wrapper {
                margin-left: 0 !important;
                }

                /* overlay for mobile will be injected dynamically */
            }

            /* Ensure content doesn't hide under header if you later change header position to fixed */
            .content-inner {
                padding: 18px; /* page spacing */
            }
        </style>


        @stack('head')
    </head>
    <body class="font-sans antialiased">

        <div class="app-shell bg-gray-100">
            {{-- only show app chrome (navbar/sidebar/etc) to authenticated users --}}
            @auth
                @include('layouts.sidebar') {{-- sidebar must be before content in markup (we style it fixed) --}}
            @endauth

            <div class="content-wrapper" id="app-content">
                @auth
                    @include('layouts.header')                    
                @endauth
                
                <!-- Page Heading -->
                @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                @endif
                
                <!-- Page Content -->
                <main class="content-inner">
                    @isset($slot)
                    {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </main>
                
                @auth
                    @include('layouts.footer')
                @endauth
            </div>
        </div>

        <!-- Core JS (load for all pages) -->
        <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

        {{-- Only load heavy dashboard scripts for authenticated pages (they manipulate dashboard DOM) --}}
        @auth
            <script>
                // guard: ensure jQuery exists before running dashboard scripts
                (function(){
                    if (typeof jQuery === 'undefined') return;
                    // kaiadmin and demo rely on plugins; include them only for authenticated UI
                })();
            </script>
            {{-- <script src="{{ asset('assets/js/chart.umd.min.js') }}"></script> {{-- doesn't exist bro wtf -天使 --}}
            <script src="{{ asset('assets/js/kaiadmin.js') }}"></script>
            <script src="{{ asset('assets/js/demo.js') }}"></script>
        @endauth


        {{-- MOBILE SIDEBAR, CAN BE REMOVED --}}
            {{-- <script>
                (function () {
                    const body = document.body;
                    const SIDEBAR_COLLAPSED_KEY = 'librewhan.sidebar.collapsed';
                    const sidebarCollapsed = localStorage.getItem(SIDEBAR_COLLAPSED_KEY) === '1';

                    function setCollapsed(collapsed) {
                    if (collapsed) {
                        body.classList.add('sidebar-collapsed');
                        localStorage.setItem(SIDEBAR_COLLAPSED_KEY, '1');
                    } else {
                        body.classList.remove('sidebar-collapsed');
                        localStorage.removeItem(SIDEBAR_COLLAPSED_KEY);
                    }
                    }

                    // initialize
                    setCollapsed(sidebarCollapsed);

                    // toggler buttons (existing in header/sidebar)
                    function attachToggles() {
                    document.querySelectorAll('.toggle-sidebar, .sidenav-toggler').forEach(btn => {
                        btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        // On mobile, open overlay rather than collapse
                        if (window.matchMedia('(max-width: 991.98px)').matches) {
                            // toggle overlay class
                            if (body.classList.contains('sidebar-open')) {
                            closeMobileSidebar();
                            } else {
                            openMobileSidebar();
                            }
                            return;
                        }

                        // desktop: toggle collapsed
                        const collapsed = body.classList.contains('sidebar-collapsed');
                        setCollapsed(!collapsed);
                        });
                    });
                    }

                    // Mobile overlay helpers
                    let overlayEl = null;
                    function openMobileSidebar() {
                    body.classList.add('sidebar-open');
                    // inject overlay
                    if (!overlayEl) {
                        overlayEl = document.createElement('div');
                        overlayEl.className = 'sidebar-mobile-overlay';
                        Object.assign(overlayEl.style, {
                        position: 'fixed',
                        inset: 0,
                        background: 'rgba(0,0,0,0.45)',
                        zIndex: 1038
                        });
                        overlayEl.addEventListener('click', closeMobileSidebar);
                        document.body.appendChild(overlayEl);
                    }
                    }
                    function closeMobileSidebar() {
                    body.classList.remove('sidebar-open');
                    if (overlayEl && overlayEl.parentNode) {
                        overlayEl.parentNode.removeChild(overlayEl);
                        overlayEl = null;
                    }
                    }

                    // close mobile sidebar on resize if desktop widow
                    window.addEventListener('resize', function () {
                    if (window.matchMedia('(min-width: 992px)').matches) {
                        body.classList.remove('sidebar-open');
                        if (overlayEl && overlayEl.parentNode) {
                        overlayEl.parentNode.removeChild(overlayEl);
                        overlayEl = null;
                        }
                    }
                    });

                    // attach toggles now
                    attachToggles();

                    // optional: allow ESC to close mobile sidebar
                    document.addEventListener('keydown', function (ev) {
                    if (ev.key === 'Escape') {
                        if (body.classList.contains('sidebar-open')) closeMobileSidebar();
                    }
                    });
                })();
            </script> --}}


        @stack('scripts')
    </body>
</html>