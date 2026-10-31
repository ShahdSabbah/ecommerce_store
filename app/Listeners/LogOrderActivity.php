<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogOrderActivity implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        // تسجيل الطلب الجديد في سجل النشاط
        try {
            ActivityLog::create([
                'user_name' => $order->customer_name,
                'action'    => 'طلب جديد',
                'details'   => "تم إنشاء طلب جديد رقم #{$order->id} بمبلغ {$order->total_price}",
            ]);
        } catch (\Exception $e) {
            Log::warning("⚠️ فشل تسجيل النشاط للطلب #{$order->id}: " . $e->getMessage());
        }

        Log::info("📝 تم تسجيل نشاط الطلب الجديد", ['order_id' => $order->id]);
    }
}
