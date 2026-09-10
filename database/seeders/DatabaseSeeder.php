<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء 5 أقسام
        Category::factory(5)->create();

        // 2. إنشاء 20 منتج مرتبطة بالأقسام
        Product::factory(20)->create();

        // 3. إنشاء 10 طلبات مع عناصرها وحساب إجمالي الفاتورة
        Order::factory(10)->create()->each(function ($order) {
            OrderItem::factory(rand(1, 4))->create([
                'order_id' => $order->id,
            ]);

            // إعادة حساب السعر الإجمالي للطلب بناءً على عناصره
            $total = $order->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $order->update(['total_price' => $total]);
        });
    }
}