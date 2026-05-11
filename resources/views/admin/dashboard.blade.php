@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-gray-100 p-4 md:p-10 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-yellow-400 dark:bg-[#d4af37] opacity-[0.02] dark:opacity-[0.05] blur-[120px] pointer-events-none rounded-full"></div>

    <div class="max-w-7xl mx-auto space-y-8 relative z-10">

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6" data-aos="fade-down">
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="text-yellow-600 dark:text-[#d4af37]">💈</span> Panel de Control
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

                <a href="{{ route('admin.statistics') }}" class="flex-1 lg:flex-none text-center bg-white dark:bg-[#111] border border-slate-200 dark:border-[#333] hover:bg-slate-100 dark:hover:bg-[#1a1a1a] hover:border-yellow-600 dark:hover:border-[#d4af37]/50 text-slate-800 dark:text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-sm dark:shadow-md flex items-center justify-center gap-2">
                    📊 Estadísticas
                </a>
                <a href="{{ route('admin.appointments') }}" class="flex-1 lg:flex-none text-center bg-slate-100 dark:bg-[#1a1a1a] border border-slate-300 dark:border-[#d4af37]/30 hover:bg-slate-200 dark:hover:bg-[#222] hover:border-yellow-600 dark:hover:border-[#d4af37] text-slate-900 dark:text-white font-bold px-6 py-3.5 rounded-xl transition-all shadow-sm dark:shadow-md flex items-center justify-center gap-2">
                    📅 Citas
                </a>
                <a href="{{ route('admin.barbers.index') }}" class="flex-1 lg:flex-none text-center bg-gradient-to-r from-yellow-400 to-yellow-500 dark:from-[#d4af37] dark:to-[#b8962e] hover:from-yellow-500 hover:to-yellow-600 dark:hover:from-[#e0c15a] dark:hover:to-[#c9a43b] text-slate-900 dark:text-black font-black px-6 py-3.5 rounded-xl transition-all shadow-md dark:shadow-[0_0_15px_rgba(212,175,55,0.4)] flex items-center justify-center gap-2">
                    ✂️ Equipo
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-[#003311] border border-emerald-200 dark:border-[#006622] text-emerald-700 dark:text-[#00ff55] px-6 py-4 rounded-2xl mb-8 font-bold flex items-center gap-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10" data-aos="fade-up">
            <div class="bg-white dark:bg-[#111] p-8 rounded-3xl border border-slate-200 dark:border-[#222] hover:border-blue-500/50 dark:hover:border-[#3b82f6]/50 shadow-sm dark:shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center group-hover:scale-125 transition-transform duration-500">
                    <span class="text-4xl opacity-50">👥</span>
                </div>
                <h3 class="text-slate-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest relative z-10">Clientes Totales</h3>
                <p class="text-5xl font-black text-slate-800 dark:text-white mt-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors relative z-10">{{ $clientes }}</p>
            </div>

            <div class="bg-white dark:bg-[#111] p-8 rounded-3xl border border-slate-200 dark:border-[#222] hover:border-yellow-500/50 dark:hover:border-[#d4af37]/50 shadow-sm dark:shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-50 dark:bg-[#d4af37]/10 rounded-full flex items-center justify-center group-hover:scale-125 transition-transform duration-500">
                    <span class="text-4xl opacity-50">📅</span>
                </div>
                <h3 class="text-slate-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest relative z-10">Citas Activas</h3>
                <p class="text-5xl font-black text-slate-800 dark:text-white mt-3 group-hover:text-yellow-600 dark:group-hover:text-[#d4af37] transition-colors relative z-10">{{ count($citas) }}</p>
            </div>

            <div class="bg-white dark:bg-[#111] p-8 rounded-3xl border border-slate-200 dark:border-[#222] hover:border-emerald-500/50 dark:hover:border-[#10b981]/50 shadow-sm dark:shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center group-hover:scale-125 transition-transform duration-500">
                    <span class="text-4xl opacity-50">⭐</span>
                </div>
                <h3 class="text-slate-500 dark:text-gray-400 text-xs font-bold uppercase tracking-widest relative z-10">Reseñas Registradas</h3>
                <p class="text-5xl font-black text-slate-800 dark:text-white mt-3 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors relative z-10">{{ count($reseñas) }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="100">
            <div class="bg-white dark:bg-[#111] rounded-3xl border border-slate-200 dark:border-[#222] shadow-sm dark:shadow-xl overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100 dark:border-[#222] bg-slate-50 dark:bg-[#161616] flex justify-between items-center">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-2">
                        📅 Próximas Citas
                    </h3>
                    <span class="text-[10px] font-black uppercase tracking-widest text-yellow-700 dark:text-[#d4af37] border border-yellow-200 dark:border-[#d4af37]/30 px-3 py-1.5 rounded-lg bg-yellow-50 dark:bg-[#d4af37]/10">Hoy</span>
                </div>
                
                <div class="p-6 flex-1 space-y-4">
                    @forelse($citas as $cita)
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-[#0a0a0a] border border-slate-100 dark:border-[#333] hover:border-yellow-400 dark:hover:border-[#d4af37]/50 transition-all group">
                            <div class="w-12 h-12 rounded-xl bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-[#444] flex items-center justify-center text-yellow-600 dark:text-[#d4af37] font-black text-xl shadow-sm dark:shadow-inner">
                                {{ substr($cita->user->name, 0, 1) }}
                            </div>
                            
                            <div class="flex-1 truncate">
                                <h4 class="font-bold text-slate-800 dark:text-gray-200 group-hover:text-yellow-600 dark:group-hover:text-[#d4af37] transition-colors truncate">{{ $cita->user->name }}</h4>
                                <p class="text-xs font-medium text-slate-500 dark:text-gray-500 truncate">{{ $cita->servicio }}</p>
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

            <div class="bg-white dark:bg-[#111] rounded-3xl border border-slate-200 dark:border-[#222] shadow-sm dark:shadow-xl overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-100 dark:border-[#222] bg-slate-50 dark:bg-[#161616]">
                    <h3 class="text-xl font-black text-slate-800 dark:text-white flex items-center gap-2">
                        ⭐ Feedback Reciente
                    </h3>
                </div>

                <div class="p-6 flex-1 space-y-4">
                    @forelse($reseñas as $r)
                        <div class="p-5 rounded-2xl bg-slate-50 dark:bg-[#0a0a0a] border border-slate-100 dark:border-[#333] hover:border-yellow-400 dark:hover:border-[#d4af37]/50 transition-all relative">
                            <div class="flex justify-between items-start mb-3">
                                <span class="font-black text-slate-800 dark:text-[#d4af37] text-sm">{{ $r->user->name }}</span>
                                <div class="flex text-yellow-400 dark:text-yellow-500 text-xs drop-shadow-sm dark:drop-shadow-md">
                                    @for($i=0; $i < $r->calificacion; $i++) ★ @endfor
                                    @for($i=$r->calificacion; $i < 5; $i++) <span class="text-slate-300 dark:text-gray-700">★</span> @endfor
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
    </div>
</div>
@endsection