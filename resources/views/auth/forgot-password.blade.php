<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Spoon’s Barber Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #18181b; /* Gris plata oscuro */
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url("{{ asset('storage/galeria/foto1.jpeg') }}") center/cover no-repeat;
            opacity: 0.10;
            z-index: -1;
            pointer-events: none;
        }

        .recovery-card {
            background: #27272a; /* Gris acorde */
            border: 1px solid #3f3f46;
            border-radius: 20px;
            padding: 40px 35px;
            width: 90%;
            max-width: 420px;
            /* Resplandor Azul tenue detrás de la tarjeta */
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.08), 0 10px 30px rgba(0,0,0,0.8);
            z-index: 10;
        }

        .input-style {
            background: #18181b;
            border: 1px solid #3f3f46;
            color: #f1f1f1;
            transition: all 0.3s ease;
        }

        .input-style:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .btn-blue {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            font-weight: 800;
            transition: all 0.3s ease;
        }

        .btn-blue:hover {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="recovery-card">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white mb-3">🔐 Recuperar</h2>
            <p class="text-gray-400 text-xs px-2 leading-relaxed">
                Ingresa tu correo y te enviaremos un enlace mágico para restablecer tu contraseña.
            </p>
        </div>

        @if(session('status'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-400 text-xs font-bold px-4 py-3 rounded mb-6 text-center shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                ✅ {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label class="block mb-2 text-gray-400 text-[10px] font-bold uppercase tracking-widest">Correo Electrónico</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required 
                    class="w-full p-3.5 rounded-xl input-style text-sm" 
                    placeholder="tu@correo.com"
                >
                @error('email')
                    <p class="text-red-400 text-xs mt-2 text-center font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full btn-blue text-sm py-3.5 rounded-xl mb-6">
                Enviar Enlace ✨
            </button>
        </form>

        <div class="text-center mt-2">
            <p class="text-gray-400 text-xs">
                ¿Recordaste tu contraseña? 
                <a href="{{ route('login') }}" class="text-[#3b82f6] font-bold hover:underline transition-all">
                    Volver al Login
                </a>
            </p>
        </div>
    </div>

</body>
</html>