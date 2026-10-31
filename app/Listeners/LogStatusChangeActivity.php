<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogStatusChangeActivity implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event): void
    {
        $order = $event->order;

        // تسجيل تغيير حالة الطلب في سجل النشاط
        try {
            ActivityLog::create([
                'user_name' => auth()->user()->name ?? 'نظام',
                'action'    => 'تحديث حالة طلب',
                'details'   => "تم تغيير حالة الطلب #{$order->id} من {$event->oldStatus} إلى {$event->newStatus}",
            ]);
        } catch (\Exception $e) {
            Log::warning("⚠️ فشل تسجيل تغيير حالة الطلب #{$order->id}: " . $e->getMessage());
        }

        Log::info("📝 تم تسجيل تغيير حالة الطلب", [
            'order_id'   => $order->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
        ]);
    }
}
