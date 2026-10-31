<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', __('messages.dashboard'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Tajawal:wght@400;500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Metronic Global CSS (Dynamic RTL / LTR) -->
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.rtl.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.rtl.css') }}" type="text/css" />
        <style>
            body { font-family: 'Cairo', 'Tajawal', sans-serif, system-ui; }
            .aside-enabled .wrapper { padding-right: 265px !important; padding-left: 0 !important; }
            .aside { width: 265px !important; position: fixed; top: 0; bottom: 0; right: 0; left: auto; z-index: 100; }
            @media (max-width: 991.98px) {
                .aside-enabled .wrapper { padding-right: 0 !important; }
            }
        </style>
    @else
        <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.bundle.css') }}" type="text/css" />
        <style>
            body { font-family: 'Inter', system-ui, sans-serif; }
            .aside-enabled .wrapper { padding-left: 265px !important; padding-right: 0 !important; }
            .aside { width: 265px !important; position: fixed; top: 0; bottom: 0; left: 0; right: auto; z-index: 100; }
            @media (max-width: 991.98px) {
                .aside-enabled .wrapper { padding-left: 0 !important; }
            }
        </style>
    @endif

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

    <!-- Pusher & Laravel Echo CDN -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

    <!-- Live Notification Toast Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div id="liveOrderToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fs-6" id="liveOrderToastBody">
                    <!-- الرسالة الفورية ستظهر هنا -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        const pusherKey = "{{ config('broadcasting.connections.pusher.key') }}";
        const pusherCluster = "{{ config('broadcasting.connections.pusher.options.cluster') }}";

        if (pusherKey && pusherKey.trim() !== "") {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: pusherKey,
                cluster: pusherCluster,
                forceTLS: true
            });

            window.Echo.channel('orders')
                .listen('.OrderPlacedEvent', (data) => {
                    console.log('⚡ Realtime Order Event Received:', data);
                    
                    // 1. إظهار الإشعار العائم (Toast Popup)
                    const toastEl = document.getElementById('liveOrderToast');
                    const toastBody = document.getElementById('liveOrderToastBody');
                    if (toastEl && toastBody) {
                        toastBody.innerHTML = `<strong>⚡ طلب جديد فوري (WebSocket):</strong><br>${data.message}`;
                        const toast = new bootstrap.Toast(toastEl, { delay: 25000 });
                        toast.show();
                    }

                    // 2. تحديث عداد جرس الإشعارات (+1)
                    const badge = document.getElementById('unreadNotificationsBadge');
                    if (badge) {
                        let currentCount = parseInt(badge.innerText.trim()) || 0;
                        badge.innerText = currentCount + 1;
                        badge.classList.remove('d-none');
                    }

                    // 3. إضافة الإشعار الجديد في أعلى القائمة المنسدلة للجرس فوراً
                    const list = document.getElementById('notificationsDropdownList');
                    const placeholder = document.getElementById('noNotificationsPlaceholder');
                    if (placeholder) {
                        placeholder.remove();
                    }

                    if (list) {
                        const newNotificationHtml = `
                            <a href="/orders/${data.order_id}" class="d-block p-3 border-bottom text-decoration-none bg-light bg-opacity-75 transition-all" style="border-color: #f1f1f4 !important; animation: fadeIn 0.5s;">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="mt-1">
                                        <i class="bi bi-bag-check-fill text-success fs-6"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-1 text-dark fw-bold" style="font-size: 13px; line-height: 1.4;">
                                            ${data.message}
                                        </p>
                                        <span class="text-muted" style="font-size: 11px;">
                                            <i class="bi bi-clock me-1"></i> الآن
                                        </span>
                                    </div>
                                    <span class="badge rounded-circle bg-primary p-1 mt-1"></span>
                                </div>
                            </a>
                        `;
                        list.insertAdjacentHTML('afterbegin', newNotificationHtml);
                    }
                });
        }
    </script>

    @stack('scripts')
</body>
</html>