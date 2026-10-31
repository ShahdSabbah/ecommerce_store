<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Events\OrderPlaced;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // عرض صفحة الـ Checkout
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty_msg'));
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    // معالجة الطلب والحفظ
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        $request->validate([
            'phone'            => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // إرجاع المتغير مباشرة من الـ Transaction لتجنب خطأ الـ Undefined Variable
        $order = DB::transaction(function () use ($request, $cart, $total) {
            
            $order = Order::create([
                'customer_name'  => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => $request->phone,
                'address'        => $request->shipping_address,
                'total_price'    => $total,
                'status'         => 'pending',
            ]);

            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $details['quantity'],
                    'price'      => $details['price'],
                ]);
            }

            return $order;
        });
        //  2. هنا موضع الـ Event الرئيسي!
        // تم إنشاء الطلب والـ Items بنجاح، نطلق حدث "OrderPlaced"
        OrderPlaced::dispatch($order);

        // مسح السلة من الجلسة بعد نجاح العملية
        session()->forget('cart');

        return redirect()->route('home')->with('success', __('messages.order_success') . $order->id);
    }
}