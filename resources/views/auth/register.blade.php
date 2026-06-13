<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Spoon’s Barber Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Sombra Azul del formulario */
        .blue-glow { box-shadow: 0 0 20px rgba(59, 130, 246, 0.25); }

        /* --- FONDO ESTELAR AZULADO (Uiverse.io) --- */
        .starfield-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background: linear-gradient(to top, rgba(24, 24, 27, 0.8) 0%, rgb(24, 24, 27) 50%, rgb(24, 24, 27) 100%);
            filter: url(#starfield-texture);
            animation: twinkle 4s ease-in-out infinite;
        }

        .texture-filter {
            position: absolute;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 1; filter: url(#starfield-texture) brightness(1); }
            50% { opacity: 0.85; filter: url(#starfield-texture) brightness(0.8); }
        }

        #meter-fill { transition: all 0.3s ease-out; }
    </style>
</head>

<body class="bg-zinc-900 min-h-screen flex justify-center items-center relative overflow-hidden font-sans">

    <div class="starfield-background"></div>
    
    <svg class="texture-filter">
        <filter id="starfield-texture">
            <feTurbulence type="fractalNoise" baseFrequency="0.1" numOctaves="8" result="noise"></feTurbulence>
            <feGaussianBlur in="noise" stdDeviation="0.5" result="blur"></feGaussianBlur>
            <feSpecularLighting in="blur" surfaceScale="2" specularConstant="1.5" specularExponent="30" lighting-color="#3b82f6" result="specular">
                <fePointLight z="100" y="50" x="50"></fePointLight>
            </feSpecularLighting>
            <feComposite in="specular" in2="SourceGraphic" operator="over" result="lit"></feComposite>
            <feBlend in="SourceGraphic" in2="lit" mode="hard-light"></feBlend>
        </filter>
    </svg>

    <div class="bg-zinc-800/60 backdrop-blur-xl p-8 rounded-3xl w-full max-w-md border border-zinc-600 blue-glow relative z-10 mx-4 shadow-2xl mt-10 mb-10">

        {{-- 🚨 ALERTA DE ERRORES DE VALIDACIÓN 🚨 --}}
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500 text-red-400 text-sm p-4 rounded mb-6 backdrop-blur-sm">
                <p class="font-bold mb-2">¡Ups! Revisa estos detalles antes de continuar:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="text-3xl font-black mb-6 text-center text-white tracking-wide flex items-center justify-center gap-2">
            <span class="text-[#3b82f6]">🧔</span> Registro
        </h2>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-5 relative">
                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Nombre Completo</label>
                <input type="text" name="name" id="name_client" required value="{{ old('name') }}"
                       pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Escribe un nombre real (solo letras, mínimo 3 caracteres)" 
                       minlength="3" maxlength="50"
                       class="w-full bg-zinc-900/80 text-white border border-zinc-600 rounded-xl p-3 focus:border-[#3b82f6] focus:ring-1 focus:ring-[#3b82f6] outline-none transition-all @error('name') border-red-500 @enderror"
                       placeholder="Ej. Uriel Martín">
                
                <p id="name-feedback" class="hidden text-red-400 text-xs mt-1 font-bold">⚠️ El nombre debe tener al menos 3 letras reales.</p>
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Correo Electrónico</label>
                <input type="email" name="email" required value="{{ old('email') }}" maxlength="80"
                       class="w-full bg-zinc-900/80 text-white border border-zinc-600 rounded-xl p-3 focus:border-[#3b82f6] focus:ring-1 focus:ring-[#3b82f6] outline-none transition-all @error('email') border-red-500 @enderror"
                       placeholder="tu@correo.com">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Contraseña</label>
                <input type="password" name="password" id="password_client" required minlength="8" maxlength="20"
                       class="w-full bg-zinc-900/80 text-white border border-zinc-600 rounded-xl p-3 focus:border-[#3b82f6] focus:ring-1 focus:ring-[#3b82f6] outline-none transition-all @error('password') border-red-500 @enderror"
                       placeholder="••••••••">
                
                <div class="h-1.5 w-full bg-zinc-700 rounded-full mt-2 overflow-hidden">
                    <div id="meter-fill" class="h-full bg-red-500 w-0"></div>
                </div>
                <p id="password-feedback" class="text-xs mt-1 text-gray-400">Mínimo 8, máximo 20 caracteres.</p>

                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-8">
                <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required minlength="8" maxlength="20"
                       class="w-full bg-zinc-900/80 text-white border border-zinc-600 rounded-xl p-3 focus:border-[#3b82f6] focus:ring-1 focus:ring-[#3b82f6] outline-none transition-all"
                       placeholder="••••••••">
            </div>

            <button id="btn-registro" type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-black py-3.5 rounded-xl transition-all shadow-[0_0_15px_rgba(59,130,246,0.4)] transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                <span id="btn-texto">Registrarse ✨</span>
            </button>

            <p class="text-center mt-6 text-sm text-gray-400 font-medium">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-[#3b82f6] hover:text-[#60a5fa] font-bold transition-colors">Inicia sesión</a>
            </p>
        </form>
    </div>

    <script>
        const formRegistro = document.querySelector('form');
        const btnRegistro = document.getElementById('btn-registro');
        const btnTexto = document.getElementById('btn-texto');

        formRegistro.addEventListener('submit', function() {
            btnRegistro.disabled = true;
            btnRegistro.classList.add('opacity-70', 'cursor-not-allowed');
            btnRegistro.classList.remove('hover:-translate-y-1', 'hover:from-blue-500');
            btnTexto.innerText = 'Creando cuenta... ⏳';
        });

        const nameInput = document.getElementById('name_client');
        const nameFeedback = document.getElementById('name-feedback');

        nameInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            const val = this.value.trim(); 

            if (this.value.length > 0 && val.length < 3) {
                nameFeedback.classList.remove('hidden');
                this.classList.add('border-red-500');
                this.classList.remove('border-zinc-600', 'focus:border-[#3b82f6]');
                this.setCustomValidity('El nombre debe tener al menos 3 letras.'); 
            } else {
                nameFeedback.classList.add('hidden');
                this.classList.remove('border-red-500');
                this.classList.add('border-zinc-600', 'focus:border-[#3b82f6]');
                this.setCustomValidity(''); 
            }
        });

        const passInput = document.getElementById('password_client');
        const meterFill = document.getElementById('meter-fill');
        const feedbackText = document.getElementById('password-feedback');

        passInput.addEventListener('input', function() {
            const val = passInput.value;
            let strength = 0;
            let message = "Mínimo 8, máximo 20 caracteres.";
            let colorClass = "bg-red-500";
            let widthClass = "w-0";

            if (val.length >= 8) {
                strength += 1;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength += 1;
                if (/[0-9]/.test(val)) strength += 1;
                if (/[^A-Za-z0-9]/.test(val)) strength += 1;

                if (strength === 1 || strength === 2) {
                    message = "Fuerza: Media (Añade mayúsculas, números o símbolos)";
                    colorClass = "bg-blue-400"; // Cambiado de amarillo a azul claro
                    widthClass = "w-1/2";
                } else if (strength >= 3) {
                    message = "Fuerza: Alta (¡Contraseña segura! 🔒)";
                    colorClass = "bg-green-500";
                    widthClass = "w-full";
                }
            } else if (val.length > 0) {
                message = "Fuerza: Baja (Muy corta)";
                colorClass = "bg-red-500";
                widthClass = "w-1/4";
            }

            meterFill.className = `h-full transition-all duration-300 ${colorClass} ${widthClass}`;
            feedbackText.innerText = message;
            
            if(strength >= 3) {
                feedbackText.classList.remove('text-gray-400', 'text-blue-400', 'text-red-400');
                feedbackText.classList.add('text-green-400');
            } else if (strength === 1 || strength === 2) {
                feedbackText.classList.remove('text-gray-400', 'text-green-400', 'text-red-400');
                feedbackText.classList.add('text-blue-400');
            } else {
                feedbackText.classList.remove('text-green-400', 'text-blue-400');
                feedbackText.classList.add('text-gray-400');
            }
        });
    </script>
</body>
</html>