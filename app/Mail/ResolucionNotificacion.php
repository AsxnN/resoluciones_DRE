<?php

namespace App\Mail;

use App\Models\Resolucion;
use App\Models\Persona;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ResolucionNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Resolucion $resolucion,
        public Persona $persona
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Resolución: ' . $this->resolucion->num_resolucion,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.resolucion-notificacion',
            with: [
                'resolucion' => $this->resolucion,
                'persona' => $this->persona,
            ]
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        // Adjuntar PDF si existe
        if ($this->resolucion->archivo_resolucion) {
            $fullPath = storage_path('app/public/' . $this->resolucion->archivo_resolucion);
            
            // Verificar que el archivo realmente existe en el sistema de archivos
            if (file_exists($fullPath) && is_readable($fullPath)) {
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as($this->resolucion->num_resolucion . '.pdf')
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}