<?php

namespace App\Notifications;

use App\Models\Course;
use EscolaSoft\Shopping\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaid extends Notification
{
    use Queueable;

    private Order $order;
    private Authenticatable $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Authenticatable $user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)->greeting('Your new courses are waiting for you!');

        foreach ($this->order->items as $k => $item) {
            if (!$item->buyable instanceof Course) {
                continue;
            }

            $mail->line(($k + 1) . ':' . $item->buyable->name . '(' . config('app.frontend_url') . '/#/course/' . $item->buyable->getKey() . ')');
        }

        $mail->action('View them now', config('app.frontend_url'));

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order' => $this->order
        ];
    }
}
