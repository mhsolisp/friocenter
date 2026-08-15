<?php

namespace App\Mail;

use App\Models\Turno;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PresupuestoPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Turno $turno,
        public string $pdfContenido,
        public string $nombreArchivo,
    ) {}

    public function build()
    {
        return $this
            ->subject('Presupuesto Frío Center — '.($this->turno->presupuesto->numero_completo ?? ''))
            ->view('emails.presupuesto')
            ->attachData($this->pdfContenido, $this->nombreArchivo, [
                'mime' => 'application/pdf',
            ]);
    }
}
