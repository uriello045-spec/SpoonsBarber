@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-gray-100 p-6 md:p-10 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-64 bg-yellow-400 dark:bg-[#d4af37] opacity-[0.02] dark:opacity-[0.03] blur-[100px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto space-y-8 relative z-10">
        
        <div class="mb-2">
            <a href="{{ route('admin.barbers.index') }}" class="text-slate-500 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-[#d4af37] transition-colors font-bold flex items-center gap-2 w-max">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver a Barberos
            </a>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="text-yellow-600 dark:text-[#d4af37]">✏️</span> Editar Barbero
                </h2>
                <p class="text-slate-500 dark:text-gray-400 text-sm mt-1 uppercase tracking-widest font-medium">Modifica los datos de {{ $barber->name }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#111] border border-slate-200 dark:border-[#222] rounded-3xl p-8 shadow-sm dark:shadow-2xl relative overflow-hidden">
            <form action="{{ route('admin.barbers.update', $barber->id) }}" method="POST" class="space-y-6" id="form-editar-barbero">
                @csrf 
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-2">Nombre Completo</label>
                        <input type="text" name="name" id="input-nombre" value="{{ old('name', $barber->name) }}" required
                            class="w-full bg-slate-50 dark:bg-[#1a1a1a] text-slate-900 dark:text-white border border-slate-200 dark:border-[#333] rounded-xl p-3.5 focus:border-yellow-400 dark:focus:border-[#d4af37] focus:ring-1 focus:ring-yellow-400 dark:focus:ring-[#d4af37] transition-all outline-none">
                        
                        <span id="error-nombre" class="hidden text-rose-500 dark:text-red-500 text-xs font-bold mt-1">Solo se permiten letras y espacios.</span>
                        @error('name') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-2">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $barber->email) }}" required
                            class="w-full bg-slate-50 dark:bg-[#1a1a1a] text-slate-900 dark:text-white border border-slate-200 dark:border-[#333] rounded-xl p-3.5 focus:border-yellow-400 dark:focus:border-[#d4af37] focus:ring-1 focus:ring-yellow-400 dark:focus:ring-[#d4af37] transition-all outline-none">
                        @error('email') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-2">Teléfono <span class="text-slate-400 dark:text-gray-600">(Opcional, 10 dígitos)</span></label>
                        <input type="text" name="phone" id="input-telefono" value="{{ old('phone', $barber->phone) }}" maxlength="10"
                            class="w-full bg-slate-50 dark:bg-[#1a1a1a] text-slate-900 dark:text-white border border-slate-200 dark:border-[#333] rounded-xl p-3.5 focus:border-yellow-400 dark:focus:border-[#d4af37] focus:ring-1 focus:ring-yellow-400 dark:focus:ring-[#d4af37] transition-all outline-none">
                        
                        <span id="error-telefono" class="hidden text-rose-500 dark:text-red-500 text-xs font-bold mt-1">Solo números, máximo 10 dígitos.</span>
                        @error('phone') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-2">Nueva Contraseña</label>
                        <input type="password" name="password" id="input-password" minlength="8" maxlength="20" placeholder="Déjalo en blanco para no cambiarla"
                            class="w-full bg-slate-50 dark:bg-[#1a1a1a] text-slate-900 dark:text-white border border-slate-200 dark:border-[#333] rounded-xl p-3.5 focus:border-yellow-400 dark:focus:border-[#d4af37] focus:ring-1 focus:ring-yellow-400 dark:focus:ring-[#d4af37] transition-all outline-none placeholder-slate-400 dark:placeholder-gray-600">
                        
                        <div id="meter-container" class="hidden mt-2">
                            <div class="h-1.5 w-full bg-slate-200 dark:bg-zinc-800 rounded-full overflow-hidden">
                                <div id="meter-fill-edit" class="h-full bg-red-500 w-0 transition-all duration-300"></div>
                            </div>
                            <p id="password-feedback-edit" class="text-[11px] mt-1.5 font-semibold text-slate-500 dark:text-gray-400">Mín. 8, Máx. 20 caracteres.</p>
                        </div>

                        @error('password') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-[#222] pt-6 mt-8 flex flex-col md:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.barbers.index') }}" class="text-center px-6 py-3.5 rounded-xl bg-slate-100 dark:bg-transparent border border-slate-200 dark:border-[#333] text-slate-600 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-[#1a1a1a] hover:text-slate-900 dark:hover:text-white font-bold transition-all">
                        Cancelar
                    </a>
                    <button type="submit" id="btn-submit" class="text-center bg-gradient-to-r from-yellow-400 to-yellow-500 dark:from-[#d4af37] dark:to-[#b8962e] hover:from-yellow-500 hover:to-yellow-600 dark:hover:from-[#e0c15a] dark:hover:to-[#c9a43b] text-slate-900 dark:text-black font-black px-8 py-3.5 rounded-xl transition-all shadow-md dark:shadow-[0_0_15px_rgba(212,175,55,0.4)]">
                        💾 Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputNombre = document.getElementById('input-nombre');
        const inputTelefono = document.getElementById('input-telefono');
        const inputPassword = document.getElementById('input-password');
        const errorNombre = document.getElementById('error-nombre');
        const errorTelefono = document.getElementById('error-telefono');
        const btnSubmit = document.getElementById('btn-submit');
        
        // Elementos del Medidor de Contraseña
        const meterContainer = document.getElementById('meter-container');
        const meterFill = document.getElementById('meter-fill-edit');
        const feedbackText = document.getElementById('password-feedback-edit');

        // Regex
        const regexLetras = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        const regexNumeros = /^[0-9]*$/;

        function validarFormulario() {
            let isValid = true;

            // Validar Nombre en tiempo real
            if (inputNombre.value.length > 0 && !regexLetras.test(inputNombre.value)) {
                errorNombre.classList.remove('hidden');
                inputNombre.classList.add('border-rose-500', 'ring-rose-500');
                inputNombre.classList.remove('border-slate-200', 'dark:border-[#333]');
                isValid = false;
            } else {
                errorNombre.classList.add('hidden');
                inputNombre.classList.remove('border-rose-500', 'ring-rose-500');
                inputNombre.classList.add('border-slate-200', 'dark:border-[#333]');
            }

            // Validar Teléfono en tiempo real (si está lleno)
            if (inputTelefono.value.length > 0) {
                if (!regexNumeros.test(inputTelefono.value) || inputTelefono.value.length !== 10) {
                    errorTelefono.classList.remove('hidden');
                    inputTelefono.classList.add('border-rose-500', 'ring-rose-500');
                    inputTelefono.classList.remove('border-slate-200', 'dark:border-[#333]');
                    isValid = false;
                } else {
                    errorTelefono.classList.add('hidden');
                    inputTelefono.classList.remove('border-rose-500', 'ring-rose-500');
                    inputTelefono.classList.add('border-slate-200', 'dark:border-[#333]');
                }
            } else {
                errorTelefono.classList.add('hidden');
                inputTelefono.classList.remove('border-rose-500', 'ring-rose-500');
                inputTelefono.classList.add('border-slate-200', 'dark:border-[#333]');
            }

            // 🔒 Validar Contraseña (Solo si el usuario intenta cambiarla)
            if (inputPassword.value.length > 0 && inputPassword.value.length < 8) {
                isValid = false; // Bloquea el botón si la empezó a teclear pero está muy corta
            }

            // Bloquear botón si hay errores
            btnSubmit.disabled = !isValid;
            if(!isValid){
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        // Eventos inputs Nombre y Teléfono
        inputNombre.addEventListener('input', function() {
            this.value = this.value.replace(/[0-9]/g, ''); 
            validarFormulario();
        });

        inputTelefono.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, ''); 
            validarFormulario();
        });

        // 🛡️ Evento input Contraseña (Evalúa fuerza y valida formulario)
        inputPassword.addEventListener('input', function() {
            validarFormulario(); // Bloquea el botón si es menor a 8

            const val = this.value;
            
            // Si el campo está vacío, escondemos la barra para que no ensucie el diseño
            if (val.length === 0) {
                meterContainer.classList.add('hidden');
                return;
            } else {
                meterContainer.classList.remove('hidden');
            }

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
                    colorClass = "bg-yellow-400";
                    widthClass = "w-1/2";
                } else if (strength >= 3) {
                    message = "Fuerza: Alta (Segura 🔒)";
                    colorClass = "bg-green-500";
                    widthClass = "w-full";
                }
            } else {
                message = "Fuerza: Baja (Muy corta)";
                colorClass = "bg-red-500";
                widthClass = "w-1/4";
            }

            meterFill.className = `h-full transition-all duration-300 ${colorClass} ${widthClass}`;
            feedbackText.innerText = message;
            
            // Color del texto de retroalimentación
            if(strength >= 3) {
                feedbackText.className = "text-[11px] mt-1.5 font-bold text-green-600 dark:text-green-400";
            } else if (strength === 1 || strength === 2) {
                feedbackText.className = "text-[11px] mt-1.5 font-bold text-yellow-600 dark:text-yellow-400";
            } else {
                feedbackText.className = "text-[11px] mt-1.5 font-semibold text-slate-500 dark:text-gray-400";
            }
        });

    });
</script>
@endsection