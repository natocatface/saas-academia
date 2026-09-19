<?php

namespace App\Mail;

use App\Models\Academia;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuscripcionPorVencer extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Academia $academia, public int $diasRestantes) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Tu suscripción está por vencer');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.suscripcion-vencer');
    }
}
