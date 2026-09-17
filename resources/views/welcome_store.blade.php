<?php
/**
 * Welcome Store Blade View
 * Updated to use translation files and a locale‑aware language toggle button.
 */
?>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.title') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Tajawal', sans-serif; background-color: #f8fafc; }
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

    <!-- 🌐 Header -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('home', ['locale'=>app()->getLocale()]) }}">
                <i class="bi bi-bag-heart-fill me-1"></i> {{ __('messages.brand') }}
            </a>

            <div class="d-flex align-items-center gap-3">
                <!-- Cart button -->
                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary rounded-pill px-3 position-relative fw-bold me-2">
                    🛒 {{ __('messages.cart') }}
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                <!-- Language toggle (single button) -->
                @php
                    $other = app()->getLocale() === 'ar' ? 'en' : 'ar';
                    $segments = request()->segments();
                    if (!empty($segments)) {
                        $segments[0] = $other;
                    } else {
                        $segments = [$other];
                    }
                    $url = url(implode('/', $segments));
                @endphp
                <a href="{{ $url }}" class="btn btn-outline-secondary rounded-pill px-2 fw-bold">
                    {{ $other == 'ar' ? 'العربية' : 'English' }}
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill dropdown-toggle px-3 border fw-bold" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-primary me-1"></i> {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item fw-bold text-primary" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> لوحة التحكم</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-bold"><i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login', ['locale'=>app()->getLocale()]) }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">{{ __('messages.login') }}</a>
                    <a href="{{ route('register', ['locale'=>app()->getLocale()]) }}" class="btn btn-primary rounded-pill px-4 fw-bold text-white">{{ __('messages.register') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- 🏷️ Category filter bar -->
    <section class="py-4 bg-white border-bottom">
        <div class="container">
            <div class="d-flex gap-2 overflow-auto pb-2">
                <a href="{{ route('home', ['locale'=>app()->getLocale()]) }}" class="category-chip {{ !request('category_id') ? 'active' : '' }}">{{ __('messages.all') }} 🛒</a>

                @foreach($categories as $category)
                    <a href="{{ route('home', ['locale'=>app()->getLocale(), 'category_id' => $category->id]) }}"
                       class="category-chip {{ request('category_id') == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 🛍️ Products display -->
    <section class="py-5">
        <div class="container">
            {{-- Success message after adding to cart --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <h4 class="fw-bold text-dark mb-4">{{ __('messages.latest_products') }} 🌟</h4>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card product-card h-100 p-2">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="product-img rounded-4" alt="{{ $product->name }}">
                            @else
                                <div class="product-img rounded-4 d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image fs-1"></i></div>
                            @endif

                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <div>
                                    <span class="badge bg-light text-primary rounded-pill px-3 py-1 mb-2 border">
                                        {{ $product->category->name ?? __('messages.general') }}
                                    </span>
                                    <h6 class="fw-bold text-dark mb-1">{{ $product->name }}</h6>
                                    <p class="text-muted small mb-3 text-truncate">{{ $product->description }}</p>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold fs-5 text-dark">{{ number_format($product->price, 2) }} <small class="fs-6 text-muted">شيكل</small></span>
                                    </div>

                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-cart w-100">🛒 {{ __('messages.add_to_cart') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-box-seam fs-1 text-muted"></i>
                        <p class="text-muted mt-2 fs-5">{{ __('messages.no_products') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>