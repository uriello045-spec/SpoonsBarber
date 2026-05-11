<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Correo - Spoon's Barber Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-gray-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-300 relative overflow-hidden">
    
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-yellow-400 dark:bg-[#d4af37] opacity-[0.05] dark:opacity-[0.08] blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white dark:bg-[#141414] rounded-3xl shadow-xl dark:shadow-2xl border border-slate-200 dark:border-[#2a2a2a] p-8 md:p-10 relative z-10 transition-all hover:border-yellow-400 dark:hover:border-[#d4af37]/50 text-center">
        
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-yellow-50 dark:bg-gradient-to-br dark:from-[#222] dark:to-[#111] mb-5 border border-yellow-200 dark:border-[#333] shadow-inner">
            <span class="text-3xl">📩</span>
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
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 dark:from-[#d4af37] dark:to-[#b8962e] hover:from-yellow-500 hover:to-yellow-600 dark:hover:from-[#e0c15a] dark:hover:to-[#c9a43b] text-slate-900 dark:text-black font-black px-6 py-3.5 rounded-xl transition-all shadow-md transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    🔄 Reenviar Enlace
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-slate-100 dark:bg-[#1a1a1a] hover:bg-slate-200 dark:hover:bg-[#222] text-slate-600 dark:text-gray-400 font-bold px-6 py-3.5 rounded-xl transition-all border border-slate-200 dark:border-[#333] flex justify-center items-center gap-2">
                    🚪 Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>