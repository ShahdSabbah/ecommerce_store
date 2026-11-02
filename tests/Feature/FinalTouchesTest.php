<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FinalTouchesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'customer']);
    }

    /** 1. اختبار صفحة الخطأ 404 المخصصة */
    public function test_custom_404_page_is_rendered_for_missing_routes()
    {
        $response = $this->get('/non-existent-page-xyz-123');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('icon-bubble', false);
    }

    /** 2. اختبار أوسمة المخزون على المنتجات */
    public function test_stock_badges_and_cart_protection_on_storefront()
    {
        $category = Category::create(['name' => 'electronics']);

        $inStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Laptop Pro',
            'price' => 1200,
            'stock' => 10,
        ]);

        $lowStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Mouse Wireless',
            'price' => 50,
            'stock' => 3,
        ]);

        $outOfStockProduct = Product::create([
            'category_id' => $category->id,
            'name' => 'Keyboard Mechanical',
            'price' => 80,
            'stock' => 0,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // التحقق من ظهور أوسمة التوفر والكميات
        $response->assertSee('Laptop Pro');
        $response->assertSee('Mouse Wireless');
        $response->assertSee('Keyboard Mechanical');

        // محاولة إضافة منتج نفد مخزونه يجب أن تفشل
        $addResponse = $this->post(route('cart.add', $outOfStockProduct->id));
        $addResponse->assertSessionHas('error');
    }

    /** 3. اختبار صلاحيات مشاهدة تفاصيل وفاتورة الطلب */
    public function test_order_details_authorization()
    {
        $category = Category::create(['name' => 'clothing']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'T-Shirt',
            'price' => 25,
            'stock' => 50,
        ]);

        $order = Order::create([
            'customer_name' => 'Shahd Sabbah',
            'customer_email' => 'shahd@example.com',
            'customer_phone' => '0599000000',
            'address' => 'Gaza, Palestine',
            'total_price' => 25,
            'status' => 'pending',
        ]);

        // 3.1 الزائر غير المسجل دخوله يحول لصفحة الدخول (302)
        $guestResponse = $this->get(route('orders.show', $order->id));
        $guestResponse->assertRedirect(route('login'));

        // 3.2 العميل صاحب الطلب يمكنه فتح الطلب ورؤية الفاتورة (200)
        $ownerUser = User::factory()->create([
            'email' => 'shahd@example.com'
        ]);
        $ownerUser->assignRole('customer');

        $ownerResponse = $this->actingAs($ownerUser)->get(route('orders.show', $order->id));
        $ownerResponse->assertStatus(200);
        $ownerResponse->assertSee('Shahd Sabbah');

        // 3.3 عميل آخر ببريد مختلف يُمنع تماماً (403 Forbidden)
        $otherUser = User::factory()->create([
            'email' => 'other@example.com'
        ]);
        $otherUser->assignRole('customer');

        $otherResponse = $this->actingAs($otherUser)->get(route('orders.show', $order->id));
        $otherResponse->assertStatus(403);

        // 3.4 الأدمن يمكنه رؤية طلبات كافة العملاء (200)
        $adminUser = User::factory()->create([
            'email' => 'admin@example.com'
        ]);
        $adminUser->assignRole('admin');

        $adminResponse = $this->actingAs($adminUser)->get(route('orders.show', $order->id));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Shahd Sabbah');
    }
}
