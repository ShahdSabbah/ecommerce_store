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
<a href="{{ Str::replaceFirst('/ar', '/en', request()->getRequestUri()) }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary px-3 py-1 border border-gray-300 rounded-lg transition">{{ __('nav.toggle_english') }}</a>
                @else
                    <a href="{{ Str::replaceFirst('/en', '/ar', request()->getRequestUri()) }}" class="text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-primary px-3 py-1 border border-gray-300 rounded-lg transition">
                        عربي
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