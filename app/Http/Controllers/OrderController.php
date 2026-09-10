<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. عرض كل الطلبات في جدول
    public function index()
    {
        // نجيب أحدث الطلبات ونعمل مقسم صفحات (10 طلبات في كل صفحة)
        $orders = Order::latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // 2. عرض تفاصيل طلب واحد (الفاتورة)
    public function show(Order $order)
    {
        // بنحمل عناصر الطلب والمنتج المرتبط بكل عنصر دفعة واحدة
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    // 3. تحديث حالة الطلب (قيد الانتظار، مكتمل... إلخ)
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح!');
    }
}