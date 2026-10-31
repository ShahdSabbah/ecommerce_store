<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\User;
use App\Notifications\OrderStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendStatusUpdateEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderStatusUpdated $event): void
    {
        $order = $event->order;

        // البحث عن المستخدم صاحب الطلب
        $user = User::where('email', $order->customer_email)->first();

        $notification = new OrderStatusNotification(
            $order,
            $event->oldStatus,
            $event->newStatus
        );

        if ($user) {
            $user->notify($notification);
        } else {
            Notification::route('mail', $order->customer_email)->notify($notification);
        }

        Log::info("📧🔔 تم إرسال إشعار تحديث حالة الطلب", [
            'order_id'   => $order->id,
            'old_status' => $event->oldStatus,
            'new_status' => $event->newStatus,
        ]);
    }
}
