<!-- 1️⃣ شريط التنقل الخاص بـ Breeze / Tailwind -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown + Language Switcher (Tailwind) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                <!-- 🌐 زر تحويل اللغة للـ Tailwind -->
                @if(app()->getLocale() == 'ar')
                    <a href="{{ route('lang.switch', 'en') }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary px-3 py-1 border border-gray-300 rounded-lg transition">
                        🇬🇧 English
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary px-3 py-1 border border-gray-300 rounded-lg transition">
                        🇸🇦 العربية
                    </a>
                @endif

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-400 dark:focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>


<!-- 2️⃣ شريط التنقل الثاني الخارجي (Bootstrap Navbar) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        
        <!-- رابط اللوجو / اسم المتجر -->
        <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('home') }}">
            <i class="bi bi-bag-heart-fill me-1"></i> {{ __('messages.welcome_title') }}
        </a>

        <div class="d-flex align-items-center gap-2">

            <!-- 🌐 زر تحويل اللغة (Bootstrap) -->
            @if(app()->getLocale() == 'ar')
                <a href="{{ Str::replaceFirst('/ar', '/en', request()->getRequestUri()) }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold">
                    English
                </a>
            @else
                <a href="{{ Str::replaceFirst('/en', '/ar', request()->getRequestUri()) }}" class="btn btn-outline-secondary rounded-pill px-3 fw-bold">
                    عربي
                </a>
            @endif

            <!-- زر السلة مع العداد -->
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary rounded-pill px-3 position-relative fw-bold">
                🛒 {{ __('messages.cart') }}
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </a>

            <!-- القائمة الخاصة بالمستخدم أو الزائر -->
            @auth
                <!-- 🔔 جرس الإشعارات مع القائمة المنسدلة -->
                <div class="dropdown" id="navNotificationDropdownContainer">
                    <button class="btn btn-light rounded-circle p-2 position-relative border shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="الإشعارات" style="width: 38px; height: 38px;">
                        <i class="bi bi-bell-fill text-dark fs-6"></i>
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        <span id="navUnreadNotificationsBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadCount > 0 ? '' : 'd-none' }}" style="font-size: 10px;">
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

                        <div class="overflow-auto" style="max-height: 320px;" id="navNotificationsDropdownList">
                            @forelse(auth()->user()->notifications->take(6) as $notification)
                                @php
                                    $isUnread = is_null($notification->read_at);
                                    $data = $notification->data;
                                    $msg = $data['message'] ?? 'إشعار جديد';
                                    
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
                                <div class="text-center py-4 text-muted" id="navNoNotificationsPlaceholder">
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
                    <button class="btn btn-light rounded-pill dropdown-toggle px-3 border fw-bold" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle text-primary me-1"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                        @if(auth()->user()->role === 'admin')
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
                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3 fw-bold">{{ __('messages.login') }}</a>
                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-3 fw-bold text-white">{{ __('messages.register') }}</a>
            @endauth
        </div>

    </div>
</nav>