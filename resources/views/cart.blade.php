<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.shopping_cart') }}</title>
    <!-- الخطوط ومكتبة Bootstrap 5 -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { 
            font-family: {{ app()->getLocale() === 'ar' ? "'Tajawal', 'Cairo', sans-serif" : "'Inter', system-ui, sans-serif" }}; 
            background-color: #f8fafc; 
        }
        .cart-card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); background: #fff; overflow: hidden; }
        .table img { width: 65px; height: 65px; object-fit: cover; border-radius: 12px; }
        .btn-checkout { background-color: #4f46e5; border: none; font-weight: 700; padding: 12px 28px; border-radius: 12px; transition: all 0.2s ease; }
        .btn-checkout:hover { background-color: #4338ca; }
    </style>
</head>
<body>

    <!-- الهيدر العلوي المشترك مع جرس الإشعارات -->
    @include('layouts.header')

    <div class="container py-5">
        <h3 class="fw-bold mb-4 text-dark">{{ __('messages.shopping_cart') }}</h3>

        {{-- تنبيه رسالة النجاح عند الحذف أو التعديل --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- تنبيه رسائل الخطأ أو تجاوز كمية المخزون --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="card cart-card mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-4">{{ __('messages.product') }}</th>
                                    <th class="py-3">{{ __('messages.price') }}</th>
                                    <th class="py-3 text-center">{{ __('messages.quantity') }}</th>
                                    <th class="py-3">{{ __('messages.subtotal') }}</th>
                                    <th class="py-3 text-center">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cart as $id => $details)
                                    @php $subtotal = $details['price'] * $details['quantity']; @endphp
                                    @php $total += $subtotal; @endphp
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center gap-3">
                                                @php
                                                    $itemImg = !empty($details['image']) 
                                                        ? (\Illuminate\Support\Str::startsWith($details['image'], 'http') ? $details['image'] : asset('storage/' . $details['image']))
                                                        : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&auto=format&fit=crop&q=80';
                                                @endphp
                                                <img src="{{ $itemImg }}" alt="{{ $details['name'] }}" class="border rounded-3 object-fit-cover shadow-sm" style="width:65px; height:65px;">
                                                <div>
                                                    <span class="fw-bold text-dark fs-6 d-block">{{ $details['name'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-secondary">{{ number_format($details['price'], 2) }} {{ __('messages.currency') }}</td>

                                        <!-- أزرار تحكم الكمية (+ و -) -->
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <!-- زر إنقاص الكمية -->
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="type" value="decrease">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                                            style="width: 28px; height: 28px;" {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                </form>

                                                <!-- رقم الكمية الحالي -->
                                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fs-6 fw-bold">
                                                    {{ $details['quantity'] }}
                                                </span>

                                                <!-- زر زيادة الكمية -->
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="type" value="increase">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" 
                                                            style="width: 28px; height: 28px;">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <td class="fw-bold text-primary fs-6">{{ number_format($subtotal, 2) }} {{ __('messages.currency') }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                                                    <i class="bi bi-trash me-1"></i> {{ __('messages.delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- شريط المجموع الكلي وإتمام الشراء -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-white p-4 rounded-4 shadow-sm border">
                <h4 class="mb-3 mb-md-0 fw-bold text-dark">
                    {{ __('messages.total') }}: <span class="text-success fw-bolder">{{ number_format($total, 2) }} {{ __('messages.currency') }}</span>
                </h4>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-checkout text-white shadow-sm">
                    {{ __('messages.proceed_to_checkout') }}
                </a>
            </div>
        @else
            <!-- حالة السلة الفارغة -->
            <div class="text-center py-5 bg-white rounded-4 shadow-sm p-5 border">
                <i class="bi bi-cart-x text-muted display-1"></i>
                <h4 class="mt-3 fw-bold text-dark">{{ __('messages.cart_empty') }}</h4>
                <p class="text-muted">{{ __('messages.cart_empty_msg') }}</p>
                <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-2 mt-2 fw-bold shadow-sm">
                    {{ __('messages.back_to_store') }}
                </a>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
</body>
</html>