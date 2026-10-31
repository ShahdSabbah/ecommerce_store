<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheAndObserverTest extends TestCase
{
    /**
     * اختبار مسح الكاش تلقائياً بواسطة ProductObserver عند إنشاء منتج جديد
     */
    public function test_product_observer_clears_cache_on_creation(): void
    {
        // 1. وضْع كاش وهمي
        Cache::put('products_web_lang_ar_page_1', 'old_data');

        // التأكد أن الكاش موجود قبل إضافة المنتج
        $this->assertEquals('old_data', Cache::get('products_web_lang_ar_page_1'));

        // 2. إنشاء تصنيف ومنتج لتفعيل الـ ProductObserver
        $category = Category::factory()->create();
        
        Product::create([
            'name'        => 'منتج تجريبي',
            'category_id' => $category->id,
            'price'       => 100,
            'stock'       => 10,
            'description' => 'وصف تجريبي',
        ]);

        // 3. التأكد من أن الـ Observer قام بفرز/مسح الكاش بنجاح
        $this->assertNull(Cache::get('products_web_lang_ar_page_1'));
    }

    /**
     * اختبار خصم المخزون ومسح الكاش تلقائياً بواسطة OrderItemObserver عند الشراء
     */
    public function test_order_item_observer_decrements_stock_and_clears_cache(): void
    {
        // 1. إنشاء منتج بمخزون 10
        $category = Category::factory()->create();
        $product = Product::create([
            'name'        => 'منتج للمخزون',
            'category_id' => $category->id,
            'price'       => 50,
            'stock'       => 10,
        ]);

        // وضع كاش للمنتج
        Cache::put("product_detail_{$product->id}_lang_ar", $product);

        // 2. إنشاء طلب وتأكيد شراء 3 قطع لتفعيل OrderItemObserver
        $order = Order::create([
            'customer_name'  => 'أحمد',
            'customer_email' => 'ahmed@example.com',
            'customer_phone' => '0599000000',
            'address'        => 'Nablus',
            'total_price'    => 150,
            'status'         => 'pending',
        ]);

        OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $product->id,
            'quantity'   => 3,
            'price'      => 50,
        ]);

        // 3. التحقق من خصم المخزون من 10 إلى 7 في قاعدة البيانات
        $this->assertDatabaseHas('products', [
            'id'    => $product->id,
            'stock' => 7,
        ]);

        // 4. التحقق من مسح الكاش الخاص بالمنتج
        $this->assertNull(Cache::get("product_detail_{$product->id}_lang_ar"));
    }
}