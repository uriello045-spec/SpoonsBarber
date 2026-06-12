<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Cita</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #334155; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #0f172a; color: #ffffff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .content { padding: 30px; }
        .welcome { font-size: 16px; font-weight: bold; margin-bottom: 20px; }
        .details-box { background-color: #f1f5f9; border-left: 4px solid #d4af37; padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .detail-item { margin-bottom: 12px; font-size: 15px; }
        .detail-item:last-child { margin-bottom: 0; }
        .label { font-weight: bold; color: #475569; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #94a3b8; background-color: #f8fafc; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>

    <div class="card">
        <div class="header">
            <h1>💈 Spoon’s Barber Shop</h1>
        </div>
        
        <div class="content">
            <p class="welcome">¡Hola!</p>
            <p>Se ha registrado una nueva cita en el sistema y has sido asignado para atenderla. A continuación, se detallan los datos correspondientes:</p>
            
            <div class="details-box">
                <div class="detail-item">
                    <span class="label">👤 Cliente:</span> 
                    {{ $cita->user->name ?? 'Cliente Físico' }}
                </div>
                <div class="detail-item">
                    <span class="label">📅 Fecha:</span> 
                    {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                </div>
                <div class="detail-item">
                    <span class="label">⏰ Hora:</span> 
                    {{ \Carbon\Carbon::parse($cita->hora)->format('H:i A') }}
                </div>
                <div class="detail-item">
                    <span class="label">✂️ Servicio:</span> 
                    {{ $cita->servicio }}
                </div>
            </div>
            
            <p>Por favor, asegúrate de estar disponible a la hora indicada para brindar el mejor servicio.</p>
        </div>
        
        <div class="footer">
            Este es un mensaje automático generado por el módulo de control de Spoon’s Barber Shop.
        </div>
    </div>

</body>
</html>