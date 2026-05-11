<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', "Spoon's Barber Shop") }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- ESTILOS GENERALES --- */
        .btn-salir { background: linear-gradient(135deg, #dc2626, #b91c1c); padding: 8px 20px; color: white; border-radius: 12px; font-weight: bold; transition: transform 0.2s; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3); }
        .btn-salir:hover { transform: scale(1.05); }
        
        /* ==========================================================
           🌟 TÍTULO ANIMADO (ESTILO BRUTALISTA / GLITCH DORADO) 🌟
           ========================================================== */
        .logo-text { 
            font-weight: 900; 
            color: #ffd700; 
            position: relative;
            letter-spacing: 1px;
            animation: textChaosGold 4s ease-in-out infinite;
            user-select: none;
        }

        .logo-text::before {
            content: attr(data-text);
            position: absolute;
            top: 0; left: 0;
            color: rgba(255, 215, 0, 0.5); 
            animation: textGhost 2s linear infinite;
            z-index: -1;
        }

        .logo-text::after {
            content: attr(data-text);
            position: absolute;
            top: 0; left: 0;
            color: rgba(255, 140, 0, 0.4); 
            animation: textGhost2 1.5s linear infinite reverse;
            z-index: -1;
        }

        @keyframes textChaosGold {
            0%, 85%, 100% { transform: translate(0) scale(1); filter: saturate(1); text-shadow: 2px 2px 0 #000, 4px 4px 0 rgba(212, 175, 55, 0.5), 6px 6px 0 rgba(255, 215, 0, 0.3), 0 0 15px rgba(212, 175, 55, 0.6); }
            5% { transform: translate(-2px, 1px) scale(0.98); filter: saturate(1.5); text-shadow: 3px 1px 0 #000, 5px 3px 0 rgba(255, 215, 0, 0.7), 7px 5px 0 rgba(255, 140, 0, 0.5), 0 0 25px rgba(255, 215, 0, 0.9); }
            10% { transform: translate(2px, -2px) scale(1.02); filter: saturate(1.8); text-shadow: 1px 3px 0 #000, 3px 5px 0 rgba(255, 200, 0, 0.7), 5px 7px 0 rgba(212, 175, 55, 0.5), 0 0 20px rgba(255, 200, 0, 0.8); }
            15% { transform: translate(-1px, 2px) scale(0.99); filter: saturate(1.3); text-shadow: 3px 2px 0 #000, 5px 4px 0 rgba(255, 165, 0, 0.7), 7px 6px 0 rgba(255, 215, 0, 0.5), 0 0 18px rgba(255, 165, 0, 0.9); }
            20% { transform: translate(1px, -1px) scale(1.01); filter: saturate(1.1); text-shadow: 2px 3px 0 #000, 4px 5px 0 rgba(212, 175, 55, 0.6), 6px 7px 0 rgba(255, 140, 0, 0.4), 0 0 15px rgba(212, 175, 55, 0.8); }
        }

        @keyframes textGhost { 0% { transform: translate(0); opacity: 0.3; } 25% { transform: translate(1px, -1px); opacity: 0.6; } 50% { transform: translate(-1px, 1px); opacity: 0.2; } 75% { transform: translate(2px, 1px); opacity: 0.5; } 100% { transform: translate(0); opacity: 0.3; } }
        @keyframes textGhost2 { 0% { transform: translate(0); opacity: 0.2; } 33% { transform: translate(-2px, 1px); opacity: 0.5; } 66% { transform: translate(1px, -2px); opacity: 0.1; } 100% { transform: translate(0); opacity: 0.2; } }

        /* ==========================================================
           🌟 BOTONES NEÓN DORADOS (INICIO, CITAS, RESEÑAS) 🌟
           ========================================================== */
        .nav-neon-btn {
            --glow-color: #d4af37; 
            --glow-spread-color: rgba(212, 175, 55, 0.4);
            --btn-color: #ffffff; 
            
            border: 2px solid var(--glow-color);
            padding: 0.6em 1.5em;
            color: var(--glow-color);
            font-size: 14px;
            font-weight: bold;
            background-color: var(--btn-color);
            border-radius: 1em;
            outline: none;
            box-shadow: 0 0 0.5em 0.1em var(--glow-color), 0 0 1.5em 0.2em var(--glow-spread-color), inset 0 0 0.3em 0.1em var(--glow-color);
            text-shadow: 0 0 0.3em var(--glow-color);
            position: relative;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .dark .nav-neon-btn {
            --glow-color: #ffd700; 
            --glow-spread-color: rgba(255, 215, 0, 0.4);
            --btn-color: #121212; 
        }

        .nav-neon-btn::after {
            pointer-events: none; content: ""; position: absolute; top: 110%; left: 0; height: 100%; width: 100%;
            background-color: var(--glow-spread-color); filter: blur(1.5em); opacity: .6; transform: perspective(1.5em) rotateX(35deg) scale(1, .6);
        }

        .nav-neon-btn:hover { color: var(--btn-color); background-color: var(--glow-color); box-shadow: 0 0 0.8em 0.2em var(--glow-color), 0 0 2em 0.5em var(--glow-spread-color), inset 0 0 0.5em 0.2em var(--glow-color); }
        .nav-neon-btn:active { box-shadow: 0 0 0.4em 0.1em var(--glow-color), 0 0 1em 0.3em var(--glow-spread-color), inset 0 0 0.3em 0.1em var(--glow-color); transform: scale(0.95); }

        /* ==========================================================
           🔴 BOTÓN NEÓN ROJO (SALIR) 🔴
           ========================================================== */
        .nav-neon-btn-red {
            --glow-color: #dc2626; 
            --glow-spread-color: rgba(220, 38, 38, 0.4);
            --btn-color: #ffffff;
            border: 2px solid var(--glow-color); padding: 0.6em 1.5em; color: var(--glow-color); font-size: 14px; font-weight: bold; background-color: var(--btn-color); border-radius: 1em; outline: none; box-shadow: 0 0 0.5em 0.1em var(--glow-color), 0 0 1.5em 0.2em var(--glow-spread-color), inset 0 0 0.3em 0.1em var(--glow-color); text-shadow: 0 0 0.3em var(--glow-color); position: relative; transition: all 0.3s; text-decoration: none; display: inline-block; text-transform: uppercase; letter-spacing: 1px; cursor: pointer;
        }

        .dark .nav-neon-btn-red { --glow-color: #ff4d4d; --glow-spread-color: rgba(255, 77, 77, 0.4); --btn-color: #121212; }
        .nav-neon-btn-red::after { pointer-events: none; content: ""; position: absolute; top: 110%; left: 0; height: 100%; width: 100%; background-color: var(--glow-spread-color); filter: blur(1.5em); opacity: .6; transform: perspective(1.5em) rotateX(35deg) scale(1, .6); }
        .nav-neon-btn-red:hover { color: var(--btn-color); background-color: var(--glow-color); box-shadow: 0 0 0.8em 0.2em var(--glow-color), 0 0 2em 0.5em var(--glow-spread-color), inset 0 0 0.5em 0.2em var(--glow-color); }
        .nav-neon-btn-red:active { box-shadow: 0 0 0.4em 0.1em var(--glow-color), 0 0 1em 0.3em var(--glow-spread-color), inset 0 0 0.3em 0.1em var(--glow-color); transform: scale(0.95); }

        /* Estilo especial cuando el botón está deshabilitado temporalmente */
        .nav-neon-btn-red:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
        }

        /* ==========================================================
           🌓 NUEVO INTERRUPTOR MODO CLARO/OSCURO (3D Uiverse) 🌓
           ========================================================== */
        .switch {
            font-size: 17px;
            position: relative;
            display: inline-block;
            width: 3.5em;
            height: 2em;
            transform-style: preserve-3d;
            perspective: 500px;
            animation: toggle__animation 3s infinite;
        }

        .switch::before {
            content: ""; position: absolute; width: 100%; height: 100%; left: 0; top: 0;
            filter: blur(20px); z-index: -1; border-radius: 50px; background-color: #d8ff99;
            background-image: radial-gradient(at 21% 46%, hsla(183,65%,60%,1) 0px, transparent 50%),
            radial-gradient(at 23% 25%, hsla(359,74%,70%,1) 0px, transparent 50%),
            radial-gradient(at 20% 1%, hsla(267,83%,75%,1) 0px, transparent 50%),
            radial-gradient(at 86% 87%, hsla(204,69%,68%,1) 0px, transparent 50%),
            radial-gradient(at 99% 41%, hsla(171,72%,77%,1) 0px, transparent 50%),
            radial-gradient(at 55% 24%, hsla(138,60%,62%,1) 0px, transparent 50%);
        }

        .switch input { opacity: 0; width: 0; height: 0; }

        .slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background-color: #fdfefedc; transition: .4s; border-radius: 30px;
        }

        .slider:before {
            position: absolute; content: ""; height: 1.4em; width: 1.4em; left: 0.3em; bottom: 0.3em;
            transition: .4s; border-radius: 50%;
            box-shadow: rgba(0, 0, 0, 0.17) 0px -10px 10px 0px inset, rgba(0, 0, 0, 0.09) 0px -1px 15px -8px;
            background-color: #ff99fd;
            background-image: radial-gradient(at 81% 39%, hsla(327,79%,79%,1) 0px, transparent 50%),
            radial-gradient(at 11% 72%, hsla(264,64%,79%,1) 0px, transparent 50%),
            radial-gradient(at 23% 20%, hsla(75,98%,71%,1) 0px, transparent 50%);
        }

        .input__check:checked + .slider { background-color: #17202A; }
        .input__check:checked + .slider:before { transform: translateX(1.5em); }

        @keyframes toggle__animation {
            0%, 100% { transform: translateY(-3px) rotateX(15deg) rotateY(-20deg); }
            50% { transform: translateY(3px) rotateX(15deg) rotateY(-20deg); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleInput = document.getElementById('toggle');
            
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                toggleInput.checked = true; 
            } else {
                document.documentElement.classList.remove('dark');
                toggleInput.checked = false;
            }

            toggleInput.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                }
            });
        });
    </script>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900 dark:bg-[#050505] dark:text-white transition-colors duration-300 relative">

    <nav class="sticky top-0 z-50 px-6 py-4 flex justify-between items-center shadow-sm 
                bg-white border-b border-slate-200 
                dark:bg-[#0a0a0a] dark:border-[#222] transition-colors duration-300">
        
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="text-3xl">✂️</span>
            <h1 class="text-2xl logo-text" data-text="Spoon’s Barber Shop">Spoon’s Barber Shop</h1>
        </a>

        <div class="flex items-center space-x-6">
            <a href="{{ route('dashboard') }}" class="nav-neon-btn hidden md:inline-block">Inicio</a>
            <a href="{{ route('appointments.index') }}" class="nav-neon-btn hidden md:inline-block">Citas</a>
            <a href="{{ route('references.index') }}" class="nav-neon-btn hidden md:inline-block">Reseñas</a>

            @if(Auth::check())
                {{-- Primero verificamos si tiene la columna is_superadmin en true --}}
                @if(Auth::user()->is_superadmin)
                    <div class="hidden sm:flex items-center gap-2 bg-gradient-to-r from-purple-700 to-indigo-600 border border-purple-400/50 text-white px-4 py-1.5 rounded-full shadow-[0_0_15px_rgba(147,51,234,0.4)] transform hover:scale-105 transition-all" title="Privilegios Máximos">
                        <span class="text-sm">👑</span>
                        <span class="text-xs font-black uppercase tracking-widest">Super Admin</span>
                    </div>
                {{-- Si no es superadmin, entonces checamos si su rol normal es barbero --}}
                @elseif(Auth::user()->role === 'barbero')
                    <div class="hidden sm:flex items-center gap-2 bg-gradient-to-r from-[#d4af37] to-[#b8860b] border border-yellow-300/50 text-black px-4 py-1.5 rounded-full shadow-[0_0_15px_rgba(212,175,55,0.4)] transform hover:scale-105 transition-all">
                        <span class="text-sm">💈</span>
                        <span class="text-xs font-black uppercase tracking-widest">Barbero</span>
                    </div>
                {{-- Si no es nada de lo anterior, es cliente --}}
                @elseif(Auth::user()->role === 'cliente')
                    <div class="hidden sm:flex items-center gap-2 bg-slate-200 dark:bg-[#1a1a1a] border border-slate-300 dark:border-[#333] text-slate-700 dark:text-gray-300 px-4 py-1.5 rounded-full">
                        <span class="text-sm">👤</span>
                        <span class="text-xs font-bold truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                    </div>
                @endif
            @endif

            <div class="flex items-center justify-center mx-2">
                <label class="switch">
                  <input type="checkbox" class="input__check" id="toggle">
                  <span class="slider"></span>
                </label>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                @csrf
                <button type="submit" class="nav-neon-btn-red"
                        onclick="this.disabled=true; this.innerHTML='Saliendo... ⏳'; this.closest('form').submit();">
                    Salir
                </button>
            </form>
        </div>
    </nav>

    <main>
        {{ $slot ?? '' }} 
        @yield('content')
    </main>

    @if(Auth::check() && Auth::user()->role === 'cliente')
        @include('chatbot.index')
    @endif

    @yield('scripts')

</body>
</html>