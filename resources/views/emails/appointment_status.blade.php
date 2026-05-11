<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #050505; color: #ffffff; margin: 0; padding: 30px 15px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #111111; border: 1px solid #333333; border-radius: 16px; padding: 40px; text-align: center; }
        .logo { font-size: 26px; font-weight: 900; color: #d4af37; margin-bottom: 20px; letter-spacing: 1px; }
        .title { font-size: 22px; font-weight: bold; margin-bottom: 15px; }
        .text-green { color: #10b981; }
        .text-red { color: #f43f5e; }
        .text-gold { color: #d4af37; }
        p { color: #cccccc; line-height: 1.6; font-size: 15px; }
        .details-box { background-color: #1a1a1a; border-radius: 12px; padding: 25px; margin: 30px 0; text-align: left; border: 1px solid #222222; }
        .details-box p { margin: 8px 0; font-size: 16px; }
        .details-box strong { color: #d4af37; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; display: inline-block; width: 90px; }
        .btn { display: inline-block; background-color: #d4af37; color: #000000; font-weight: 900; text-decoration: none; padding: 15px 30px; border-radius: 10px; margin-top: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .footer { margin-top: 40px; font-size: 12px; color: #666666; border-top: 1px solid #222222; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">✂️ Spoon's Barber Shop</div>

        @if($estado == 'confirmada')
            <h2 class="title">¡Tu cita ha sido <span class="text-green">Confirmada</span>! ✅</h2>
            <p>Hola <strong>{{ $appointment->user->name }}</strong>, tu barbero ya reservó el espacio para ti. Aquí tienes los detalles de tu corte:</p>
        
        @elseif($estado == 'cancelada')
            <h2 class="title">Tu cita ha sido <span class="text-red">Cancelada</span> ❌</h2>
            <p>Hola <strong>{{ $appointment->user->name }}</strong>, te informamos que tu cita ha sido cancelada. Si deseas agendar nuevamente, puedes hacerlo desde nuestra plataforma.</p>
        
        @elseif($estado == 'completada')
            <h2 class="title">¡Gracias por tu visita! <span class="text-gold">💈</span></h2>
            <p>Hola <strong>{{ $appointment->user->name }}</strong>, esperamos que hayas disfrutado tu experiencia en nuestra barbería y que luzcas genial con tu nuevo estilo.</p>
        @endif

        <div class="details-box">
            <p><strong>FECHA:</strong> {{ \Carbon\Carbon::parse($appointment->fecha)->format('d / m / Y') }}</p>
            <p><strong>HORA:</strong> {{ \Carbon\Carbon::parse($appointment->hora)->format('h:i A') }}</p>
            <p><strong>SERVICIO:</strong> {{ $appointment->servicio }}</p>
        </div>

        @if($estado == 'completada')
            <p style="margin-top: 30px; font-weight: bold; color: #fff;">Nos ayudaría muchísimo saber qué te pareció el servicio.</p>
            <a href="{{ route('references.index') }}" class="btn">⭐ Calificar Servicio</a>
        @endif

        <div class="footer">
            <p>Este es un correo automático generado por el sistema de Spoon's Barber Shop.</p>
            <p>&copy; {{ date('Y') }} Spoon's Barber Shop. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>