<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CriticoMetricaMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $titulo, private $values, private $valuesGrafico)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('sentinela@devprod.com.br', 'Devprod Sentinela'),
            subject: "Métricas Críticas - {$this->titulo}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.critico-metrica',
            with: ['titulo' => $this->titulo, 'values' => $this->values, 'valuesGrafico' => $this->valuesGrafico],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
