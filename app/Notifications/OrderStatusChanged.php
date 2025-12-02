<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'new' => 'Новый',
            'processing' => 'В обработке',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменен'
        ];

        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newStatusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        $customerDetails = $this->order->customer_details;
        $customerName = $customerDetails['name'] ?? 'Клиент';

        return (new MailMessage)
            ->subject('Изменение статуса заказа #' . $this->order->id)
            ->greeting('Здравствуйте, ' . $customerName . '!')
            ->line('Статус вашего заказа #' . $this->order->id . ' был изменен.')
            ->line('**Старый статус:** ' . $oldStatusLabel)
            ->line('**Новый статус:** ' . $newStatusLabel)
            ->line('**Сумма заказа:** ' . number_format($this->order->total_amount, 0, ',', ' ') . ' ₽')
            ->action('Посмотреть заказ', url('/orders'))
            ->line('Спасибо за ваш заказ!')
            ->salutation('С уважением, АО «Арвиай»');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }
}
