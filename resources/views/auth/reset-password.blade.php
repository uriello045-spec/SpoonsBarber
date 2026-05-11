<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Spoon's Barber Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-gray-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-300 relative overflow-hidden">
    
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-yellow-400 dark:bg-[#d4af37] opacity-[0.05] dark:opacity-[0.08] blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white dark:bg-[#141414] rounded-3xl shadow-xl dark:shadow-2xl border border-slate-200 dark:border-[#2a2a2a] p-8 md:p-10 relative z-10 transition-all hover:border-yellow-400 dark:hover:border-[#d4af37]/50">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-yellow-50 dark:bg-gradient-to-br dark:from-[#222] dark:to-[#111] mb-5 border border-yellow-200 dark:border-[#333] shadow-inner">
                <span class="text-3xl">✂️</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Spoon's Barber Shop</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400 mt-2 font-bold uppercase tracking-widest">Crea tu nueva contraseña</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 dark:bg-rose-900/10 border border-rose-200 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 px-4 py-3 rounded-xl text-sm font-bold">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-gray-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">📧</span>
                    <input type="email" id="email" name="email" value="{{ old('email', request()->email) }}" required readonly
                        class="w-full bg-slate-100 dark:bg-[#0a0a0a] border border-slate-300 dark:border-[#222] text-slate-500 dark:text-gray-500 rounded-xl pl-11 pr-4 py-3.5 focus:outline-none transition-all cursor-not-allowed font-medium shadow-inner select-none"
                        title="El correo no se puede cambiar">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-gray-400 uppercase tracking-wider mb-2">Nueva Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔒</span>
                    <input type="password" id="password" name="password" required autofocus autocomplete="new-password"
                        class="w-full bg-white dark:bg-[#111] border border-slate-300 dark:border-[#333] text-slate-900 dark:text-white rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-yellow-400 dark:focus:ring-[#d4af37] focus:border-transparent transition-all font-medium placeholder-slate-400 dark:placeholder-gray-600"
                        placeholder="Mínimo 8 caracteres">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-gray-400 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔑</span>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-white dark:bg-[#111] border border-slate-300 dark:border-[#333] text-slate-900 dark:text-white rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-yellow-400 dark:focus:ring-[#d4af37] focus:border-transparent transition-all font-medium placeholder-slate-400 dark:placeholder-gray-600"
                        placeholder="Repite tu nueva contraseña">
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-yellow-400 to-yellow-500 dark:from-[#d4af37] dark:to-[#b8962e] hover:from-yellow-500 hover:to-yellow-600 dark:hover:from-[#e0c15a] dark:hover:to-[#c9a43b] text-slate-900 dark:text-black font-black px-6 py-4 rounded-xl transition-all shadow-md dark:shadow-[0_0_20px_rgba(212,175,55,0.3)] transform hover:-translate-y-1 flex justify-center items-center gap-2 mt-4">
                💾 Guardar y Entrar al Panel
            </button>
        </form>
    </div>
</body>
</html>