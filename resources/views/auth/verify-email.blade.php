<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Correo - Spoon's Barber Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-gray-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-300 relative overflow-hidden">
    
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-blue-400 dark:bg-[#3b82f6] opacity-[0.05] dark:opacity-[0.08] blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white dark:bg-zinc-800 rounded-3xl shadow-xl dark:shadow-2xl border border-slate-200 dark:border-zinc-700 p-8 md:p-10 relative z-10 transition-all hover:border-blue-400 dark:hover:border-[#3b82f6]/50 text-center">
        
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 dark:bg-gradient-to-br dark:from-zinc-700 dark:to-zinc-800 mb-5 border border-blue-200 dark:border-zinc-600 shadow-inner">
            <span class="text-3xl text-[#3b82f6]">📩</span>
        </div>
        
        <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-4">¡Ya casi terminamos!</h2>
        
        <p class="text-sm text-slate-500 dark:text-gray-400 mb-6 leading-relaxed font-medium">
            Bienvenido a <b>Spoon's Barber Shop</b>. Para proteger tu cuenta y poder agendar citas, por favor verifica tu correo electrónico haciendo clic en el enlace que te acabamos de enviar. <br><br> ¿No lo recibiste? Con gusto te enviamos otro.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/30 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 shadow-inner">
                <span>✅</span> ¡Un nuevo enlace ha sido enviado a tu correo!
            </div>
        @endif

        <div class="flex flex-col gap-4">
            {{-- 🛡️ Agregamos un onsubmit al formulario para disparar la función JS --}}
            <form method="POST" action="{{ route('verification.send') }}" onsubmit="disableButton()">
                @csrf
                {{-- 🛡️ Agregamos un id="btn-reenviar" para poder controlarlo con JS --}}
                <button type="submit" id="btn-reenviar" class="w-full bg-blue-600 dark:bg-gradient-to-r dark:from-[#3b82f6] dark:to-[#2563eb] hover:bg-blue-700 dark:hover:from-[#60a5fa] dark:hover:to-[#3b82f6] text-white dark:text-white font-black px-6 py-3.5 rounded-xl transition-all shadow-md transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    🔄 Reenviar Enlace
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-slate-100 dark:bg-zinc-900 hover:bg-slate-200 dark:hover:bg-zinc-700 text-slate-600 dark:text-gray-400 font-bold px-6 py-3.5 rounded-xl transition-all border border-slate-200 dark:border-zinc-700 flex justify-center items-center gap-2">
                    🚪 Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

    {{-- 🛡️ Script para deshabilitar el botón y mostrar estado de carga --}}
    <script>
        function disableButton() {
            const btn = document.getElementById('btn-reenviar');
            // Deshabilita el botón para evitar múltiples clics
            btn.disabled = true;
            // Cambia la apariencia para que parezca desactivado y cargando
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.classList.remove('hover:-translate-y-1'); 
            // Cambia el texto
            btn.innerHTML = '⏳ Enviando...';
        }
    </script>
</body>
</html>