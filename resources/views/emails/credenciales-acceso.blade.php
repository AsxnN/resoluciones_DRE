<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 8px 8px; }
        .credentials { background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Credenciales de Acceso</h1>
            <p>Sistema de Gestión de Resoluciones</p>
        </div>
        <div class="content">
            <p>Hola <strong>{{ $datos['nombre'] }}</strong>,</p>
            
            <p>Se ha creado una cuenta para ti en el Sistema de Gestión de Resoluciones porque estás relacionado con la siguiente resolución:</p>
            
            <div style="background: white; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <strong>📄 {{ $datos['resolucion']->num_resolucion }}</strong><br>
                <small>{{ $datos['resolucion']->asunto_resolucion }}</small>
            </div>

            <p><strong>Tus credenciales de acceso son:</strong></p>
            
            <div class="credentials">
                <p><strong>📧 Usuario (Email):</strong><br>{{ $datos['email'] }}</p>
                <p><strong>🔑 Contraseña:</strong><br><code style="background: #f0f0f0; padding: 5px 10px; border-radius: 3px; font-size: 16px;">{{ $datos['password'] }}</code></p>
            </div>

            <p style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <strong>⚠️ Importante:</strong> Por tu seguridad, te recomendamos cambiar tu contraseña después del primer inicio de sesión.
            </p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/login') }}" class="button">Iniciar Sesión Ahora</a>
            </p>

            <p>Si tienes alguna pregunta o problema para acceder, por favor contacta al administrador del sistema.</p>
        </div>
        <div class="footer">
            <p>Este es un correo automático, por favor no responder.</p>
            <p>&copy; {{ date('Y') }} Sistema de Gestión de Resoluciones</p>
        </div>
    </div>
</body>
</html>