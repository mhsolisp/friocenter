<?php

namespace App\Mail;

use App\Models\Turno;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TurnoConfirmadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Turno $turno) {}

    public function build()
    {
        return $this
            ->subject('Tu turno en Frío Center — '.$this->turno->fecha_turno->format('d/m/Y'))
            ->view('emails.turno-confirmado');
    }
}
