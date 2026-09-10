<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    // عرض صفحة الـ Checkout
    public function index()
    {
        $cart = session()->get('cart', []);

        // شرط الأمان Defensive Check: منع الدخول إذا كانت السلة فارغة
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'سلتك فارغة! أضف بعض المنتجات أولاً.');
        }

        // حساب المجموع الكلي لتمريره للواجهة
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    // معالجة الطلب والحفظ (سنكتبها في الخطوة القادمة)
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'سلتك فارغة!');
        }

        // 1. Validation للبيانات القادمة من الـ Form
        $request->validate([
            'phone'            => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
        ]);

        // 2. حساب المجموع الكلي
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // 3. إنشاء السجل الرئيسي في جدول orders
            $order = Order::create([
            'customer_name'  => auth()->user()->name,
            'customer_email' => auth()->user()->email,
            'customer_phone' => $request->phone,            // 👈 مطابقة لـ customer_phone
            'address'        => $request->shipping_address, // 👈 مطابقة لـ address
            'total_price'    => $total,
            'status'         => 'pending',
        ]);

        // 4. نقل عناصر السلة إلى جدول order_items
        foreach ($cart as $productId => $details) {
            OrderItem::create([
                'order_id'   => $order->id, // ربط العنصر برقم الفاتورة المُنصأة أعلاه
                'product_id' => $productId,
                'quantity'   => $details['quantity'],
                'price'      => $details['price'], // تثبيت سعر القطعة لحظة الشراء
            ]);
        }

        // 5. مسح السلة من الـ Session بعد نجاح العملية
        session()->forget('cart');

        return redirect()->route('home')->with('success', 'تم تسجيل طلبك بنجاح! رقم الطلب: #' . $order->id);
    }
}
