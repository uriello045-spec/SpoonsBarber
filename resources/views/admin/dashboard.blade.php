@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 dark:bg-[#222222] text-slate-900 dark:text-gray-100 p-4 md:p-10 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-blue-400 dark:bg-[#3b82f6] opacity-[0.02] dark:opacity-[0.05] blur-[120px] pointer-events-none rounded-full"></div>

    <div class="max-w-7xl mx-auto space-y-8 relative z-10">

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6" data-aos="fade-down">
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="text-blue-600 dark:text-[#3b82f6]">💈</span> Panel de Control
                </h2>
                <p class="text-slate-500 dark:text-gray-400 font-medium mt-2 uppercase tracking-widest text-sm">Resumen general de Spoon’s Barber Shop</p>
            </div>

            <div class="flex flex-wrap gap-3 w-full lg:w-auto items-center">
                <form action="{{ route('admin.toggleShop') }}" method="POST" class="w-full lg:w-auto">
                    @csrf
                    @if($shopStatus->value == 'open')
                        <button type="submit" class="w-full text-center bg-rose-500 hover:bg-rose-600 text-white font-bold py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md">
                            <span>🛑</span> Cerrar Barbería
                        </button>
                    @else
                        <button type="submit" class="w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 transition-all shadow-[0_0_15px_rgba(16,185,129,0.5)] animate-pulse">
                            <span>✅</span> Abrir Barbería
                        </button>
                    @endif
                </form>

                <a href="{{ route('admin.statistics') }}" class="flex-1 lg:flex-none text-center bg-white dark:bg-[#2a2a2a] border border-slate-200 dark:border-zinc-700 hover:bg-slate-100 dark:hover:bg-zinc-700 hover:border-blue-600 dark:hover:border-[#3b82f6]/50 text-slate-800 dark:text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-sm dark:shadow-md flex items-center justify-center gap-2">
                    📊 Estadísticas
                </a>
                <a href="{{ route('admin.appointments') }}" class="flex-1 lg:flex-none text-center bg-slate-100 dark:bg-[#222222] border border-slate-300 dark:border-[#3b82f6]/30 hover:bg-slate-200 dark:hover:bg-zinc-700 hover:border-blue-600 dark:hover:border-[#3b82f6] text-slate-900 dark:text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-sm dark:shadow-md flex items-center justify-center gap-2">
                    📅 Citas
                </a>
                <a href="{{ route('admin.barbers.index') }}" class="flex-1 lg:flex-none text-center bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-800 hover:from-blue-400 hover:to-blue-500 dark:hover:from-blue-500 dark:hover:to-blue-700 text-white dark:text-white font-black px-6 py-3.5 rounded-xl transition-all shadow-md dark:shadow-[0_0_15px_rgba(59,130,246,0.4)] flex items-center justify-center gap-2">
                    ✂️ Equipo
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/50 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl mb-8 font-bold flex items-center gap-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10" data-aos="fade-up">
            <div class="bg-white dark:bg-[#2a2a2a] p-8 rounded-3xl border border-slate-200 dark:border-zinc-700 hover:border-blue-500/50 dark:hover:border-[#3b82f6]/50 shadow-sm dark:shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center group-hover:scale-125 transition-transform duration-500">
                    <span class="text-4xl opacity-50">👥</span>
                </div>
                <h3 class="text-slate-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest relative z-10">Clientes Totales</h3>
                <p class="text-5xl font-black text-slate-800 dark:text-white mt-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors relative z-10">{{ $clientes }}</p>
            </div>

            <div class="bg-white dark:bg-[#2a2a2a] p-8 rounded-3xl border border-slate-200 dark:border-zinc-700 hover:border-cyan-500/50 dark:hover:border-[#06b6d4]/50 shadow-sm dark:shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-cyan-50 dark:bg-[#06b6d4]/10 rounded-full flex items-center justify-center group-hover:scale-125 transition-transform duration-500">
                    <span class="text-4xl opacity-50">📅</span>
                </div>
                <h3 class="text-slate-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest relative z-10">Citas Activas</h3>
                <p class="text-5xl font-black text-slate-800 dark:text-white mt-3 group-hover:text-cyan-600 dark:group-hover:text-[#06b6d4] transition-colors relative z-10">{{ count($citas) }}</p>
            </div>

            <div class="bg-white dark:bg-[#2a2a2a] p-8 rounded-3xl border border-slate-200 dark:border-zinc-700 hover:border-emerald-500/50 dark:hover:border-[#10b981]/50 shadow-sm dark:shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center group-hover:scale-125 transition-transform duration-500">
                    <span class="text-4xl opacity-50">⭐</span>
                </div>
                <h3 class="text-slate-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest relative z-10">Reseñas Registradas</h3>
                <p class="text-5xl font-black text-slate-800 dark:text-white mt-3 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors relative z-10">{{ count($reseñas) }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="100">
            <div class="bg-white dark:bg-[#2a2a2a] rounded-3xl border border-slate-200 dark:border-zinc-700 shadow-sm dark:shadow-xl overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100 dark:border-zinc-700 bg-slate-50 dark:bg-[#222222] flex justify-between items-center">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-2">
                        📅 Próximas Citas
                    </h3>
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-700 dark:text-[#3b82f6] border border-blue-200 dark:border-[#3b82f6]/30 px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-[#3b82f6]/10">Hoy</span>
                </div>
                
                <div class="p-6 flex-1 space-y-4">
                    @forelse($citas as $cita)
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-[#222222] border border-slate-100 dark:border-zinc-700 hover:border-blue-400 dark:hover:border-[#3b82f6]/50 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-white dark:bg-[#2a2a2a] border border-slate-200 dark:border-zinc-600 flex items-center justify-center text-blue-600 dark:text-[#3b82f6] font-black text-xl shadow-sm dark:shadow-inner">
                                {{ $cita->user ? substr($cita->user->name, 0, 1) : 'C' }}
                            </div>
                            
                            <div class="flex-1 truncate">
                                <h4 class="font-bold text-slate-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-[#3b82f6] transition-colors truncate">{{ $cita->user->name ?? 'Cliente Físico' }}</h4>
                                <p class="text-xs font-medium text-slate-500 dark:text-gray-400 truncate">{{ $cita->servicio }}</p>
                            </div>

                            <div class="text-right">
                                <p class="text-slate-900 dark:text-white font-black">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-gray-500">{{ \Carbon\Carbon::parse($cita->fecha)->format('d M') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center py-10">
                            <span class="text-4xl opacity-30 mb-3">😴</span>
                            <p class="text-slate-500 dark:text-gray-500 font-medium">No hay citas pendientes para hoy.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-[#2a2a2a] rounded-3xl border border-slate-200 dark:border-zinc-700 shadow-sm dark:shadow-xl overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100 dark:border-zinc-700 bg-slate-50 dark:bg-[#222222]">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-2">
                        ⭐ Feedback Reciente
                    </h3>
                </div>

                <div class="p-6 flex-1 space-y-4">
                    @forelse($reseñas as $r)
                        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-[#222222] border border-slate-100 dark:border-zinc-700 hover:border-blue-400 dark:hover:border-[#3b82f6]/50 transition-all relative">
                            <div class="flex justify-between items-start mb-3">
                                <span class="font-black text-slate-800 dark:text-[#3b82f6] text-sm">{{ $r->user->name ?? 'Usuario Anónimo' }}</span>
                                <div class="flex text-blue-500 dark:text-blue-400 text-xs drop-shadow-sm dark:drop-shadow-md">
                                    @for($i=0; $i < $r->calificacion; $i++) ★ @endfor
                                    @for($i=$r->calificacion; $i < 5; $i++) <span class="text-slate-300 dark:text-zinc-600">★</span> @endfor
                                </div>
                            </div>
                            <p class="text-slate-600 dark:text-gray-400 text-sm font-medium italic">"{{Str::limit($r->comentario, 90)}}"</p>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center py-10">
                            <span class="text-4xl opacity-30 mb-3">💬</span>
                            <p class="text-slate-500 dark:text-gray-500 font-medium">Aún no hay reseñas registradas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 🛡️ MÓDULO DE RESPALDOS PARA SUPERADMIN --}}
        @if(auth()->user()->is_superadmin)
            <div class="mt-8 bg-[#2a2a2a] rounded-3xl p-8 border border-zinc-700 text-center shadow-xl relative overflow-hidden" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500 opacity-10 rounded-bl-full pointer-events-none"></div>
                <h3 class="text-2xl font-black text-white mb-2 flex items-center justify-center gap-2">
                    🛡️ Respaldo de Seguridad
                </h3>
                <p class="text-zinc-400 text-sm mb-6 max-w-lg mx-auto">
                    Crea una copia de seguridad manual de toda la base de datos (se comprimirá en un archivo .zip interno para protección contra pérdida de datos).
                </p>
                <button onclick="generarRespaldo()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-transform hover:scale-105 border border-blue-500">
                    💾 Generar Respaldo Ahora
                </button>
            </div>

            <script>
                function generarRespaldo() {
                    const isDark = document.documentElement.classList.contains('dark');
                    Swal.fire({
                        title: 'Empacando Base de Datos...',
                        text: 'Esto puede tomar unos segundos. Por favor, no cierres la ventana.',
                        allowOutsideClick: false,
                        background: isDark ? '#222222' : '#ffffff',
                        color: isDark ? '#ffffff' : '#0f172a',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route("admin.backup") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire({
                                title: '¡Protegido!', 
                                text: data.message, 
                                icon: 'success',
                                background: isDark ? '#222222' : '#ffffff',
                                color: isDark ? '#ffffff' : '#0f172a'
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Hubo un problema al conectar con el servidor.', 'error');
                    });
                }
            </script>
        @endif

    </div>
</div>
@endsection