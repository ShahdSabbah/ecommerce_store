<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendOrderNotificationListener implements ShouldQueue
{
    /**
     * ا يرسل إيميلات ولا يخزن في قاعدة البيانات، بل يقوم فقط بكتابة سطر سجل معلومات (Log) داخل ملف storage/logs/laravel.log.
     */
    public function handle(OrderPlaced $event): void
    {
        Log::info("🎉 تم معالجة إشعار الطلب بنجاح عبر الـ Queue!", [
            'order_id'      => $event->order->id,
            'customer_name' => $event->order->customer_name,
            'total_price'   => $event->order->total_price,
            'created_at'    => now()->toDateTimeString(),
        ]);
    }
}