<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use App\Observers\OrderItemObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تسجيل الـ Observers
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        OrderItem::observe(OrderItemObserver::class);
    }
}