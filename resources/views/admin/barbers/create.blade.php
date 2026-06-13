@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 bg-slate-50 dark:bg-zinc-900 transition-colors duration-300 flex items-center justify-center p-4">
    
    <div class="w-full max-w-2xl p-8 bg-white dark:bg-zinc-800 rounded-3xl border border-slate-200 dark:border-zinc-700 shadow-sm dark:shadow-xl transition-all duration-300 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 dark:via-[#3b82f6] to-transparent opacity-80"></div>

        <div class="text-center mb-8" data-aos="fade-down">
            <h1 class="text-3xl font-black text-slate-900 dark:text-[#3b82f6] tracking-wide flex items-center justify-center gap-2">
                <span>✂️</span> Registrar Nuevo Barbero
            </h1>
            <p class="text-slate-500 dark:text-gray-400 mt-2 font-medium">Crea una cuenta de acceso para el personal.</p>
        </div>

        <form method="POST" action="{{ route('admin.barbers.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-6 mb-6">
                
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400 mb-2">Nombre Completo</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" maxlength="50"
                        oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')"
                        class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-600 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-800 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-[#3b82f6]/50 focus:border-blue-500 dark:focus:border-[#3b82f6] outline-none transition font-medium
                        @error('name') border-red-500 focus:ring-red-500 @enderror"
                        placeholder="Ej. Ricardo Montaner" required>
                    @error('name') <p class="text-red-500 text-xs mt-1 font-semibold">* {{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" maxlength="80"
                        class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-600 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-800 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-[#3b82f6]/50 focus:border-blue-500 dark:focus:border-[#3b82f6] outline-none transition font-medium
                        @error('email') border-red-500 @enderror"
                        placeholder="barbero@spoons.com" required>
                    @error('email') <p class="text-red-500 text-xs mt-1 font-semibold">* {{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400 mb-2">Teléfono <span class="text-[10px] font-normal lowercase">(10 dígitos)</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                        class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-600 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-800 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-[#3b82f6]/50 focus:border-blue-500 dark:focus:border-[#3b82f6] outline-none transition font-medium
                        @error('phone') border-red-500 @enderror"
                        placeholder="Ej. 7221234567">
                    @error('phone') <p class="text-red-500 text-xs mt-1 font-semibold">* {{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400 mb-2">Contraseña</label>
                        <input type="password" name="password" id="password_barber" required minlength="8" maxlength="20"
                            class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-600 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-800 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-[#3b82f6]/50 focus:border-blue-500 dark:focus:border-[#3b82f6] outline-none transition
                            @error('password') border-red-500 @enderror">
                        
                        <div class="h-1.5 w-full bg-slate-200 dark:bg-zinc-700 rounded-full mt-2 overflow-hidden">
                            <div id="meter-fill-barber" class="h-full bg-red-500 w-0 transition-all duration-300"></div>
                        </div>
                        <p id="password-feedback-barber" class="text-[11px] mt-1.5 text-slate-500 dark:text-gray-400 font-semibold">Mín. 8, Máx. 20 caracteres.</p>

                        @error('password') <p class="text-red-500 text-xs mt-1 font-semibold">* {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400 mb-2">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" maxlength="20"
                            class="w-full p-3.5 rounded-xl bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-600 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-zinc-800 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-[#3b82f6]/50 focus:border-blue-500 dark:focus:border-[#3b82f6] outline-none transition">
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse md:flex-row items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-zinc-700">
                <a href="{{ route('admin.barbers.index') }}" class="w-full md:w-auto text-center bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-600 hover:bg-slate-200 dark:hover:bg-zinc-700 text-slate-700 dark:text-gray-300 font-bold px-6 py-3.5 rounded-xl transition-all">
                    Cancelar
                </a>
                <button type="submit" class="w-full md:w-auto bg-blue-600 dark:bg-[#3b82f6] hover:bg-blue-700 dark:hover:bg-[#60a5fa] text-white dark:text-white font-black px-8 py-3.5 rounded-xl shadow-sm dark:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    Crear Barbero
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const passInputBarber = document.getElementById('password_barber');
    const meterFillBarber = document.getElementById('meter-fill-barber');
    const feedbackTextBarber = document.getElementById('password-feedback-barber');

    passInputBarber.addEventListener('input', function() {
        const val = passInputBarber.value;
        let strength = 0;
        let message = "Mín. 8, Máx. 20 caracteres.";
        let colorClass = "bg-red-500";
        let widthClass = "w-0";

        if (val.length >= 8) {
            strength += 1;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) strength += 1;
            if (/[0-9]/.test(val)) strength += 1;
            if (/[^A-Za-z0-9]/.test(val)) strength += 1;

            if (strength === 1 || strength === 2) {
                message = "Fuerza: Media (Añade letras, números o símbolos)";
                colorClass = "bg-blue-400";
                widthClass = "w-1/2";
            } else if (strength >= 3) {
                message = "Fuerza: Alta (Segura 🔒)";
                colorClass = "bg-green-500";
                widthClass = "w-full";
            }
        } else if (val.length > 0) {
            message = "Fuerza: Baja (Muy corta)";
            colorClass = "bg-red-500";
            widthClass = "w-1/4";
        }

        meterFillBarber.className = `h-full transition-all duration-300 ${colorClass} ${widthClass}`;
        feedbackTextBarber.innerText = message;
        
        if(strength >= 3) {
            feedbackTextBarber.className = "text-[11px] mt-1.5 font-bold text-green-600 dark:text-green-400";
        } else if (strength === 1 || strength === 2) {
            feedbackTextBarber.className = "text-[11px] mt-1.5 font-bold text-blue-600 dark:text-blue-400";
        } else {
            feedbackTextBarber.className = "text-[11px] mt-1.5 font-semibold text-slate-500 dark:text-gray-400";
        }
    });
</script>
@endsection