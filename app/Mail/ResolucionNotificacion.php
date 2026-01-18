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
        public ?Persona $persona = null  // ← HACER OPCIONAL
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

        // Adjuntar PDF si existe (priorizar archivo firmado)
        $archivoAdjuntar = $this->resolucion->archivo_firmado ?? $this->resolucion->archivo_resolucion;
        
        if ($archivoAdjuntar) {
            $fullPath = storage_path('app/public/' . $archivoAdjuntar);
            
            // Verificar que el archivo realmente existe en el sistema de archivos
            if (file_exists($fullPath) && is_readable($fullPath)) {
                $nombreArchivo = $this->resolucion->archivo_firmado 
                    ? $this->resolucion->num_resolucion . '_firmado.pdf'
                    : $this->resolucion->num_resolucion . '.pdf';
                    
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as($nombreArchivo)
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}