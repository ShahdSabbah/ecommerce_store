<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * عرض جميع طلبات المستخدم الحالي
     */
    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('customer_email', $request->user()->email)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الطلبات بنجاح',
            'data' => $orders
        ], 200);
    }

    /**
     * تحويل محتويات السلة إلى طلب جديد (Checkout)
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
            // إنشاء الطلب الرئيسي
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
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // إفراغ سلة التسوق للمستخدم بعد نجاح العملية
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
