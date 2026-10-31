<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    // 1. عرض كل الطلبات في جدول مع التصفح والكاش
    public function index(Request $request)
    {
        $page = (int) $request->get('page', 1);

        // مفتاح كاش فريد لكل صفحة طلبات
        $cacheKey = "orders_web_page_{$page}";

        $orders = Cache::remember($cacheKey, 1800, function () {
            return Order::latest()->paginate(10);
        });

        return view('orders.index', compact('orders'));
    }

    // 2. عرض تفاصيل طلب واحد (الفاتورة)
    public function show($id)
    {
        $cacheKey = "order_detail_{$id}";

        $order = Cache::remember($cacheKey, 1800, function () use ($id) {
            return Order::with('items.product')->find($id);
        });

        if (!$order) {
            abort(404);
        }

        return view('orders.show', compact('order'));
    }

    // 3. تحديث حالة الطلب
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        // حفظ الحالة القديمة قبل التحديث
        $oldStatus = $order->status;

        // عند التحديث سيتفعل الـ OrderObserver تلقائياً ويمسح كاش هذا الطلب وقائمة الطلبات
        $order->update([
            'status' => $request->status
        ]);

        // إطلاق حدث تغيير حالة الطلب
        OrderStatusUpdated::dispatch($order, $oldStatus, $request->status);

        return redirect()->back()->with('success', __('messages.order_status_updated'));
    }
}