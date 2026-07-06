<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales de Acceso — Sistema DRE</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f0f2f5;
            color: #2d3748;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 620px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        /* HEADER */
        .header {
            background-color: #0d3b6e;
            padding: 32px 40px;
            border-bottom: 4px solid #c0392b;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }
        .header-logo {
            width: 48px;
            height: 48px;
            background-color: #c0392b;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-logo span {
            color: white;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: -1px;
        }
        .header-org {
            color: #ffffff;
        }
        .header-org p {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #90aecb;
            margin-bottom: 2px;
        }
        .header-org strong {
            font-size: 14px;
            font-weight: 700;
        }
        .header h1 {
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .header p.subtitle {
            color: #90aecb;
            font-size: 13px;
            margin-top: 4px;
        }

        /* BODY */
        .body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 15px;
            color: #2d3748;
            margin-bottom: 16px;
        }
        .intro-text {
            font-size: 14px;
            color: #4a5568;
            margin-bottom: 24px;
        }

        /* RESOLUCION BOX */
        .resolution-box {
            background: #f7faff;
            border: 1px solid #bee3f8;
            border-left: 4px solid #3182ce;
            border-radius: 4px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .resolution-box p {
            font-size: 13px;
            color: #4a5568;
            margin-bottom: 4px;
        }
        .resolution-box strong {
            font-size: 14px;
            color: #1a365d;
        }

        /* CREDENTIALS */
        .credentials-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #718096;
            margin-bottom: 12px;
        }
        .credentials-box {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .credential-row {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        .credential-row:last-child {
            border-bottom: none;
        }
        .credential-label {
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 100px;
            flex-shrink: 0;
        }
        .credential-value {
            font-size: 15px;
            font-weight: 600;
            color: #1a202c;
            font-family: 'Courier New', Courier, monospace;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 4px 12px;
        }

        /* NOTICE */
        .notice {
            background: #fffbf0;
            border: 1px solid #f6e05e;
            border-left: 4px solid #d69e2e;
            border-radius: 4px;
            padding: 14px 18px;
            margin-bottom: 28px;
        }
        .notice p {
            font-size: 13px;
            color: #744210;
        }
        .notice strong {
            font-weight: 700;
        }

        /* CTA */
        .cta-wrapper {
            text-align: center;
            margin-bottom: 28px;
        }
        .cta-button {
            display: inline-block;
            background-color: #0d3b6e;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 13px 36px;
            border-radius: 4px;
        }

        /* SUPPORT */
        .support-text {
            font-size: 13px;
            color: #718096;
            margin-bottom: 8px;
        }

        /* DIVIDER */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 28px 0;
        }

        /* FOOTER */
        .footer {
            background: #f7fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 40px;
            text-align: center;
        }
        .footer p {
            font-size: 11px;
            color: #a0aec0;
            line-height: 1.8;
        }
        .footer strong {
            color: #718096;
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- HEADER -->
    <div class="header">
        <div class="header-brand">
            <div class="header-logo">
                <span>DRE</span>
            </div>
            <div class="header-org">
                <p>Gobierno Regional</p>
                <strong>Dirección Regional de Educación</strong>
            </div>
        </div>
        <h1>Credenciales de Acceso</h1>
        <p class="subtitle">Sistema de Gestión de Resoluciones</p>
    </div>

    <!-- BODY -->
    <div class="body">

        <p class="greeting">Estimado(a) <strong>{{ $datos['nombre'] }}</strong>,</p>

        @if(!empty($datos['resolucion']))
        <p class="intro-text">
            Se le ha habilitado una cuenta de acceso en el Sistema de Gestión de Resoluciones
            de la Dirección Regional de Educación, en relación con la siguiente resolución:
        </p>
        <div class="resolution-box">
            <p>Resolución</p>
            <strong>{{ $datos['resolucion']->num_resolucion }}</strong>
            @if($datos['resolucion']->asunto_resolucion)
            <p style="margin-top: 6px; font-size: 13px;">{{ $datos['resolucion']->asunto_resolucion }}</p>
            @endif
        </div>
        @else
        <p class="intro-text">
            A continuación se detallan sus credenciales de acceso al Sistema de Gestión de
            Resoluciones de la Dirección Regional de Educación.
        </p>
        @endif

        <p class="credentials-title">Sus credenciales de acceso</p>
        <div class="credentials-box">
            <div class="credential-row">
                <span class="credential-label">Usuario</span>
                <span class="credential-value">{{ $datos['username'] }}</span>
            </div>
            <div class="credential-row">
                <span class="credential-label">Contraseña</span>
                <span class="credential-value">{{ $datos['password'] }}</span>
            </div>
        </div>

        <div class="notice">
            <p>
                <strong>Aviso de seguridad:</strong> Por razones de seguridad, se recomienda cambiar
                su contraseña inmediatamente después de iniciar sesión por primera vez. Esta contraseña
                es de uso personal e intransferible.
            </p>
        </div>

        <div class="cta-wrapper">
            <a href="{{ route('login') }}" class="cta-button">Ingresar al Sistema</a>
        </div>

        <hr class="divider">

        <p class="support-text">
            Si tiene dificultades para acceder o requiere asistencia, comuníquese con el
            área de sistemas de la Dirección Regional de Educación.
        </p>
        <p class="support-text">
            Por favor, no responda a este mensaje. Este correo es generado automáticamente
            por el sistema.
        </p>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>
            <strong>Dirección Regional de Educación</strong><br>
            Sistema de Gestión de Resoluciones<br>
            Este correo electrónico es confidencial y está dirigido únicamente a su destinatario.<br>
            &copy; {{ date('Y') }} Gobierno Regional — Todos los derechos reservados.
        </p>
    </div>

</div>
</body>
</html>
