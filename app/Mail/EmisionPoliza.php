<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmisionPoliza extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($polizaResp,$aseguradora)
    {
        //
        $this->emision = $polizaResp;
        $this->aseg = $aseguradora;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $mensaje = 'Marco A Mtx';
        return $this->markdown('Mail.emision')->with('mensaje' ,$this->emision)->with('aseguradora', $this->aseg);
    }
}
