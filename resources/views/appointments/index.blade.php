@extends('layouts.app')

@section('content')

<div class="min-h-screen w-full bg-slate-50 dark:bg-[#050505] text-slate-900 dark:text-gray-100 p-4 md:p-10 font-sans transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8" data-aos="fade-down">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-slate-100 dark:bg-gradient-to-br dark:from-[#1a1a1a] dark:to-[#0a0a0a] rounded-2xl border border-slate-200 dark:border-[#222] flex items-center justify-center shadow-lg transition-colors">
                    <span class="text-3xl">📅</span>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-[#d4af37] tracking-tight">
                        Mis Citas
                    </h1>
                    <p class="text-slate-500 dark:text-gray-400 font-medium text-sm mt-1">Spoon's Barber Shop - Tu espacio personal</p>
                </div>
            </div>
            
            <a href="{{ url('/dashboard') }}" 
               class="w-full md:w-auto text-center bg-white dark:bg-[#111] border border-slate-200 dark:border-[#222] hover:bg-slate-100 dark:hover:bg-[#1a1a1a] text-slate-800 dark:text-white px-6 py-3 rounded-xl transition-all font-bold flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-[#003311] border border-emerald-200 dark:border-[#006622] text-emerald-700 dark:text-[#00ff55] px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-50 dark:bg-[#330000] border border-rose-200 dark:border-[#660000] text-rose-700 dark:text-[#ff4444] px-6 py-4 rounded-2xl mb-8 shadow-sm">
                <ul class="list-disc pl-5 font-bold text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gradient-to-b dark:from-[#111] dark:to-[#0a0a0a] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-[#222] shadow-sm dark:shadow-2xl relative overflow-hidden transition-colors duration-300">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-yellow-400 dark:via-[#d4af37] to-transparent opacity-50"></div>

            <div class="mb-6 border-b border-slate-100 dark:border-[#222] pb-4">
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">Reserva tu lugar</h3>
                <p id="texto-horario-dinamico" class="text-slate-500 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mt-1">
                    Selecciona una fecha en el calendario para ver el horario.
                </p>
            </div>

            @if($shopStatus->value === 'open')
                <form action="{{ route('appointments.store') }}" method="POST" id="form-agendar">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="group relative">
                            <label class="block text-slate-500 dark:text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-2 group-focus-within:text-yellow-600 dark:group-focus-within:text-[#d4af37] transition-colors">Fecha</label>
                            <input type="date" name="fecha" id="fecha-agendar" required 
                                   class="w-full bg-slate-50 dark:bg-[#050505] border border-slate-300 dark:border-[#333] text-slate-900 dark:text-white rounded-xl px-4 py-3.5 
                                          focus:bg-white focus:border-yellow-400 dark:focus:border-[#d4af37] focus:ring-1 focus:ring-yellow-400/50 dark:focus:ring-[#d4af37] outline-none transition-all dark:[color-scheme:dark]">
                            
                            <p id="mensaje-error-fecha" class="hidden text-[11px] mt-2 font-black text-red-500 dark:text-[#ff4444] drop-shadow-[0_0_5px_rgba(239,68,68,0.8)] transition-all duration-300"></p>
                        </div>

                        <div class="group relative">
                            <label class="block text-slate-500 dark:text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-2 group-focus-within:text-yellow-600 dark:group-focus-within:text-[#d4af37] transition-colors">Hora</label>
                            <input type="time" name="hora" id="hora-agendar" required 
                                   class="w-full bg-slate-50 dark:bg-[#050505] border border-slate-300 dark:border-[#333] text-slate-900 dark:text-white rounded-xl px-4 py-3.5 
                                          focus:bg-white focus:border-yellow-400 dark:focus:border-[#d4af37] focus:ring-1 focus:ring-yellow-400/50 dark:focus:ring-[#d4af37] outline-none transition-all dark:[color-scheme:dark]">
                            
                            <p id="mensaje-error-hora" class="hidden text-[11px] mt-2 font-black tracking-wide transition-all duration-300"></p>
                        </div>

                        <div class="group relative">
                            <label class="block text-slate-500 dark:text-gray-500 text-[10px] font-bold uppercase tracking-widest mb-2 group-focus-within:text-yellow-600 dark:group-focus-within:text-[#d4af37] transition-colors">Servicio</label>
                            <div class="relative">
                                <select name="servicio" id="servicio-select" required 
                                        class="w-full bg-slate-50 dark:bg-[#050505] border border-slate-300 dark:border-[#333] text-slate-900 dark:text-white rounded-xl px-4 py-3.5 
                                               focus:bg-white focus:border-yellow-400 dark:focus:border-[#d4af37] focus:ring-1 focus:ring-yellow-400/50 dark:focus:ring-[#d4af37] outline-none transition-all appearance-none cursor-pointer font-medium">
                                    <option value="" disabled selected>Selecciona un servicio...</option>
                                    @foreach($services as $servicio)
                                        <option value="{{ $servicio->nombre }}" data-duration="{{ $servicio->duracion_minutos }}">
                                            {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }} ({{ $servicio->duracion_minutos }} min)
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" id="btn-submit-cita" class="w-full md:w-auto bg-yellow-400 hover:bg-yellow-500 dark:bg-gradient-to-r dark:from-[#d4af37] dark:to-[#b8860b] dark:hover:from-[#ffd700] dark:hover:to-[#d4af37] text-slate-900 dark:text-black font-black text-sm uppercase tracking-widest px-10 py-4 rounded-xl shadow-lg dark:shadow-[0_0_15px_rgba(212,175,55,0.2)] transform hover:-translate-y-1 transition-all duration-300">
                            Confirmar Cita ✨
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-rose-50 dark:bg-[#2a1010] border-2 border-dashed border-rose-300 dark:border-red-900 rounded-2xl p-8 md:p-12 text-center my-6">
                    <div class="text-6xl md:text-7xl mb-6">🛑</div>
                    <h3 class="text-2xl md:text-3xl font-black text-rose-600 dark:text-red-500 mb-3 tracking-tight">Barbería Cerrada Temporalmente</h3>
                    <p class="text-slate-600 dark:text-gray-400 font-medium max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                        El barbero no se encuentra disponible en este momento o estamos fuera de servicio. Por favor, vuelve a intentarlo más tarde. Tus citas ya programadas siguen activas en tu historial y te atenderemos en el horario acordado.
                    </p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">
            @forelse($appointments as $cita)
                @php
                    $estado = strtolower($cita->estado);
                    
                    if($estado == 'pendiente') {
                        $colorCaja   = 'bg-amber-100 text-amber-700 dark:bg-[#332200] dark:text-[#ffb800]';
                        $colorBadge  = 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-[#332200] dark:text-[#ffb800] dark:border-[#664400]';
                        $textoEstado = 'PENDIENTE';
                    } elseif($estado == 'confirmada' || $estado == 'aceptada') {
                        $colorCaja   = 'bg-blue-100 text-blue-700 dark:bg-[#002233] dark:text-[#3399ff]';
                        $colorBadge  = 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-[#002233] dark:text-[#3399ff] dark:border-[#004466]';
                        $textoEstado = 'ACEPTADA';
                    } elseif($estado == 'completada') {
                        $colorCaja   = 'bg-emerald-100 text-emerald-700 dark:bg-[#003311] dark:text-[#00ff55]';
                        $colorBadge  = 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-[#003311] dark:text-[#00ff55] dark:border-[#006622]';
                        $textoEstado = 'COMPLETADA';
                    } else {
                        $colorCaja   = 'bg-rose-100 text-rose-700 dark:bg-[#330000] dark:text-[#ff4444]';
                        $colorBadge  = 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-[#330000] dark:text-[#ff4444] dark:border-[#660000]';
                        $textoEstado = 'CANCELADA';
                    }
                @endphp

                <div class="bg-white dark:bg-[#0f0f0f] rounded-2xl border border-slate-200 dark:border-[#222] p-6 flex flex-col justify-between shadow-sm hover:shadow-lg dark:hover:border-[#333] transition-all duration-300">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl {{ $colorCaja }} flex items-center justify-center text-2xl font-black shadow-inner">
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('d') }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-slate-500 dark:text-gray-500 font-bold uppercase tracking-widest mb-0.5">
                                    {{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('M Y') }}
                                </span>
                                <span class="text-xl text-slate-800 dark:text-white font-black tracking-tight">
                                    {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                </span>
                            </div>
                        </div>
                        
                        <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-md border {{ $colorBadge }}">
                            {{ $textoEstado }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <p class="text-[10px] text-slate-500 dark:text-gray-500 font-bold uppercase tracking-widest mb-2">Servicio Reservado</p>
                        <p class="text-base text-slate-900 dark:text-[#d4af37] font-bold flex items-center gap-2 truncate">
                            <span class="text-slate-400 dark:text-gray-500 font-normal">→</span> {{ $cita->servicio }}
                        </p>
                    </div>

                    <div class="pt-5 border-t border-slate-100 dark:border-[#1a1a1a]">
                        @if($estado == 'pendiente')
                            <div class="flex gap-3">
                                <a href="{{ route('appointments.edit', $cita->id) }}" 
                                   class="flex-1 bg-blue-50 dark:bg-[#101827] border border-blue-200 dark:border-[#1e3a8a]/50 hover:bg-blue-600 hover:text-white dark:hover:bg-[#1e3a8a]/40 text-blue-600 dark:text-[#60a5fa] text-sm font-bold py-3.5 rounded-xl text-center transition-colors">
                                    Editar Cita
                                </a>
                                
                                <form action="{{ route('appointments.update', $cita->id) }}" method="POST" class="m-0 form-accion-cita">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="estado" value="cancelada">
                                    <button type="submit" class="w-[52px] h-[52px] bg-rose-50 dark:bg-[#2a1010] border border-rose-200 dark:border-[#7f1d1d]/50 hover:bg-rose-600 hover:text-white dark:hover:bg-[#7f1d1d]/40 text-rose-600 dark:text-[#f87171] rounded-xl flex items-center justify-center transition-colors" data-accion="cancelar" title="Cancelar Cita">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="w-full bg-slate-50 dark:bg-[#111] text-slate-500 dark:text-gray-600 text-[10px] font-black py-4 rounded-xl text-center border border-slate-200 dark:border-[#222] uppercase tracking-widest flex justify-between items-center px-4">
                                <span>Cita {{ $textoEstado }}</span>
                                @if($estado == 'cancelada' || $estado == 'completada')
                                    <form action="{{ route('appointments.destroy', $cita->id) }}" method="POST" class="m-0 inline-block form-accion-cita">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 dark:text-red-500 dark:hover:text-red-400 transition-colors" data-accion="borrar" title="Eliminar Registro">
                                            🗑️ Borrar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 py-20">
                    <div class="bg-white dark:bg-[#0f0f0f] rounded-2xl border border-slate-200 dark:border-[#222] border-dashed p-12 text-center flex flex-col items-center justify-center transition-colors">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-[#1a1a1a] rounded-2xl flex items-center justify-center mb-6 border border-slate-100 dark:border-[#333]">
                            <svg class="w-10 h-10 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-gray-300 mb-2">Aún no tienes citas</h3>
                        <p class="text-slate-500 dark:text-gray-500 font-medium">Usa el formulario de arriba para agendar tu primer corte.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection

@section('scripts')

<script id="datos-horarios" type="application/json">
    {!! json_encode($horarios) !!}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputFecha = document.getElementById("fecha-agendar");
        const inputHora = document.getElementById("hora-agendar");
        const selectServicio = document.getElementById("servicio-select"); 
        const mensajeErrorHora = document.getElementById("mensaje-error-hora");
        const mensajeErrorFecha = document.getElementById("mensaje-error-fecha");
        const btnSubmit = document.getElementById("btn-submit-cita");
        const txtHorarioDinamico = document.getElementById("texto-horario-dinamico");

        // 🌟 AHORA LO LEEMOS DESDE EL HTML Y VS CODE NO SE QUEJA 🌟
        const horariosBD = JSON.parse(document.getElementById("datos-horarios").textContent);

        function getLocalTodayStr() {
            const date = new Date();
            const offset = date.getTimezoneOffset() * 60000;
            return (new Date(date - offset)).toISOString().split('T')[0];
        }

        if(inputFecha) {
            inputFecha.setAttribute("min", getLocalTodayStr()); 
            const nextYear = new Date();
            nextYear.setFullYear(nextYear.getFullYear() + 1);
            inputFecha.setAttribute("max", nextYear.toISOString().split("T")[0]); 
        }

        // 🧠 MAGIA: EL FORMULARIO DETECTA EL DÍA Y SE ADAPTA EN TIEMPO REAL
        function actualizarConfiguracionPorDia() {
            if (!inputFecha.value) return;

            const dateParts = inputFecha.value.split('-');
            const dateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
            const day = dateObj.getDay(); // 0 = Domingo, 6 = Sábado

            let confDia;
            let nombreDia = "";
            if (day === 0) { confDia = horariosBD.domingo; nombreDia = "Domingo"; }
            else if (day === 6) { confDia = horariosBD.sabado; nombreDia = "Sábado"; }
            else { confDia = horariosBD.semana; nombreDia = "Lunes a Viernes"; }

            if (confDia.cerrado) {
                txtHorarioDinamico.innerHTML = `🚫 ESTE DÍA LA BARBERÍA ESTÁ CERRADA`;
                txtHorarioDinamico.className = 'text-red-500 font-bold text-xs uppercase tracking-widest mt-1';
                inputHora.disabled = true;
                inputHora.value = '';
                mensajeErrorHora.innerHTML = '🚫 No puedes agendar, estamos cerrados este día.';
                mensajeErrorHora.classList.remove('hidden');
                btnSubmit.disabled = true;
            } else {
                txtHorarioDinamico.innerHTML = `Horario para ${nombreDia}: ${confDia.texto}`;
                txtHorarioDinamico.className = 'text-slate-500 dark:text-gray-500 text-xs font-bold uppercase tracking-widest mt-1';
                inputHora.disabled = false;
                inputHora.min = confDia.apertura;
                inputHora.max = confDia.cierre;
                
                if (inputHora.value) validateTimeAndDate();
            }
        }

        async function validateTimeAndDate() {
            if (inputHora.disabled) return;

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
                    mensajeErrorFecha.innerHTML = '🚫 Año inválido.';
                    mensajeErrorFecha.classList.remove('hidden');
                    hasError = true;
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

            mensajeErrorHora.innerHTML = '⏳ Verificando disponibilidad...';
            mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-blue-500 dark:text-[#3399ff] transition-all duration-300';
            mensajeErrorHora.classList.remove('hidden');
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');

            try {
                const response = await fetch("{{ route('api.validate.time') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({ 
                        date: fechaSeleccionada, 
                        time: horaSeleccionada, 
                        servicio: selectServicio.value 
                    })
                });

                if (!response.ok) throw new Error("Error del servidor");

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
                mensajeErrorHora.innerHTML = '❌ Ocurrió un error al verificar. Intenta de nuevo.';
                mensajeErrorHora.className = 'text-[11px] mt-2 font-black text-red-500 dark:text-[#ff4444] transition-all duration-300';
                btnSubmit.disabled = true;
            }
        }

        if(inputHora) inputHora.addEventListener('change', validateTimeAndDate);
        if(inputFecha) inputFecha.addEventListener('change', actualizarConfiguracionPorDia);
        if(selectServicio) selectServicio.addEventListener('change', validateTimeAndDate); 

        const formsAccion = document.querySelectorAll('.form-accion-cita');
        formsAccion.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                const btn = this.querySelector('button');
                const tipoAccion = btn.getAttribute('data-accion'); 
                const isDark = document.documentElement.classList.contains('dark');
                let swalConfig = {
                    icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', 
                    cancelButtonColor: isDark ? '#1a1a1a' : '#f1f5f9', background: isDark ? '#111111' : '#ffffff', 
                    color: isDark ? '#ffffff' : '#0f172a', iconColor: isDark ? '#d4af37' : '#ca8a04', 
                    customClass: { popup: isDark ? 'border border-[#333] rounded-2xl shadow-2xl' : 'border border-slate-200 rounded-2xl shadow-xl', cancelButton: isDark ? 'text-white' : 'text-slate-700 border border-slate-300' }
                };

                if(tipoAccion === 'borrar') {
                    swalConfig.title = '¿Borrar del historial?'; swalConfig.text = 'Esta cita se eliminará permanentemente.'; swalConfig.confirmButtonText = 'Sí, borrar'; swalConfig.cancelButtonText = 'Cancelar';
                } else {
                    swalConfig.title = '¿Cancelar Cita?'; swalConfig.text = 'Perderás tu lugar reservado en la agenda.'; swalConfig.confirmButtonText = 'Sí, cancelar cita'; swalConfig.cancelButtonText = 'Volver';
                }

                Swal.fire(swalConfig).then((result) => { if (result.isConfirmed) this.submit(); })
            });
        });
    });
</script>
@endsection