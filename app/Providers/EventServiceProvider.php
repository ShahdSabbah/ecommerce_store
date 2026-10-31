<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\OrderStatusUpdated;
use App\Events\ProductOutOfStock;
use App\Listeners\LogOrderActivity;
use App\Listeners\LogStatusChangeActivity;
use App\Listeners\NotifyAdminOutOfStock;
use App\Listeners\SendOrderConfirmationEmail;
use App\Listeners\SendOrderNotificationListener;
use App\Listeners\SendStatusUpdateEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Laravel default: إرسال إيميل التحقق عند التسجيل
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // عند إنشاء طلب جديد
        OrderPlaced::class => [
            SendOrderNotificationListener::class,  // Log الطلب (موجود سابقاً)
            SendOrderConfirmationEmail::class,      // إرسال إيميل تأكيد للعميل
            LogOrderActivity::class,                // تسجيل في سجل النشاط
        ],

        // عند تغيير حالة الطلب
        OrderStatusUpdated::class => [
            SendStatusUpdateEmail::class,           // إرسال إيميل للعميل بالتحديث
            LogStatusChangeActivity::class,         // تسجيل التغيير في سجل النشاط
        ],

        // عند نفاذ المخزون
        ProductOutOfStock::class => [
            NotifyAdminOutOfStock::class,           // تنبيه الأدمن
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
