<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberPro - Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; color: #333; }
        .barber-primary { background-color: #2A3B47; }
        .barber-accent { color: #C59D5F; }
        .barber-button { background-color: #C59D5F; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; }
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
                <h1 class="text-3xl font-bold mb-6 barber-accent">Mi Perfil</h1>
                <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
                    <p><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Teléfono:</strong> {{ Auth::user()->phone ?? 'No especificado' }}</p>
                    <form action="{{ route('profile.update') }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium">Nombre</label>
                            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="form-input" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="form-input" required>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium">Teléfono</label>
                            <input type="text" name="phone" id="phone" value="{{ Auth::user()->phone ?? '' }}" class="form-input">
                        </div>
                        <button type="submit" class="barber-button">Actualizar Perfil</button>
                    </form>
                    @if ($errors->any())
                        <div class="mt-4 text-red-600">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
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