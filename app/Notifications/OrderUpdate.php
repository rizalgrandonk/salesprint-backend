<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

const ORDER_STATUS_BODY = [
    'UNPAID' => 'Pesanan baru telah dibuat dengan nomor pesanan %s',
    'PAID' => 'Pesanan dengan nomor pesanan %s telah dibayar oleh pembeli',
    'PROCESSED' => 'Pesanan dengan nomor pesanan %s telah dikonfirmasi oleh penjual',
    'SHIPPED' => 'Pesanan dengan nomor pesanan %s telah diserahkan ke jasa kirim',
    'DELIVERED' => 'Pesanan dengan nomor pesanan %s diserahkan ke penerima',
    'COMPLETED' => 'Pesanan dengan nomor pesanan %s selesai',
    'CANCELED' => 'Pesanan dengan nomor pesanan %s dibatalkan'
];
const ORDER_STATUS_TITLE = [
    'UNPAID' => 'Pesanan Dibuat',
    'PAID' => 'Pesanan Dibayar',
    'PROCESSED' => 'Pesanan Dikonfirmasi',
    'SHIPPED' => 'Pesanan Dikirim',
    'DELIVERED' => 'Pesanan Diterima',
    'COMPLETED' => 'Pesanan Selesai',
    'CANCELED' => 'Pesanan Dibatalkan'
];

class OrderUpdate extends Notification {
    use Queueable;

    /** @var Order */
    private $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order) {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['database', 'broadcast', PusherChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array {
        return [
            'order_id' => $this->order->id,
            'notif_type' => 'order-update',
            'title' => ORDER_STATUS_TITLE[$this->order->order_status],
            'body' => sprintf(ORDER_STATUS_BODY[$this->order->order_status], $this->order->order_number),
            'action_url' => $notifiable->role
                ? env('CLIENT_APP_URL', 'http://localhost:8080') . '/' . $notifiable->role . '/orders' . ($notifiable->role == 'seller' ? '/' . $this->order->order_number : '')
                : env('CLIENT_APP_URL', 'http://localhost:8080')
        ];
    }

    public function toBroadcast($notifiable) {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function toPushNotification($notifiable) {
        return PusherMessage::create()
            ->web()
            ->title(ORDER_STATUS_TITLE[$this->order->order_status])
            ->body(sprintf(ORDER_STATUS_BODY[$this->order->order_status], $this->order->order_number))
            ->link(
                $notifiable->role
                ? env('CLIENT_APP_URL', 'http://localhost:8080') . '/' . $notifiable->role . '/orders' . ($notifiable->role == 'seller' ? '/' . $this->order->order_number : '')
                : env('CLIENT_APP_URL', 'http://localhost:8080')
            )
            ->icon(env('CLIENT_APP_URL', 'http://localhost:8080') . '/icons/favicon-32x32.png');
    }
}
