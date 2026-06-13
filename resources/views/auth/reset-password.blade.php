<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Spoon's Barber Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-gray-100 min-h-screen flex items-center justify-center p-4 transition-colors duration-300 relative overflow-hidden">
    
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-blue-400 dark:bg-[#3b82f6] opacity-[0.05] dark:opacity-[0.08] blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white dark:bg-zinc-800 rounded-3xl shadow-xl dark:shadow-2xl border border-slate-200 dark:border-zinc-700 p-8 md:p-10 relative z-10 transition-all hover:border-blue-400 dark:hover:border-[#3b82f6]/50">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 dark:bg-gradient-to-br dark:from-zinc-700 dark:to-zinc-800 mb-5 border border-blue-200 dark:border-zinc-600 shadow-inner">
                <span class="text-3xl text-[#3b82f6]">✂️</span>
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
                        class="w-full bg-slate-100 dark:bg-zinc-900 border border-slate-300 dark:border-zinc-700 text-slate-500 dark:text-gray-500 rounded-xl pl-11 pr-4 py-3.5 focus:outline-none transition-all cursor-not-allowed font-medium shadow-inner select-none"
                        title="El correo no se puede cambiar">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-gray-400 uppercase tracking-wider mb-2">Nueva Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔒</span>
                    <input type="password" id="password" name="password" required autofocus autocomplete="new-password"
                        class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-white rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-[#3b82f6] focus:border-transparent transition-all font-medium placeholder-slate-400 dark:placeholder-gray-500"
                        placeholder="Mínimo 8 caracteres">
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-gray-400 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔑</span>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-white rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-[#3b82f6] focus:border-transparent transition-all font-medium placeholder-slate-400 dark:placeholder-gray-500"
                        placeholder="Repite tu nueva contraseña">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 dark:bg-gradient-to-r dark:from-[#3b82f6] dark:to-[#2563eb] hover:bg-blue-700 dark:hover:from-[#60a5fa] dark:hover:to-[#3b82f6] text-white dark:text-white font-black px-6 py-4 rounded-xl transition-all shadow-md dark:shadow-[0_0_20px_rgba(59,130,246,0.3)] transform hover:-translate-y-1 flex justify-center items-center gap-2 mt-4">
                💾 Guardar y Entrar al Panel
            </button>
        </form>
    </div>
</body>
</html>