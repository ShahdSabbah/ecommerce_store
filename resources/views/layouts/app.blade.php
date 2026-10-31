<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('messages.site_name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        <!-- Bootstrap 5 (RTL/LTR dynamic) -->
        @if(app()->getLocale() === 'ar')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
        @else
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        @endif

        <style>
            body { 
                font-family: {{ app()->getLocale() === 'ar' ? "'Tajawal', 'Cairo', sans-serif" : "'Inter', system-ui, sans-serif" }}; 
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
                        
                        // 1. إظهار الإشعار العائم (Toast Popup) بمدة 25 ثانية
                        const toastEl = document.getElementById('liveOrderToast');
                        const toastBody = document.getElementById('liveOrderToastBody');
                        if (toastEl && toastBody) {
                            toastBody.innerHTML = `<strong>⚡ طلب جديد فوري (WebSocket):</strong><br>${data.message}`;
                            const toast = new bootstrap.Toast(toastEl, { delay: 25000 });
                            toast.show();
                        }

                        // 2. تحديث عداد جرس الإشعارات (+1)
                        const badge = document.getElementById('navUnreadNotificationsBadge') || document.getElementById('unreadNotificationsBadge');
                        if (badge) {
                            let currentCount = parseInt(badge.innerText.trim()) || 0;
                            badge.innerText = currentCount + 1;
                            badge.classList.remove('d-none');
                        }

                        // 3. إضافة الإشعار الجديد في أعلى القائمة المنسدلة للجرس فوراً
                        const list = document.getElementById('navNotificationsDropdownList') || document.getElementById('notificationsDropdownList');
                        const placeholder = document.getElementById('navNoNotificationsPlaceholder') || document.getElementById('noNotificationsPlaceholder');
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
    </body>
</html>