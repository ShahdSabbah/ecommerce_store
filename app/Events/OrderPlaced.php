<?php

namespace App\Events;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class OrderPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'OrderPlacedEvent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $msgAr = "🎉 تم استلام طلب جديد رقم #{$this->order->id} من العميل {$this->order->customer_name} بمبلغ \${$this->order->total_price}";
        $msgEn = "🎉 New order #{$this->order->id} received from customer {$this->order->customer_name} for \${$this->order->total_price}";

        return [
            'order_id'       => $this->order->id,
            'customer_name'  => $this->order->customer_name,
            'total_price'    => $this->order->total_price,
            'created_at'     => now()->toDateTimeString(),
            'message'        => app()->getLocale() === 'en' ? $msgEn : $msgAr,
            'message_ar'     => $msgAr,
            'message_en'     => $msgEn,
        ];
    }
}
