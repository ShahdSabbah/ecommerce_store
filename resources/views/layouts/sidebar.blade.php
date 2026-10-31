<div id="kt_aside" 
     class="aside aside-dark aside-hoverable offcanvas {{ app()->getLocale() === 'ar' ? 'offcanvas-start' : 'offcanvas-end' }}" 
     tabindex="-1" 
     aria-labelledby="kt_aside_label">
    <!-- اللوجو -->
    <div class="aside-logo d-flex align-items-center justify-content-center h-75px px-5 border-bottom border-secondary">
        <a href="{{ url('/') }}" class="text-white fw-bolder fs-3 text-decoration-none">
            🛒 {{ __('messages.brand') }}
        </a>
    </div>

    <!-- القائمة -->
    <div class="aside-menu my-5 px-4">
        <div class="menu menu-column menu-title-gray-400 menu-state-title-primary menu-state-icon-primary">
            
            <!-- الرئيسية / Dashboard -->
            <div class="menu-item mb-2">
                <a class="menu-link py-3 px-4 rounded text-white d-flex align-items-center {{ request()->routeIs('dashboard') ? 'bg-primary' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 fs-4 me-3"></i>
                    <span class="menu-title fw-bold">{{ __('messages.dashboard') }}</span>
                </a>
            </div>

            <!-- المتجر / Storefront -->
            <div class="menu-item mb-2">
                <a class="menu-link py-3 px-4 rounded text-white d-flex align-items-center" href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-shop fs-4 me-3"></i>
                    <span class="menu-title fw-bold">{{ __('messages.back_to_store') }}</span>
                </a>
            </div>

            <!-- إدارة الأقسام -->
            <div class="menu-item mb-2">
                <a class="menu-link py-3 px-4 rounded text-white d-flex align-items-center {{ request()->routeIs('categories.*') ? 'bg-primary' : '' }}" href="{{ route('categories.index') }}">
                    <i class="bi bi-grid fs-4 me-3"></i>
                    <span class="menu-title fw-bold">{{ __('messages.categories_management') }}</span>
                </a>
            </div>

            <!-- إدارة المنتجات -->
            <div class="menu-item mb-2">
                <a class="menu-link py-3 px-4 rounded text-white d-flex align-items-center {{ request()->routeIs('products.*') ? 'bg-primary' : '' }}" href="{{ route('products.index') }}">
                    <i class="bi bi-box-seam fs-4 me-3"></i>
                    <span class="menu-title fw-bold">{{ __('messages.products_management') }}</span>
                </a>
            </div>

            <!-- إدارة الطلبات -->
            <div class="menu-item mb-2">
                <a class="menu-link py-3 px-4 rounded text-white d-flex align-items-center {{ request()->routeIs('orders.*') ? 'bg-primary' : '' }}" href="{{ route('orders.index') }}">
                    <i class="bi bi-cart fs-4 me-3"></i>
                    <span class="menu-title fw-bold">{{ __('messages.orders_management') }}</span>
                </a>
            </div>

            <!-- إدارة المستخدمين (تظهر فقط للسوبر أدمن) -->
            @if(auth()->check() && auth()->user()->isSuperAdmin())
            <div class="menu-item mb-2">
                <a class="menu-link py-3 px-4 rounded text-white d-flex align-items-center {{ request()->routeIs('users.*') ? 'bg-primary' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people fs-4 me-3"></i>
                    <span class="menu-title fw-bold">{{ __('messages.users_management') }}</span>
                    <span class="badge bg-warning text-dark ms-auto fs-8 px-2 py-1">{{ __('messages.super_admin') }}</span>
                </a>
            </div>

            <!-- الوثائق والفواتير الخاصة (تظهر فقط للسوبر أدمن) -->
            <div class="menu-item mb-2">
                <a class="menu-link py-3 px-4 rounded text-white d-flex align-items-center {{ request()->routeIs('private-documents.*') ? 'bg-primary' : '' }}" href="{{ route('private-documents.index') }}">
                    <i class="bi bi-shield-lock fs-4 me-3"></i>
                    <span class="menu-title fw-bold">{{ __('messages.private_documents') }}</span>
                    <span class="badge bg-danger text-white ms-auto fs-8 px-2 py-1">🔒</span>
                </a>
            </div>
            @endif

        </div>
    </div>
</div>