<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        
        <!-- رابط اللوجو / اسم المتجر -->
        <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('home') }}">
            <i class="bi bi-bag-heart-fill me-1"></i> متجرنا
        </a>

        <div class="d-flex align-items-center gap-3">
            <!-- زر السلة مع العداد -->
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary rounded-pill px-3 position-relative fw-bold me-2">
                🛒 السلة
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
                                    <i class="bi bi-speedometer2 me-2"></i> لوحة التحكم
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-bolder d-flex align-items-center gap-2 py-2">
                                    <i class="bi bi-box-arrow-right text-danger fs-5"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">دخول</a>
                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 fw-bold text-white">تسجيل جديد</a>
            @endauth
        </div>

    </div>
</nav>