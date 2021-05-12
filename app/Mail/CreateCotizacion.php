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
    public function __construct($cliente, $cotizacion, $aseguradora)
    {
        //
        $this->cliente = $cliente;
        $this->cotizacion = $cotizacion;
        $this->aseguradora = $aseguradora;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->aseguradora == "ANA") {
            return $this->subject('Gracias por cotizar en Autosegurodirecto ANA SEGUROS')->view('emails.cotizacionAna');
        }
        if ($this->aseguradora == "GNP") {
            return $this->subject('Gracias por cotizar en Autosegurodirecto GNP SEGUROS')->view('emails.cotizacionGNP');
        }
        if ($this->aseguradora == "GS") {
            // dd( $this->cotizacion);
            return $this->subject('Gracias por cotizar en Autosegurodirecto GENERAL DE SEGUROS')->view('emails.cotizacionGS');
        }
        if ($this->aseguradora == "QA") {
             // dd( $this->cotizacion);
            return $this->subject('Gracias por cotizar en Autosegurodirecto QUALITAS SEGUROS')->view('emails.cotizacionQUA');
        }
        else
            return $this->subject('Gracias por usar Autosegurodirecto')->view('emails.cotizacion');
    }
}
