<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            margin: 0;
            font-size: 18px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f1f5f9;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
            border-radius: 5px;
        }
        .info-row {
            margin: 10px 0;
            display: flex;
            gap: 10px;
        }
        .info-row strong {
            color: #2563eb;
            min-width: 120px;
        }
        .asunto-box {
            background: #fef3c7;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
            border-radius: 5px;
        }
        .asunto-box strong {
            color: #f59e0b;
        }
        .visto-box {
            background: #e0f2fe;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #0284c7;
            border-radius: 5px;
        }
        .visto-box strong {
            color: #0284c7;
        }
        .attachment-notice {
            background: #d1fae5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            color: #065f46;
        }
        .footer {
            background: #1f2937;
            color: #9ca3af;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📋 Nueva Resolución Emitida</h1>
            <p>{{ $resolucion->num_resolucion }}</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                <p>Estimado(a) <strong>{{ $persona->apellido_paterno }} {{ $persona->apellido_materno }}, {{ $persona->nombres }}</strong>,</p>
            </div>
            
            <p>Le informamos que se ha emitido una nueva <strong>Resolución</strong> en la que usted está relacionado(a).</p>
            
            <div class="info-box">
                <div class="info-row">
                    <strong>N° Resolución:</strong> 
                    <span>{{ $resolucion->num_resolucion }}</span>
                </div>
                <div class="info-row">
                    <strong>Fecha Emisión:</strong> 
                    <span>{{ \Carbon\Carbon::parse($resolucion->fecha_resolucion)->format('d/m/Y') }}</span>
                </div>
                <div class="info-row">
                    <strong>Tipo:</strong> 
                    <span>{{ $resolucion->tipoResolucion->nombre_tipo_resolucion ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <strong>Estado:</strong> 
                    <span>{{ $resolucion->estado->nombre_estado ?? 'N/A' }}</span>
                </div>
            </div>

            @if($resolucion->visto_resolucion)
            <div class="visto-box">
                <strong>VISTO:</strong>
                <p style="margin: 10px 0 0 0; white-space: pre-wrap;">{{ $resolucion->visto_resolucion }}</p>
            </div>
            @endif
            
            <div class="asunto-box">
                <strong>ASUNTO:</strong>
                <p style="margin: 10px 0 0 0;">{{ $resolucion->asunto_resolucion }}</p>
            </div>

            @if($resolucion->archivo_resolucion)
            <div class="attachment-notice">
                <strong>📎 Se adjunta el documento oficial de la resolución en formato PDF</strong>
            </div>
            @endif
            
            <p style="margin-top: 30px;">Para más información, consultas o aclaraciones sobre esta resolución, por favor contacte con la administración correspondiente.</p>
            
            <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
                <em>Este documento tiene carácter informativo. Conserve este correo para futuras referencias.</em>
            </p>
        </div>
        
        <div class="footer">
            <p><strong>Sistema de Gestión de Resoluciones</strong></p>
            <p>Este es un correo automático generado por el sistema.</p>
            <p>Por favor, no responda directamente a este mensaje.</p>
            <p style="margin-top: 15px; opacity: 0.7;">&copy; {{ date('Y') }} Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>