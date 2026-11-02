<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        
        <!-- رابط اللوجو / اسم المتجر -->
        <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('home') }}">
            <i class="bi bi-bag-heart-fill me-1"></i> {{ __('messages.brand') }}
        </a>

        <div class="d-flex align-items-center gap-2 gap-md-3">
            
            <!-- 🌍 زر تبديل لغة صغير وأنيق (Compact) -->
            <div class="dropdown">
                <button class="btn btn-sm btn-light border rounded-pill px-2 py-1 fw-bold dropdown-toggle d-flex align-items-center gap-1 text-muted" type="button" data-bs-toggle="dropdown" style="font-size: 13px;">
                    <i class="bi bi-globe2 text-primary" style="font-size: 13px;"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'عربي' : 'EN' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-1 py-1" style="min-width: 110px; font-size: 13px;">
                    <li>
                        <a class="dropdown-item py-1 px-3 fw-bold {{ app()->getLocale() === 'ar' ? 'text-primary bg-light' : '' }}" href="{{ route('lang.switch', 'ar') }}">
                            🇸🇦 عربي
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-1 px-3 fw-bold {{ app()->getLocale() === 'en' ? 'text-primary bg-light' : '' }}" href="{{ route('lang.switch', 'en') }}">
                            🇬🇧 English
                        </a>
                    </li>
                </ul>
            </div>

            <!-- زر السلة مع العداد -->
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary rounded-pill px-3 position-relative fw-bold btn-sm">
                🛒 {{ __('messages.cart') }}
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>

            <!-- القائمة الخاصة بالمستخدم وإشعاراته -->
            @auth
                <!-- 🔔 جرس الإشعارات مع القائمة المنسدلة -->
                <div class="dropdown" id="notificationDropdownContainer">
                    <button class="btn btn-light rounded-circle p-2 position-relative border shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="الإشعارات" style="width: 38px; height: 38px;">
                        <i class="bi bi-bell-fill text-dark fs-6"></i>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        <span id="unreadNotificationsBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadCount > 0 ? '' : 'd-none' }}" style="font-size: 10px;">
                            {{ $unreadCount }}
                        </span>
                    </button>
                    
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-0" style="width: 340px; max-width: 90vw;">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top-4">
                            <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-bell-fill text-primary"></i> الإشعارات
                            </h6>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.markAllRead') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-primary fw-bold" style="font-size: 12px;">
                                        تعليم الكل كمقروء
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="overflow-auto" style="max-height: 320px;" id="notificationsDropdownList">
                            @forelse(auth()->user()->notifications->take(6) as $notification)
                                @php
                                    $isUnread = is_null($notification->read_at);
                                    $data = $notification->data;
                                    
                                    if (app()->getLocale() === 'en') {
                                        $msg = $data['message_en'] ?? (
                                            !empty($data['order_id']) 
                                                ? "Your order #{$data['order_id']} has been received successfully."
                                                : ($data['message'] ?? 'New Notification')
                                        );
                                    } else {
                                        $msg = $data['message_ar'] ?? ($data['message'] ?? 'إشعار جديد');
                                    }

                                    $actionUrl = $data['action_url'] ?? null;
                                    
                                    $icon = 'bi-bell-fill text-primary';
                                    if (str_contains($notification->type, 'OutOfStock')) {
                                        $icon = 'bi-exclamation-triangle-fill text-danger';
                                    } elseif (str_contains($notification->type, 'OrderStatus')) {
                                        $icon = 'bi-arrow-repeat text-warning';
                                    } elseif (str_contains($notification->type, 'OrderPlaced')) {
                                        $icon = 'bi-bag-check-fill text-success';
                                    }
                                @endphp

                                <a href="{{ route('notifications.read', $notification->id) }}" class="d-block p-3 border-bottom text-decoration-none transition-all {{ $isUnread ? 'bg-light bg-opacity-50' : '' }}" style="border-color: #f1f1f4 !important;">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="mt-1">
                                            <i class="bi {{ $icon }} fs-6"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1 text-dark {{ $isUnread ? 'fw-bold' : '' }}" style="font-size: 13px; line-height: 1.4;">
                                                {{ $msg }}
                                            </p>
                                            <span class="text-muted" style="font-size: 11px;">
                                                <i class="bi bi-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if($isUnread)
                                            <span class="badge rounded-circle bg-primary p-1 mt-1"></span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-4 text-muted" id="noNotificationsPlaceholder">
                                    <i class="bi bi-bell-slash fs-3 d-block mb-1 opacity-50"></i>
                                    <span style="font-size: 13px;">لا توجد إشعارات حالياً</span>
                                </div>
                            @endforelse
                        </div>

                        <div class="p-2 border-top text-center bg-light rounded-bottom-4">
                            <a href="{{ route('notifications.index') }}" class="btn btn-link btn-sm text-decoration-none text-primary fw-bold" style="font-size: 13px;">
                                عرض جميع الإشعارات <i class="bi bi-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light rounded-pill dropdown-toggle px-3 border fw-bold btn-sm shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle text-primary me-1"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                        @if(auth()->user()->isAdmin())
                            <li>
                                <a class="dropdown-item fw-bold text-primary py-2" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> {{ __('messages.dashboard') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-bolder d-flex align-items-center gap-2 py-2">
                                    <i class="bi bi-box-arrow-right text-danger fs-5"></i>
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3 fw-bold btn-sm">{{ __('messages.login') }}</a>
                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-3 fw-bold text-white btn-sm shadow-sm">{{ __('messages.register') }}</a>
            @endauth
        </div>

    </div>
</nav>