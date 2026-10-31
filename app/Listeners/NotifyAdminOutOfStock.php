<?php

namespace App\Listeners;

use App\Events\ProductOutOfStock;
use App\Models\User;
use App\Notifications\OutOfStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOutOfStock implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ProductOutOfStock $event): void
    {
        $product = $event->product;

        // البحث عن كل المسؤولين (Admins & Super Admins)
        $admins = User::where('role', 'admin')
            ->orWhere('is_super_admin', true)
            ->get();

        if ($admins->isNotEmpty()) {
            // إرسال الإشعار لجميع الأدمنز (حفظ بالداتابيس + إيميل)
            Notification::send($admins, new OutOfStockNotification($product));
        }

        // تسجيل تحذير في سجلات النظام
        Log::warning("🚨🔔 تنبيه: المنتج نفذ من المخزون وتـم إشعار الأدمن!", [
            'product_id'   => $product->id,
            'product_name' => $product->name,
            'stock'        => $product->stock,
            'admins_count' => $admins->count(),
        ]);
    }
}
