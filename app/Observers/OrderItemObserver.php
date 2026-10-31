<?php

namespace App\Observers;

use App\Events\ProductOutOfStock;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Cache;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        if ($orderItem->product) {
            // خصم الكمية من مخزون المنتج
            $orderItem->product->decrement('stock', $orderItem->quantity);

            // تحديث الكائن بعد الخصم
            $orderItem->product->refresh();

            // إذا المخزون وصل صفر أو أقل، نطلق حدث نفاذ المخزون
            if ($orderItem->product->stock <= 0) {
                ProductOutOfStock::dispatch($orderItem->product);
            }

            // مسح كاش هذا المنتج تحديداً لمسح الـ Stock القديم فوراً
            Cache::forget("product_detail_{$orderItem->product_id}_lang_ar");
            Cache::forget("product_detail_{$orderItem->product_id}_lang_en");
            Cache::flush();
        }
    }
}