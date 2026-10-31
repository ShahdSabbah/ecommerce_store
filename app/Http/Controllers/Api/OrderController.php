<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/orders",
     *      tags={"Orders"},
     *      summary="عرض طلبات المستخدم",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(name="Accept", in="header", required=true, @OA\Schema(type="string", default="application/json")),
     *      @OA\Response(response=200, description="قائمة طلبات المستخدم")
     * )
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $userEmail = $request->user()->email;

        // مفتاح كاش فريد لكل مستخدم
        $cacheKey = "user_orders_{$userId}";

        // تخزين الطلبات مؤقتاً في الكاش لمدة ساعة (3600 ثانية)
        $orders = Cache::remember($cacheKey, 3600, function () use ($userEmail) {
            return Order::with('items.product')
                ->where('customer_email', $userEmail)
                ->latest()
                ->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الطلبات بنجاح',
            'data' => $orders
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/orders",
     *      tags={"Orders"},
     *      summary="إنشاء طلب جديد من السلة",
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          @OA\Schema(type="string", default="application/json")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"customer_name","customer_email","customer_phone","address"},
     *              @OA\Property(property="customer_name", type="string", example="Shahd Sabbah"),
     *              @OA\Property(property="customer_email", type="string", example="shahedsabbah@gmail.com"),
     *              @OA\Property(property="customer_phone", type="string", example="0599123456"),
     *              @OA\Property(property="address", type="string", example="Gaza, Palestine")
     *          )
     *      ),
     *      @OA\Response(response=201, description="تم إنشاء الطلب بنجاح")
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // 1. جلب عناصر السلة الخاصة بالمستخدم
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'السلة فارغة، لا يمكن إنشاء طلب'
            ], 400);
        }

        // 2. حساب المجموع الكلي
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // 3. استخدام Transaction لضمان الحفظ الكامل أو التراجع عند حدوث خطأ
        DB::beginTransaction();

        try {
            // إنشاء الطلب الرئيسي (الـ OrderObserver سينظف كاش طلبات المستخدم آلياً)
            $order = Order::create([
                'customer_name'  => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $request->customer_phone ?? $user->phone,
                'address'        => $request->address,
                'total_price'    => $totalAmount,
                'status'         => 'pending',
            ]);

            // تفريغ عناصر السلة ونقلها لجدول تفاصيل الطلب order_items
            foreach ($cartItems as $item) {
                // الـ OrderItemObserver يخصم المخزون تلقائياً عند الإنشآء
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }

            // إفراغ سلة التسوق للمستخدم (الـ CartObserver ينظف كاش السلة تلقائياً)
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'تم إنشاء الطلب بنجاح',
                'data' => $order->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء إنشاء الطلب',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}