<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'لوحة التحكم')</title>

    <!-- Metronic Global CSS (RTL support) -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.rtl.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.rtl.css') }}" type="text/css" />

    <style>
        /* ضبط مسافات الهيكل تلقائياً */
        body { font-family: 'Cairo', sans-serif, system-ui; }
        .aside-enabled .wrapper { padding-right: 265px !important; padding-left: 0 !important; }
        .aside { width: 265px !important; position: fixed; top: 0; bottom: 0; right: 0; z-index: 100; }
        @media (max-width: 991.98px) {
            .aside-enabled .wrapper { padding-right: 0 !important; }
        }
    </style>

    @stack('styles')
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-enabled aside-fixed">
    
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            
            <!-- القائمة الجانبية -->
            @include('layouts.sidebar')

            <!-- تغليف المحتوى الرئيسي -->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                
                <!-- الهيدر -->
                @include('layouts.header')

                <!-- محتوى الصفحة -->
                <div class="content d-flex flex-column flex-column-fluid p-6" id="kt_content">
                    @yield('content')
                </div>

                <!-- الفوتر -->
                @include('layouts.footer')

            </div>
        </div>
    </div>

    <!-- Metronic Global JS -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    @stack('scripts')
</body>
</html>