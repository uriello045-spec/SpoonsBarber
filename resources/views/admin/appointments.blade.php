@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-gray-100 p-6 md:p-10 font-sans transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8" data-aos="fade-down">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white dark:bg-gradient-to-br dark:from-[#1a1a1a] dark:to-[#0a0a0a] rounded-2xl border border-slate-200 dark:border-[#222] flex items-center justify-center shadow-sm dark:shadow-[0_0_15px_rgba(212,175,55,0.15)]">
                    <span class="text-3xl">📅</span>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-[#d4af37] tracking-tight dark:drop-shadow-md">
                        Agenda Activa
                    </h1>
                    <p class="text-slate-500 dark:text-gray-400 font-medium text-sm mt-1 uppercase tracking-widest">Panel de Trabajo - Spoon's Barber Shop</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                <div class="flex w-full p-1 bg-slate-200 dark:bg-[#1a1a1a] rounded-xl border border-slate-300 dark:border-[#333]">
                    <button id="btn-calendario" class="flex-1 px-6 py-2 rounded-lg text-sm font-bold transition-all bg-white dark:bg-[#333] shadow-sm text-slate-800 dark:text-white" onclick="cambiarVista('calendario')">
                        📅 Calendario
                    </button>
                    <button id="btn-tabla" class="flex-1 px-6 py-2 rounded-lg text-sm font-bold transition-all text-slate-500 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white bg-transparent" onclick="cambiarVista('tabla')">
                        📋 Lista
                    </button>
                </div>

                <a href="{{ route('admin.dashboard') }}" class="w-full md:w-auto text-center bg-white dark:bg-[#111] border border-slate-200 dark:border-[#333] hover:bg-slate-100 dark:hover:bg-[#1a1a1a] hover:border-yellow-600 dark:hover:border-[#d4af37] text-slate-800 dark:text-white px-6 py-2.5 rounded-xl transition-all font-bold flex items-center justify-center gap-2 shadow-sm dark:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/50 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 shadow-sm dark:shadow-[0_0_15px_rgba(16,185,129,0.1)]">
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

        <div class="mb-8 bg-gray-800/80 p-5 rounded-xl border border-gray-700 shadow-lg backdrop-blur-sm">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#d4af37] flex items-center gap-2">
                        ⚡ Registrar Corte Express
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">Registra a un cliente físico. Ocupará espacio en la agenda hasta que lo finalices.</p>
                </div>
                
                <form action="{{ route('admin.appointments.express') }}" method="POST" id="form-express" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    @csrf
                    <select name="servicio" required class="bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-[#d4af37] focus:border-[#d4af37] block w-full sm:w-80 p-2.5">
                        <option value="" disabled selected>Selecciona el servicio...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->nombre }}">{{ $service->nombre }} ({{ $service->duracion_minutos }} min) - ${{ number_format($service->precio, 2) }}</option>
                        @endforeach
                    </select>
                    
                    <button type="submit" id="btn-express" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        + Registrar Express
                    </button>
                </form>
            </div>
        </div>

        <div id="vista-calendario" class="bg-white dark:bg-[#111] p-4 md:p-6 rounded-3xl border border-slate-200 dark:border-[#222] shadow-xl transition-all relative z-10">
            <div id="calendar" class="text-slate-800 dark:text-gray-200 min-h-[600px]"></div>
        </div>

        <div id="vista-tabla" class="hidden bg-white dark:bg-[#111] rounded-2xl border border-slate-200 dark:border-[#222] shadow-sm dark:shadow-2xl overflow-hidden transition-all duration-300 relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-[#161616] border-b border-slate-200 dark:border-[#333]">
                        <tr>
                            <th class="p-5 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-[#d4af37]">Cliente</th>
                            <th class="p-5 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-[#d4af37]">Fecha / Hora</th>
                            <th class="p-5 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-[#d4af37]">Servicio</th>
                            <th class="p-5 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-[#d4af37]">Duración</th>
                            <th class="p-5 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-[#d4af37]">Estado</th>
                            <th class="p-5 text-xs font-black uppercase tracking-widest text-slate-500 dark:text-[#d4af37] text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#222]">
                        @forelse($appointments as $cita)
                            <tr class="hover:bg-slate-50 dark:hover:bg-[#181818] transition-colors">
                                <td class="p-5 font-bold text-slate-900 dark:text-white">{{ $cita->user->name ?? 'Cliente Físico' }}</td>
                                <td class="p-5 text-sm text-slate-600 dark:text-gray-300">
                                    {{ $cita->fecha }} <br>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $cita->hora }}</span>
                                </td>
                                <td class="p-5 text-sm text-slate-600 dark:text-gray-300">{{ $cita->servicio }}</td>
                                <td class="p-5 text-sm font-bold text-slate-500">
                                    {{ $cita->duracion_minutos ?? '45' }} min
                                </td>
                                <td class="p-5">
                                    <span class="px-4 py-1.5 rounded-md text-[10px] font-black uppercase tracking-widest border
                                        {{ $cita->estado == 'pendiente' ? 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-[#332200] dark:text-yellow-400 dark:border-[#664400]' : 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50' }}">
                                        {{ $cita->estado }}
                                    </span>
                                </td>
                                <td class="p-5 flex justify-center items-center gap-2">
                                    @if($cita->estado == 'pendiente')
                                        <form action="{{ route('appointments.update', $cita->id) }}" method="POST" class="m-0">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="estado" value="confirmada">
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-1" title="Aceptar cita">
                                                ☑️ Aceptar
                                            </button>
                                        </form>
                                        <form action="{{ route('appointments.update', $cita->id) }}" method="POST" class="m-0 form-accion-cita">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="estado" value="cancelada">
                                            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-1" data-accion="cancelar" title="Rechazar Cita">
                                                ❌ Cancelar
                                            </button>
                                        </form>
                                    @elseif($cita->estado == 'confirmada')
                                        <form action="{{ route('appointments.update', $cita->id) }}" method="POST" class="m-0">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="estado" value="completada">
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-1" title="Finalizar y Ocultar">
                                                🏁 Finalizar y Ocultar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('appointments.destroy', $cita->id) }}" method="POST" class="m-0 form-accion-cita">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-1" data-accion="borrar" title="Eliminar">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-5xl mb-4 opacity-30">✨</span>
                                        <p class="text-slate-500 dark:text-gray-500 text-xl font-bold">Agenda libre.</p>
                                        <p class="text-sm text-slate-400 dark:text-gray-600 mt-2">No tienes citas pendientes por atender.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

@php
    $eventosCalendario = [];
    foreach($appointments as $cita) {
        $duracion = $cita->duracion_minutos ?? 45; 
        
        $start = \Carbon\Carbon::parse($cita->fecha . ' ' . $cita->hora);
        $end = $start->copy()->addMinutes($duracion);
        
        $color = ($cita->estado == 'pendiente') ? '#eab308' : '#3b82f6';

        // 🛡️ BLINDAJE: Verificamos si el usuario existe antes de imprimir su nombre
        $nombreCliente = $cita->user ? $cita->user->name : 'Cliente Físico';

        $eventosCalendario[] = [
            'title' => $nombreCliente . ' - ' . $cita->servicio,
            'start' => $start->toIso8601String(),
            'end'   => $end->toIso8601String(),
            'color' => $color
        ];
    }
@endphp

<script id="eventos-data" type="application/json">
    {!! json_encode($eventosCalendario) !!}
</script>

<script>
    const formExpress = document.getElementById('form-express');
    const btnExpress = document.getElementById('btn-express');
    
    if(formExpress && btnExpress) {
        formExpress.addEventListener('submit', function() {
            btnExpress.disabled = true;
            btnExpress.classList.add('opacity-50', 'cursor-not-allowed');
            btnExpress.innerHTML = 'Bloqueando agenda... ⏳';
        });
    }

    function cambiarVista(vista) {
        const vistaCalendario = document.getElementById('vista-calendario');
        const vistaTabla = document.getElementById('vista-tabla');
        const btnCalendario = document.getElementById('btn-calendario');
        const btnTabla = document.getElementById('btn-tabla');

        const classActive = ['bg-white', 'dark:bg-[#333]', 'shadow-sm', 'text-slate-800', 'dark:text-white'];
        const classInactive = ['text-slate-500', 'dark:text-gray-400', 'hover:text-slate-800', 'dark:hover:text-white', 'bg-transparent'];

        if (vista === 'calendario') {
            vistaCalendario.classList.remove('hidden');
            vistaTabla.classList.add('hidden');
            
            btnCalendario.classList.add(...classActive);
            btnCalendario.classList.remove(...classInactive);
            
            btnTabla.classList.add(...classInactive);
            btnTabla.classList.remove(...classActive);
            
            window.dispatchEvent(new Event('resize'));
        } else {
            vistaCalendario.classList.add('hidden');
            vistaTabla.classList.remove('hidden');
            
            btnTabla.classList.add(...classActive);
            btnTabla.classList.remove(...classInactive);
            
            btnCalendario.classList.add(...classInactive);
            btnCalendario.classList.remove(...classActive);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        
        const eventosData = JSON.parse(document.getElementById("eventos-data").textContent);

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek', 
            locale: 'es', 
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00', 
            allDaySlot: false, 
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día'
            },
            slotLabelFormat: {
                hour: 'numeric', minute: '2-digit', meridiem: 'short'
            },
            eventTimeFormat: {
                hour: 'numeric', minute: '2-digit', meridiem: 'short'
            },
            events: eventosData 
        });
        calendar.render();

        const formsAccion = document.querySelectorAll('.form-accion-cita');
        formsAccion.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); 
                const btn = this.querySelector('button');
                const tipoAccion = btn.getAttribute('data-accion'); 
                const isDark = document.documentElement.classList.contains('dark');
                
                let swalConfig = {
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626', 
                    cancelButtonColor: isDark ? '#1a1a1a' : '#f1f5f9', 
                    background: isDark ? '#111111' : '#ffffff', 
                    color: isDark ? '#ffffff' : '#0f172a', 
                    iconColor: isDark ? '#e11d48' : '#e11d48', 
                    customClass: {
                        popup: isDark ? 'border border-[#333] rounded-2xl shadow-2xl' : 'border border-slate-200 rounded-2xl shadow-xl',
                        cancelButton: isDark ? 'text-white' : 'text-slate-700 border border-slate-300'
                    }
                };

                if(tipoAccion === 'borrar') {
                    swalConfig.title = '¿El cliente no llegó?';
                    swalConfig.text = 'Esta cita será eliminada permanentemente de la agenda.';
                    swalConfig.confirmButtonText = 'Sí, eliminar cita';
                    swalConfig.cancelButtonText = 'Volver';
                } else {
                    swalConfig.title = '¿Rechazar cita?';
                    swalConfig.text = 'El cliente será notificado de que la cita fue cancelada.';
                    swalConfig.confirmButtonText = 'Sí, rechazar';
                    swalConfig.cancelButtonText = 'Volver';
                }

                Swal.fire(swalConfig).then((result) => {
                    if (result.isConfirmed) this.submit();
                })
            });
        });
    });
</script>

<style>
    .fc-theme-standard td, .fc-theme-standard th { border-color: #e2e8f0; }
    .dark .fc-theme-standard td, .dark .fc-theme-standard th { border-color: #222; }
    .dark .fc-col-header-cell-cushion, .dark .fc-timegrid-slot-label-cushion { color: #ccc; }
    .fc-event { border: none; border-radius: 6px; padding: 3px 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); font-weight: 600;}
    .fc-toolbar-title { font-weight: 900 !important; font-size: 1.5rem !important; color: #d4af37; text-transform: capitalize; }
    .fc-button-primary { background-color: #f1f5f9 !important; border-color: #cbd5e1 !important; color: #334155 !important; font-weight: bold !important; text-transform: capitalize; transition: all 0.3s;}
    .dark .fc-button-primary { background-color: #1a1a1a !important; border-color: #333 !important; color: white !important;}
    .fc-button-primary:hover { background-color: #d4af37 !important; border-color: #d4af37 !important; color: black !important;}
    .fc-button-active { background-color: #d4af37 !important; border-color: #d4af37 !important; color: black !important; }
</style>
@endsection