<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.site_name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { 
            font-family: {{ app()->getLocale() === 'ar' ? "'Tajawal', 'Cairo', sans-serif" : "'Inter', system-ui, sans-serif" }}; 
            background-color: #f8fafc; 
        }
        .navbar-custom { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .category-chip { border-radius: 50px; padding: 8px 20px; font-size: 14px; font-weight: 600; background-color: #fff; color: #475569; border: 1px solid #e2e8f0; text-decoration: none; transition: all 0.2s ease; white-space: nowrap; }
        .category-chip:hover, .category-chip.active { background-color: #4f46e5; color: #fff; border-color: #4f46e5; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); }
        .product-card { border: none; border-radius: 20px; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.03); transition: all 0.3s ease; overflow: hidden; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.07); }
        .product-img { height: 200px; object-fit: cover; width: 100%; background-color: #f1f5f9; }
        .btn-cart { background-color: #4f46e5; color: white; border-radius: 12px; font-weight: 700; border: none; padding: 10px; transition: all 0.2s ease; }
        .btn-cart:hover { background-color: #4338ca; color: white; }
    </style>
</head>
<body>

    <!-- الهيدر العلوي المشترك مع جرس الإشعارات -->
    @include('layouts.header')

    <!--  شريط البحث والأقسام المفلترة -->
    <section class="py-4 bg-white border-bottom shadow-sm">
        <div class="container">
            <div class="row align-items-center mb-3 g-3">
                <div class="col-12 col-md-7 col-lg-5">
                    <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
                        @if(request('category_id'))
                            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        @endif
                        <div class="input-group shadow-sm rounded-pill overflow-hidden">
                            <span class="input-group-text bg-light border-0 ps-3 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="form-control bg-light border-0 py-2 fs-6" 
                                   placeholder="{{ __('messages.search_placeholder') }}">
                            @if(request('search'))
                                <a href="{{ route('home', array_filter(['category_id' => request('category_id')])) }}" 
                                   class="input-group-text bg-light border-0 text-muted" title="{{ __('messages.cancel') }}">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                {{ __('messages.search') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="d-flex gap-2 overflow-auto pb-2">
                <a href="{{ route('home') }}" class="category-chip {{ !request('category_id') && !request('search') ? 'active' : '' }}">
                    {{ __('messages.all') }} 🛒
                </a>

                @foreach($categories as $category)
                    <a href="{{ route('home', ['category_id' => $category->id]) }}" 
                       class="category-chip {{ request('category_id') == $category->id ? 'active' : '' }}">
                       {{ \Illuminate\Support\Facades\Lang::has('messages.' . $category->name) ? __('messages.' . $category->name) : $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 🛍️ عرض المنتجات -->
    <section class="py-5">
        <div class="container">
            {{-- تنبيه رسالة النجاح عند الإضافة --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <h4 class="fw-bold text-dark mb-4">{{ __('messages.latest_products') }}</h4>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100 p-2">
                            <img src="{{ $product->image_url }}" class="product-img rounded-4" alt="{{ $product->name }}" loading="lazy">

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center gap-1 mb-2">
                                        <span class="badge bg-light text-primary rounded-pill px-3 py-1 border text-truncate">
                                            @if($product->category)
                                                {{ \Illuminate\Support\Facades\Lang::has('messages.' . $product->category->name) ? __('messages.' . $product->category->name) : $product->category->name }}
                                            @else
                                                {{ __('messages.categories') }}
                                            @endif
                                        </span>

                                        @if($product->stock > 5)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                                                <i class="bi bi-check-circle-fill me-1"></i> {{ __('messages.in_stock') }} ({{ __('messages.pieces_left', ['count' => $product->stock]) }})
                                            </span>
                                        @elseif($product->stock > 0)
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ __('messages.low_stock') }} ({{ __('messages.pieces_left', ['count' => $product->stock]) }})
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                                                <i class="bi bi-x-circle-fill me-1"></i> {{ __('messages.out_of_stock') }}
                                            </span>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ $product->name }}</h6>
                                    <p class="text-muted small mb-3 text-truncate">{{ $product->description }}</p>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold fs-5 text-dark">
                                            {{ number_format($product->price, 2) }} 
                                            <small class="fs-6 text-muted">{{ __('messages.currency') }}</small>
                                        </span>
                                    </div>

                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-cart w-100">
                                                🛒 {{ __('messages.add_to_cart') }}
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-secondary w-100 opacity-75 shadow-none" style="cursor: not-allowed;" disabled>
                                            ❌ {{ __('messages.out_of_stock') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-box-seam fs-1 text-muted"></i>
                        <p class="text-muted mt-2 fs-5">{{ __('messages.no_data') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

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
                    
                    // 1. إظهار الإشعار العائم (Toast Popup) بمدة 25 ثانية حتى يراه المستخدم بوضوح
                    const toastEl = document.getElementById('liveOrderToast');
                    const toastBody = document.getElementById('liveOrderToastBody');
                    if (toastEl && toastBody) {
                        const isEn = "{{ app()->getLocale() }}" === 'en';
                        const toastTitle = isEn ? '⚡ Instant New Order (WebSocket):' : '⚡ طلب جديد فوري (WebSocket):';
                        const toastMsg = isEn ? (data.message_en || data.message) : (data.message_ar || data.message);
                        toastBody.innerHTML = `<strong>${toastTitle}</strong><br>${toastMsg}`;
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