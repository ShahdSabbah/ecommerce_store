<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus
    ) {}

    private function translateStatus(string $status): string
    {
        return match ($status) {
            'pending'    => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'completed'  => 'مكتمل',
            'cancelled'  => 'ملغي',
            default      => $status,
        };
    }

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
        $oldStatusTranslated = $this->translateStatus($this->oldStatus);
        $newStatusTranslated = $this->translateStatus($this->newStatus);

        return (new MailMessage)
            ->subject("تحديث حالة الطلب #{$this->order->id} - {$newStatusTranslated}")
            ->greeting("مرحباً {$this->order->customer_name}!")
            ->line("تم تحديث حالة طلبك رقم #{$this->order->id}")
            ->line("من: {$oldStatusTranslated}")
            ->line("إلى: {$newStatusTranslated}")
            ->action('عرض تفاصيل الطلب', url('/orders/' . $this->order->id))
            ->line('شكراً لتسوقك معنا!');
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        $newStatusTranslated = $this->translateStatus($this->newStatus);

        return [
            'order_id'   => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message'    => "تم تغيير حالة طلبك #{$this->order->id} إلى {$newStatusTranslated}.",
            'action_url' => url('/orders/' . $this->order->id),
        ];
    }
}
