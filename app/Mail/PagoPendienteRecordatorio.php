<?php

namespace App\Mail;

use App\Models\Estudiante;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PagoPendienteRecordatorio extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Estudiante $estudiante, public float $deuda, public string $nombreAcademia) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Recordatorio de pago pendiente');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.pago-pendiente');
    }
}
