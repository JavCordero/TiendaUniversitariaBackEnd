<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Producto;

class AlertaStock extends Mailable
{
    use Queueable, SerializesModels;


    public $producto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Producto $producto)
    {
        $this->producto = $producto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Tienda Universitaria: Alerta de stock crítico")->markdown('alertaStock',['producto' => $this->producto]);
        //return $this->subject("Tienda Universitaria: Alerta de stock crítico")->view('alertaStock')->with('producto', $this->producto);
    }
}
