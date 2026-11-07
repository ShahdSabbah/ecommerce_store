<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthAndCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_protected_routes_return_401_without_token()
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);

        $response = $this->getJson('/api/cart');
        $response->assertStatus(401);

        $response = $this->getJson('/api/orders');
        $response->assertStatus(401);
    }

    public function test_user_can_login_and_access_protected_routes_with_token()
    {
        $user = User::factory()->create([
            'email' => 'shahd@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'shahd@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('access_token');
        $this->assertNotEmpty($token);

        $userResponse = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/user');

        $userResponse->assertStatus(200)
            ->assertJsonPath('user.email', 'shahd@example.com');
    }

    public function test_complete_cart_lifecycle_and_stock_validation()
    {
        $category = Category::create([
            'name' => 'Tech',
            'description' => 'Gadgets',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Mechanical Keyboard',
            'description' => 'RGB Gaming Keyboard',
            'price' => 100.00,
            'stock' => 5,
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        $headers = ['Authorization' => "Bearer {$token}"];

        // 1. إضافة منتج للسلة
        $addResponse = $this->withHeaders($headers)->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $addResponse->assertStatus(200);
        $cartItemId = $addResponse->json('data.id');

        // 2. فحص السلة
        $getResponse = $this->withHeaders($headers)->getJson('/api/cart');
        $getResponse->assertStatus(200)
            ->assertJsonPath('data.items_count', 2)
            ->assertJsonPath('data.total_price', 200);

        // 3. منع إضافة كمية تتجاوز المخزون (المخزون 5، بالسلة 2، إضافة 4 تفشل)
        $oversellResponse = $this->withHeaders($headers)->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 4,
        ]);
        $oversellResponse->assertStatus(422);

        // 4. تحديث الكمية إلى 3
        $updateResponse = $this->withHeaders($headers)->putJson("/api/cart/{$cartItemId}", [
            'quantity' => 3,
        ]);
        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.quantity', 3);

        // 5. زيادة بنوع increase
        $increaseResponse = $this->withHeaders($headers)->putJson("/api/cart/{$cartItemId}", [
            'type' => 'increase',
        ]);
        $increaseResponse->assertStatus(200)
            ->assertJsonPath('data.quantity', 4);

        // 6. حذف عنصر واحد
        $deleteResponse = $this->withHeaders($headers)->deleteJson("/api/cart/{$cartItemId}");
        $deleteResponse->assertStatus(200);

        // التأكد من أن السلة أصبحت فارغة
        $emptyResponse = $this->withHeaders($headers)->getJson('/api/cart');
        $emptyResponse->assertStatus(200)
            ->assertJsonPath('data.items_count', 0);
    }
}
