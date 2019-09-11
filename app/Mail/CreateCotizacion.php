<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCotizacion extends Mailable
{
    use Queueable, SerializesModels;

    public $cliente;

    public $cotizacion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($cliente, $cotizacion)
    {
        //
        $this->cliente = $cliente;
        $this->cotizacion = $cotizacion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Gracias por usar Autosegurodirecto')->view('emails.cotizacion');
    }
}
