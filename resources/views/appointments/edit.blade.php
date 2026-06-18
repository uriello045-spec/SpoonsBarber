@extends('layouts.app')

@section('content')

@php
    $estaBloqueada = in_array(strtolower($appointment->estado), ['aceptada', 'confirmada', 'completada', 'cancelada']);
@endphp

<div class="min-h-screen w-full bg-slate-50 dark:bg-[#222222] flex items-center justify-center p-4 font-sans transition-colors duration-300">
    
    <div class="w-full max-w-lg" data-aos="fade-up">
        
        <div class="bg-white dark:bg-[#2a2a2a] rounded-3xl shadow-xl dark:shadow-2xl border border-slate-200 dark:border-zinc-700 overflow-hidden relative transition-colors duration-300">
            
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 dark:via-[#3b82f6] to-transparent opacity-80"></div>

            <div class="bg-slate-50 dark:bg-[#222222] px-8 py-8 text-center border-b border-slate-100 dark:border-zinc-700 transition-colors duration-300">
                <h2 class="text-3xl font-black text-slate-900 dark:text-[#3b82f6] tracking-tight">
                    Editar Cita
                </h2>
                <p class="text-slate-500 dark:text-gray-400 text-[10px] mt-2 font-bold tracking-widest uppercase">Modificando detalles del servicio</p>
            </div>

            <div class="p-8">
                
                @if($estaBloqueada)
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-xl p-4 mb-6 text-center shadow-sm">
                        <p class="text-amber-700 dark:text-amber-500 font-bold text-sm flex items-center justify-center gap-2">
                            <span>⚠️</span> Esta cita ya fue procesada y no puede ser modificada.
                        </p>
                    </div>
                @endif

                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" id="form-editar-cita">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        
                        <div class="group relative">
                            <label class="block text-slate-500 dark:text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2 group-focus-within:text-blue-600 dark:group-focus-within:text-[#3b82f6] transition-colors">
                                Fecha de la cita
                            </label>
                            <input type="date" name="fecha" id="fecha" value="{{ \Carbon\Carbon::parse($appointment->fecha)->format('Y-m-d') }}" required 
                                   {{ $estaBloqueada ? 'disabled' : '' }}
                                   class="w-full bg-slate-50 dark:bg-[#222222] border border-slate-300 dark:border-zinc-600 text-slate-900 dark:text-white rounded-xl px-5 py-4 
                                          focus:bg-white dark:focus:bg-[#2a2a2a] focus:border-blue-500 dark:focus:border-[#3b82f6] focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-[#3b82f6]/20 outline-none transition-all dark:[color-scheme:dark] font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <p id="mensaje-error-fecha" class="hidden text-[11px] mt-2 font-black text-red-500 dark:text-[#ff4444] drop-shadow-[0_0_5px_rgba(239,68,68,0.8)] transition-all duration-300"></p>
                        </div>

                        <div class="group relative">
                            <label class="block text-slate-500 dark:text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2 group-focus-within:text-blue-600 dark:group-focus-within:text-[#3b82f6] transition-colors">
                                Hora
                            </label>
                            <input type="time" name="hora" id="hora" min="08:00" max="21:00" value="{{ \Carbon\Carbon::parse($appointment->hora)->format('H:i') }}" required 
                                   {{ $estaBloqueada ? 'disabled' : '' }}
                                   class="w-full bg-slate-50 dark:bg-[#222222] border border-slate-300 dark:border-zinc-600 text-slate-900 dark:text-white rounded-xl px-5 py-4 
                                          focus:bg-white dark:focus:bg-[#2a2a2a] focus:border-blue-500 dark:focus:border-[#3b82f6] focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-[#3b82f6]/20 outline-none transition-all dark:[color-scheme:dark] font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <p id="mensaje-error-hora" class="hidden text-[11px] mt-2 font-black tracking-wide transition-all duration-300"></p>
                        </div>

                        <div class="group relative">
                            <label class="block text-slate-500 dark:text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2 group-focus-within:text-blue-600 dark:group-focus-within:text-[#3b82f6] transition-colors">
                                Servicio Reservado
                            </label>
                            <div class="relative">
                                <select name="servicio" required {{ $estaBloqueada ? 'disabled' : '' }}
                                        class="w-full bg-slate-50 dark:bg-[#222222] border border-slate-300 dark:border-zinc-600 text-slate-900 dark:text-white rounded-xl px-5 py-4 
                                               focus:bg-white dark:focus:bg-[#2a2a2a] focus:border-blue-500 dark:focus:border-[#3b82f6] focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-[#3b82f6]/20 outline-none transition-all appearance-none cursor-pointer font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                    <option value="" disabled>Selecciona un servicio...</option>
                                    @foreach($services as $servicio)
                                        <option value="{{ $servicio->nombre }}" {{ $appointment->servicio == $servicio->nombre ? 'selected' : '' }}>
                                            {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex flex-col-reverse md:flex-row gap-4 mt-10">
                        <a href="{{ route('appointments.index') }}" 
                           class="flex-1 text-center bg-white dark:bg-[#2a2a2a] hover:bg-slate-100 dark:hover:bg-zinc-700 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white font-bold px-6 py-4 rounded-xl transition-all border border-slate-300 dark:border-zinc-600 hover:border-slate-400 dark:hover:border-zinc-500">
                            {{ $estaBloqueada ? 'Volver a Mis Citas' : 'Cancelar' }}
                        </a>

                        @if(!$estaBloqueada)
                            <button type="submit" id="btn-submit-cita"
                                    class="flex-1 bg-blue-600 dark:bg-gradient-to-r dark:from-[#3b82f6] dark:to-[#2563eb] hover:bg-blue-700 dark:hover:from-[#60a5fa] dark:hover:to-[#3b82f6] text-white dark:text-white font-black px-6 py-4 rounded-xl shadow-lg dark:shadow-[0_0_15px_rgba(59,130,246,0.2)] hover:shadow-xl dark:hover:shadow-[0_0_20px_rgba(59,130,246,0.4)] transform hover:-translate-y-1 transition-all duration-300">
                                Guardar Cambios
                            </button>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputFecha = document.getElementById("fecha");
        const inputHora = document.getElementById("hora");
        const mensajeErrorHora = document.getElementById("mensaje-error-hora");
        const mensajeErrorFecha = document.getElementById("mensaje-error-fecha");
        const btnSubmit = document.getElementById("btn-submit-cita");
        const formEditar = document.getElementById("form-editar-cita");

        const originalFecha = "{{ \Carbon\Carbon::parse($appointment->fecha)->format('Y-m-d') }}";
        const originalHora = "{{ \Carbon\Carbon::parse($appointment->hora)->format('H:i') }}";

        if(inputFecha && !inputFecha.disabled) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            inputFecha.setAttribute("min", now.toISOString().split("T")[0]);
            
            const nextYear = new Date();
            nextYear.setFullYear(nextYear.getFullYear() + 1);
            inputFecha.setAttribute("max", nextYear.toISOString().split("T")[0]); 
        }

        async function validateTimeAndDate() {
            if(inputFecha.disabled) return; 

            const fechaSeleccionada = inputFecha.value;
            const horaSeleccionada = inputHora.value;
            let hasError = false;

            mensajeErrorFecha.classList.add('hidden');
            mensajeErrorHora.classList.add('hidden');
            mensajeErrorHora.className = 'hidden text-[11px] mt-2 font-black tracking-wide transition-all duration-300';

            if (fechaSeleccionada) {
                const anioSeleccionado = parseInt(fechaSeleccionada.split('-')[0]);
                const anioActual = new Date().getFullYear();
                
                if (anioSeleccionado > anioActual + 1 || anioSeleccionado < anioActual) {
                    mensajeErrorFecha.innerHTML = '🚫 Año inválido. Solo puedes agendar para este año o el próximo.';
                    mensajeErrorFecha.classList.remove('hidden');
                    hasError = true;
                }
            }

            if (horaSeleccionada) {
                if (horaSeleccionada < "08:00" || horaSeleccionada > "21:00") {
                    mensajeErrorHora.innerHTML = '🚫 Abrimos de 08:00 AM a 09:00 PM. Elige un horario válido.';
                    mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-red-500 dark:text-[#ff4444] drop-shadow-[0_0_5px_rgba(239,68,68,0.8)] transition-all duration-300';
                    mensajeErrorHora.classList.remove('hidden');
                    hasError = true;
                }
            }

            if (fechaSeleccionada && horaSeleccionada) {
                const hoyDate = new Date();
                const strHoy = hoyDate.getFullYear() + "-" + String(hoyDate.getMonth() + 1).padStart(2, '0') + "-" + String(hoyDate.getDate()).padStart(2, '0');
                
                if (fechaSeleccionada === strHoy) {
                    const horaActualStr = String(hoyDate.getHours()).padStart(2, '0') + ":" + String(hoyDate.getMinutes()).padStart(2, '0');
                    
                    if (horaSeleccionada < horaActualStr && !(fechaSeleccionada === originalFecha && horaSeleccionada === originalHora)) {
                        mensajeErrorHora.innerHTML = '🚫 Esta hora ya pasó. Por favor elige un horario futuro para hoy.';
                        mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-red-500 dark:text-[#ff4444] drop-shadow-[0_0_5px_rgba(239,68,68,0.8)] transition-all duration-300';
                        mensajeErrorHora.classList.remove('hidden');
                        hasError = true;
                    }
                }
            }

            if (hasError) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                return; 
            }

            if (!fechaSeleccionada || !horaSeleccionada) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            if (fechaSeleccionada === originalFecha && horaSeleccionada === originalHora) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                return; 
            }

            mensajeErrorHora.innerHTML = '⏳ Verificando disponibilidad...';
            mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-blue-500 dark:text-[#3b82f6] transition-all duration-300';
            mensajeErrorHora.classList.remove('hidden');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                const response = await fetch("{{ route('api.validate.time') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ date: fechaSeleccionada, time: horaSeleccionada })
                });

                const data = await response.json();

                if (data.valid) {
                    mensajeErrorHora.innerHTML = data.message;
                    mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-emerald-600 dark:text-[#00ff55] drop-shadow-[0_0_5px_rgba(16,185,129,0.5)] transition-all duration-300';
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    mensajeErrorHora.innerHTML = data.message;
                    mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-red-500 dark:text-[#ff4444] drop-shadow-[0_0_5px_rgba(239,68,68,0.8)] transition-all duration-300';
                    btnSubmit.disabled = true;
                }
            } catch (error) {
                mensajeErrorHora.innerHTML = '❌ Error de conexión al verificar el horario.';
                mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-red-500 dark:text-[#ff4444] transition-all duration-300';
                btnSubmit.disabled = true;
            }
        }

        if(inputHora && !inputHora.disabled) inputHora.addEventListener('change', validateTimeAndDate);
        if(inputFecha && !inputFecha.disabled) inputFecha.addEventListener('change', validateTimeAndDate);

        if(formEditar && btnSubmit) {
            formEditar.addEventListener('submit', function(e) {
                e.preventDefault();
                const isDark = document.documentElement.classList.contains('dark');

                Swal.fire({
                    title: '¿Guardar cambios?',
                    text: "Se actualizarán los detalles de tu cita.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6', 
                    cancelButtonColor: isDark ? '#2a2a2a' : '#f1f5f9', 
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar',
                    background: isDark ? '#222222' : '#ffffff', 
                    color: isDark ? '#ffffff' : '#0f172a', 
                    iconColor: isDark ? '#3b82f6' : '#2563eb', 
                    customClass: {
                        popup: isDark ? 'border border-zinc-700 rounded-2xl shadow-2xl' : 'border border-slate-200 rounded-2xl shadow-xl',
                        cancelButton: isDark ? 'text-white' : 'text-slate-700 border border-slate-300',
                        confirmButton: 'text-white font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        btnSubmit.innerHTML = 'Guardando... ⏳';
                        this.submit();
                    }
                });
            });
        }
    });
</script>
@endsection