<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberPro - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; color: #333; }
        .barber-primary { background-color: #2A3B47; }
        .barber-accent { color: #C59D5F; }
        .form-input { border: 1px solid #ddd; border-radius: 0.375rem; padding: 0.5rem; width: 100%; }
        .barber-button { background-color: #C59D5F; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; }
        .barber-button-outline { border: 1px solid #C59D5F; color: #C59D5F; background: none; padding: 0.5rem 1rem; border-radius: 0.375rem; }
    </style>
</head>
<body>
    <div>
        @auth
            <nav class="barber-primary py-4 px-6 shadow-md">
                <div class="container mx-auto flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="https://cdn.pixabay.com/photo/2019/07/02/08/32/barber-pole-4311429_1280.png" alt="BarberPro Logo" class="w-10 h-10 mr-3">
                        <span class="text-white font-bold text-xl">BarberPro</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('barberpro') }}" class="text-white">Dashboard</a>
                        <a href="{{ route('appointments.index') }}" class="text-white">Citas</a>
                        <a href="{{ route('gallery') }}" class="text-white">Galería</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="barber-button-outline text-sm py-2 px-4">Salir</button>
                        </form>
                    </div>
                </div>
            </nav>
            <main class="container mx-auto py-8 px-4">
                <h1 class="text-3xl font-bold mb-6">Dashboard - BarberPro</h1>
                @if(isset($isNewUser) && $isNewUser)
                    <div id="welcomeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md">
                            <h2 class="text-2xl font-bold barber-accent mb-4">¡Bienvenido Nuevo Usuario!</h2>
                            <p class="mb-4">Configura tu perfil o agenda tu primera cita.</p>
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('profile') }}" class="barber-button-outline">Configurar Perfil</a>
                                <a href="{{ route('appointments.create') }}" class="barber-button">Agendar Cita</a>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold mb-4">Próxima Cita</h2>
                        @if(isset($nextAppointment) && $nextAppointment)
                            <p>Fecha: {{ $nextAppointment->appointment_date->format('d/m/Y H:i') }}</p>
                            <p>Servicio: {{ $nextAppointment->service }}</p>
                        @else
                            <p>No tienes citas.</p>
                            <a href="{{ route('appointments.create') }}" class="barber-button mt-4">Agendar</a>
                        @endif
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold mb-4">Galería Rápida</h2>
                        <div class="grid grid-cols-2 gap-2">
                            @forelse($galleryStyles->take(4) as $style)
                                <img src="{{ $style->image_url ?? asset('images/placeholder.jpg') }}" alt="{{ $style->name }}" class="w-full h-24 object-cover">
                            @empty
                                <p>No hay estilos.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h2 class="text-xl font-semibold mb-4">Citas Próximas</h2>
                    <ul class="list-disc pl-5">
                        @forelse($appointments as $appointment)
                            <li>{{ $appointment->service }} - {{ $appointment->appointment_date->format('d/m/Y H:i') }}</li>
                        @empty
                            <li>No hay citas próximas.</li>
                        @endforelse
                    </ul>
                </div>
            </main>
        @else
            <div class="text-center mt-10">
                <a href="{{ route('login') }}" class="barber-button mr-4">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="barber-button">Registrarse</a>
            </div>
        @endauth
    </div>
</body>
</html>