<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Spoon’s Barber Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #18181b; /* Gris plata oscuro */
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-y: auto; 
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url("{{ asset('img/foto1.jpeg') }}") center/cover no-repeat;
            opacity: 0.15; z-index: -1; pointer-events: none; /* Opacidad reducida para el nuevo tono */
            animation: zoom 20s ease-in-out infinite alternate;
        }

        @keyframes zoom { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }
        .fade-in { opacity: 0; animation: fadeIn 0.8s ease-out forwards; }
        @keyframes fadeIn { to { opacity: 1; } }

        .input-style {
            background: rgba(39, 39, 42, 0.9); /* Gris acorde al fondo */
            border: 1px solid #3f3f46; color: #f1f1f1;
            transition: 0.3s ease; backdrop-filter: blur(4px);
            appearance: none; 
        }
        .input-style:focus { border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.5); outline: none; }

        .btn-blue {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff; font-weight: bold;
            transition: 0.3s ease;
        }
        .btn-blue:hover {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.5); transform: translateY(-2px);
        }

        .btn-google {
            display: inline-flex; align-items: center; justify-content: center;
            width: 48px; height: 48px; 
            background: #27272a; border: 1px solid #3f3f46; border-radius: 50%;
            transition: all 0.3s ease; box-shadow: 0 3px 6px rgba(0,0,0,0.3);
        }
        .btn-google:hover {
            background: #3b82f6; border-color: #3b82f6; transform: translateY(-3px);
            box-shadow: 0 8px 12px rgba(59, 130, 246, 0.4);
        }
        .btn-google .svg-icon { fill: white; width: 20px; height: 20px; transition: fill 0.3s ease; }
        .btn-google:hover .svg-icon { fill: white; }

        .login-card {
            background: rgba(39, 39, 42, 0.85); backdrop-filter: blur(12px);
            border: 1px solid #52525b; border-radius: 18px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(59, 130, 246, 0.15);
        }
    </style>
</head>

<body class="flex justify-center items-center min-h-screen p-4">

    <div class="login-card w-full max-w-sm sm:w-96 px-6 sm:px-8 py-8 fade-in">

        <div class="text-center mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-[#3b82f6] tracking-tight">Spoon’s Barber Shop</h1>
            <p class="text-gray-400 text-[10px] sm:text-xs mt-1 uppercase tracking-[0.2em]">Estilo • Precisión • Actitud</p>
        </div>

        <h2 class="text-lg sm:text-xl font-semibold mb-6 text-white text-center flex items-center justify-center gap-2">
            <span class="text-[#3b82f6]">💈</span> Iniciar Sesión
        </h2>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-400 text-xs font-bold px-3 py-2 rounded mb-4 text-center">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form id="login-form" method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1.5 text-gray-300 text-sm font-medium">Correo Electrónico</label>
                <input type="email" name="email" required maxlength="80" placeholder="tu@correo.com" 
                    class="w-full p-3 rounded input-style text-base sm:text-sm">
            </div>

            <div class="mb-2">
                <label class="block mb-1.5 text-gray-300 text-sm font-medium">Contraseña</label>
                <input type="password" name="password" required minlength="8" maxlength="20" placeholder="••••••••" 
                    class="w-full p-3 rounded input-style text-base sm:text-sm">
            </div>

            <div class="text-right mb-6">
                <a href="{{ route('password.request') }}" class="text-xs text-[#3b82f6] hover:text-[#60a5fa] transition-colors font-medium">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            @error('email')
                <p class="text-red-400 text-xs mb-4 text-center bg-red-400/10 py-2 rounded border border-red-400/20">{{ $message }}</p>
            @enderror

            <button id="btn-login" type="submit" class="w-full btn-blue text-base py-3 rounded-xl flex items-center justify-center shadow-lg">
                <span id="btn-texto" class="flex items-center">Entrar</span>
            </button>
        </form>

        <div class="mt-8 text-center relative">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-zinc-700"></div></div>
            <p class="relative inline-block px-4 bg-zinc-800 text-gray-400 text-[10px] uppercase tracking-widest rounded-full">O ingresa con</p>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ url('/login/google') }}" class="btn-google" title="Iniciar sesión con Google">
                <svg viewBox="0 0 488 512" xmlns="http://www.w3.org/2000/svg" class="svg-icon">
                    <path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                </svg>
            </a>
        </div>

        <p class="text-center mt-8 text-gray-400 text-sm">
            ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-[#3b82f6] hover:text-[#60a5fa] font-bold transition-colors">Regístrate</a>
        </p>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formLogin = document.getElementById('login-form');
            const btnLogin = document.getElementById('btn-login');
            const btnTexto = document.getElementById('btn-texto');

            formLogin.addEventListener('submit', function() {
                btnLogin.disabled = true;
                btnLogin.classList.add('opacity-70', 'cursor-not-allowed');
                
                btnTexto.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Autenticando...
                `;
            });
        });
    </script>
</body>
</html>