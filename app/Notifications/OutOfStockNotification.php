<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OutOfStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Product $product) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject("🚨 تنبيه: المنتج {$this->product->name} نفد من المخزون!")
            ->greeting("مرحباً الأدمن!")
            ->line("المنتج (ID: {$this->product->id} - {$this->product->name}) وصل مخزونه إلى {$this->product->stock}.")
            ->action('إدارة المنتجات', url('/products/' . $this->product->id . '/edit'))
            ->line('يرجى إعادة تزويد المخزون في أقرب وقت.');
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'product_id'   => $this->product->id,
            'product_name' => $this->product->name,
            'stock'        => $this->product->stock,
            'message'      => "🚨 تنبيه: المنتج ({$this->product->name}) نفد من المخزون!",
            'action_url'   => url('/products/' . $this->product->id . '/edit'),
        ];
    }
}
