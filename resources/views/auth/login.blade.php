<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Spoon’s Barber Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #0f0f0f;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url("{{ asset('storage/galeria/foto1.jpeg') }}") center/cover no-repeat;
            opacity: 0.35; z-index: -1; pointer-events: none;
            animation: zoom 20s ease-in-out infinite alternate;
        }

        @keyframes zoom { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }
        .fade-in { opacity: 0; animation: fadeIn 0.8s ease-out forwards; }
        @keyframes fadeIn { to { opacity: 1; } }

        .input-style {
            background: rgba(28, 28, 28, 0.9);
            border: 1px solid #3a3a3a; color: #f1f1f1;
            transition: 0.3s ease; backdrop-filter: blur(4px);
        }
        .input-style:focus { border-color: #d4af37; box-shadow: 0 0 10px rgba(212, 175, 55, 0.5); outline: none; }

        .btn-gold {
            background: linear-gradient(135deg, #d4af37, #b8962e);
            color: #111; font-weight: bold;
            transition: 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #e0c15a, #c9a43b);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5); transform: translateY(-2px);
        }

        .btn-google {
            display: inline-flex; align-items: center; justify-content: center;
            width: 42px; height: 42px; /* Más compacto */
            background: #222; border: 1px solid #333; border-radius: 50%;
            transition: all 0.3s ease; box-shadow: 0 3px 6px rgba(0,0,0,0.3);
        }
        .btn-google:hover {
            background: #d4af37; border-color: #d4af37; transform: translateY(-3px);
            box-shadow: 0 8px 12px rgba(212, 175, 55, 0.4);
        }
        .btn-google .svg-icon { fill: white; width: 18px; height: 18px; transition: fill 0.3s ease; }
        .btn-google:hover .svg-icon { fill: black; }

        .login-card {
            background: rgba(20, 20, 20, 0.92); backdrop-filter: blur(12px);
            border: 1px solid #333; border-radius: 18px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(212, 175, 55, 0.15);
        }
    </style>
</head>

<body class="flex justify-center items-center h-screen">

    <div class="login-card w-96 px-8 py-6 fade-in">

        <div class="text-center mb-5">
            <h1 class="text-3xl font-bold text-[#d4af37]">Spoon’s Barber Shop</h1>
            <p class="text-gray-300 text-xs mt-1">Estilo. Precisión. Actitud.</p>
        </div>

        <h2 class="text-xl font-semibold mb-4 text-[#d4af37] text-center">💈 Iniciar Sesión</h2>

        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-400 text-xs font-bold px-3 py-2 rounded mb-4 text-center">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form id="login-form" method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="block mb-1 text-gray-300 text-sm font-medium">Correo Electrónico</label>
                <input type="email" name="email" required class="w-full p-2.5 rounded input-style text-sm">
            </div>

            <div class="mb-1">
                <label class="block mb-1 text-gray-300 text-sm font-medium">Contraseña</label>
                <input type="password" name="password" required class="w-full p-2.5 rounded input-style text-sm">
            </div>

            <div class="text-right mb-4">
                <a href="{{ route('password.request') }}" class="text-xs text-[#d4af37] hover:underline font-medium transition-all">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            @error('email')
                <p class="text-red-400 text-xs mb-3 text-center">{{ $message }}</p>
            @enderror

            <button id="btn-login" type="submit" class="w-full btn-gold text-base py-2.5 rounded-lg flex items-center justify-center">
                <span id="btn-texto">Entrar</span>
            </button>
        </form>

        <div class="mt-4 text-center">
            <p class="text-gray-500 text-[10px] mb-2 uppercase tracking-widest">O ingresa con</p>
            <a href="{{ url('/login/google') }}" class="btn-google" title="Iniciar sesión con Google">
                <svg viewBox="0 0 488 512" xmlns="http://www.w3.org/2000/svg" class="svg-icon">
                    <path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path>
                </svg>
            </a>
        </div>

        <p class="text-center mt-4 text-gray-400 text-xs">
            ¿No tienes cuenta? <a href="{{ route('register') }}" class="text-[#d4af37] hover:underline font-medium">Regístrate aquí</a>
        </p>

    </div>

    <script>
        // 🛡️ ESCUDO CONTRA EL "DEDO NERVIOSO" EN EL LOGIN
        document.addEventListener('DOMContentLoaded', function () {
            const formLogin = document.getElementById('login-form');
            const btnLogin = document.getElementById('btn-login');
            const btnTexto = document.getElementById('btn-texto');

            formLogin.addEventListener('submit', function() {
                // Deshabilita el botón instantáneamente
                btnLogin.disabled = true;
                
                // Efecto visual: se vuelve opaco, el cursor cambia y pierde el efecto hover
                btnLogin.classList.add('opacity-70', 'cursor-wait');
                btnLogin.classList.remove('hover:transform', 'hover:-translate-y-2');
                
                // Cambia el texto e inyecta un pequeño spinner de Tailwind
                btnTexto.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Entrando...
                `;
            });
        });
    </script>
</body>
</html>