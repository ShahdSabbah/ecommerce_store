<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendOrderConfirmationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        $order = $event->order;

        // البحث عن المستخدم المرتبط بالبريد الإلكتروني للطلب
        $user = User::where('email', $order->customer_email)->first();

        if ($user) {
            // إرسال إشعار للمستخدم (إيميل + قاعدة بيانات)
            $user->notify(new OrderPlacedNotification($order));
        } else {
            // إذا كان الزائر غير مسجّل، نكتفي بإرسال الإيميل
            Notification::route('mail', $order->customer_email)
                ->notify(new OrderPlacedNotification($order));
        }

        Log::info("📧🔔 تم إرسال إشعار تأكيد الطلب", [
            'order_id' => $order->id,
            'email'    => $order->customer_email,
        ]);
    }
}
