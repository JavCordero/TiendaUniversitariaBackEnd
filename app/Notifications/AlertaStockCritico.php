<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Producto;
use App\Mail\AlertaStock;

class AlertaStockCritico extends Notification
{
    use Queueable;

    public $mensajeCorreo;
    public $producto;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Producto $producto)
    {
        $this->mensajeCorreo = new AlertaStock($producto);
        $this->producto = $producto;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return ($this->mensajeCorreo)->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'email' => $notifiable->email,
            'nombre' => $this->producto->nombre,
            'cantidad' => $this->producto->cantidad,
            'stock_critico' => $this->producto->stock_critico
        ];
    }
}
