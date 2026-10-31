<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function created(Product $product): void
    {
        $this->clearProductCache($product->id);
    }

    public function updated(Product $product): void
    {
        $this->clearProductCache($product->id);
    }

    public function deleted(Product $product): void
    {
        $this->clearProductCache($product->id);
    }

    public function restored(Product $product): void
    {
        $this->clearProductCache($product->id);
    }

    public function forceDeleted(Product $product): void
    {
        $this->clearProductCache($product->id);
    }

    /**
     * مسح الكاش المتعلق بالمنتجات لجميع اللغات والصفحات
     */
    private function clearProductCache(int $productId): void
    {
        // 1. مسح كاش تفاصيل المنتج لجميع اللغات
        Cache::forget("product_detail_{$productId}_lang_ar");
        Cache::forget("product_detail_{$productId}_lang_en");

        // 3. أما مع File Cache، لمسح كاش قوائم الصفحات المترجمة تماماً:
        Cache::flush();
    }
}