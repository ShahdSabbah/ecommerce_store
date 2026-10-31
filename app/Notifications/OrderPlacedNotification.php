<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("تأكيد الطلب #{$this->order->id}")
            ->greeting("مرحباً {$this->order->customer_name}!")
            ->line("تم استلام طلبك رقم #{$this->order->id} بنجاح.")
            ->line("المبلغ الإجمالي: \${$this->order->total_price}")
            ->line("عنوان التوصيل: {$this->order->address}")
            ->action('عرض تفاصيل الطلب', url('/orders/' . $this->order->id))
            ->line('شكراً لتسوقك معنا!');
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id'       => $this->order->id,
            'customer_name'  => $this->order->customer_name,
            'total_price'    => $this->order->total_price,
            'message'        => "تم استلام طلبك الجديد رقم #{$this->order->id} بنجاح.",
            'action_url'     => url('/orders/' . $this->order->id),
        ];
    }
}
