@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-gray-100 p-4 md:p-10 transition-colors duration-300 relative">
    
    <div class="max-w-7xl mx-auto space-y-8 relative z-10">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10" data-aos="fade-down">
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="text-yellow-600 dark:text-[#d4af37]">📈</span> Rendimiento
                </h2>
                <p class="text-slate-500 dark:text-gray-400 font-medium mt-2 uppercase tracking-widest text-sm">Reporte financiero y métricas de negocio</p>
            </div>

            <a href="{{ route('admin.dashboard') }}" class="w-full md:w-auto text-center bg-white dark:bg-[#111] border border-slate-200 dark:border-[#333] hover:bg-slate-100 dark:hover:bg-[#1a1a1a] hover:border-yellow-600 dark:hover:border-[#d4af37] text-slate-800 dark:text-white px-6 py-3.5 rounded-2xl transition-all shadow-sm dark:shadow-lg font-bold flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10" data-aos="fade-up">
            
            <div class="bg-gradient-to-br from-emerald-50 to-white dark:from-[#0a1a10] dark:to-[#0a0a0a] p-8 rounded-3xl border border-emerald-200 dark:border-emerald-900/30 shadow-sm dark:shadow-[0_10px_40px_rgba(16,185,129,0.05)] relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-100 dark:bg-emerald-900/20 rounded-full blur-2xl group-hover:bg-emerald-200 dark:group-hover:bg-emerald-900/40 transition-all duration-700"></div>
                <h3 class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest relative z-10">Ingresos Estimados</h3>
                <p class="text-4xl md:text-5xl font-black text-emerald-600 dark:text-[#00ff55] mt-3 relative z-10 drop-shadow-sm">${{ number_format($ingresos) }}</p>
                <p class="text-[11px] font-bold text-slate-400 dark:text-gray-500 mt-2 relative z-10 uppercase tracking-wider flex items-center gap-1">
                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    Capital generado
                </p>
            </div>

            <div class="bg-gradient-to-br from-rose-50 to-white dark:from-[#1a0a0a] dark:to-[#0a0a0a] p-8 rounded-3xl border border-rose-200 dark:border-rose-900/30 shadow-sm dark:shadow-[0_10px_40px_rgba(244,63,94,0.05)] relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-rose-100 dark:bg-rose-900/20 rounded-full blur-2xl group-hover:bg-rose-200 dark:group-hover:bg-rose-900/40 transition-all duration-700"></div>
                <h3 class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest relative z-10">Pérdidas Estimadas</h3>
                <p class="text-4xl md:text-5xl font-black text-rose-600 dark:text-[#ff4444] mt-3 relative z-10 drop-shadow-sm">-${{ number_format($perdidas ?? 0) }}</p>
                <p class="text-[11px] font-bold text-slate-400 dark:text-gray-500 mt-2 relative z-10 uppercase tracking-wider flex items-center gap-1">
                    <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Por cancelaciones
                </p>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-white dark:from-[#1a1a0a] dark:to-[#0a0a0a] p-8 rounded-3xl border border-yellow-200 dark:border-[#d4af37]/30 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-yellow-100 dark:bg-[#d4af37]/10 rounded-full blur-2xl group-hover:bg-yellow-200 dark:group-hover:bg-[#d4af37]/20 transition-all duration-700"></div>
                <h3 class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest relative z-10 flex items-center gap-1">Cliente Estrella <span class="text-yellow-500 text-lg ml-1 drop-shadow-sm dark:drop-shadow-md">⭐</span></h3>
                @if($topCliente)
                    <p class="text-2xl font-black text-slate-800 dark:text-white mt-4 truncate relative z-10">{{ $topCliente->user->name }}</p>
                    <p class="text-xs font-bold text-yellow-600 dark:text-[#d4af37] mt-1 uppercase tracking-widest relative z-10">{{ $topCliente->total }} visitas</p>
                @else
                    <p class="text-3xl font-black text-slate-300 dark:text-gray-700 mt-3 relative z-10">--</p>
                    <p class="text-xs text-slate-400 dark:text-gray-500 mt-1 uppercase relative z-10">Sin datos</p>
                @endif
            </div>

            <div class="bg-white dark:bg-[#111] p-8 rounded-3xl border border-slate-200 dark:border-[#222] border-l-4 border-l-emerald-500 shadow-sm flex flex-col justify-center hover:border-slate-300 dark:hover:border-[#333] transition-colors">
                <h3 class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">Cortes Finalizados</h3>
                <p class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mt-3">{{ $citasCompletadas }}</p>
            </div>

            <div class="bg-white dark:bg-[#111] p-8 rounded-3xl border border-slate-200 dark:border-[#222] border-l-4 border-l-rose-500 shadow-sm flex flex-col justify-center hover:border-slate-300 dark:hover:border-[#333] transition-colors">
                <h3 class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">Citas Canceladas</h3>
                <p class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mt-3">{{ $citasCanceladas ?? 0 }}</p>
            </div>

            <div class="bg-white dark:bg-[#111] p-8 rounded-3xl border border-slate-200 dark:border-[#222] border-l-4 border-l-blue-500 shadow-sm flex flex-col justify-center hover:border-slate-300 dark:hover:border-[#333] transition-colors">
                <h3 class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">Total Citas Históricas</h3>
                <p class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mt-3">{{ $totalCitas }}</p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="100">
            
            <div class="lg:col-span-2 bg-white dark:bg-[#111] rounded-3xl p-8 border border-slate-200 dark:border-[#222] shadow-sm relative">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-widest">📉 Ingresos Mensuales</h3>
                    <span class="text-xs font-bold text-slate-500 bg-slate-100 dark:bg-[#1a1a1a] px-3 py-1 rounded-md">Últimos 6 meses</span>
                </div>
                <div class="relative h-72 w-full">
                    <canvas id="ingresosChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-[#111] rounded-3xl p-8 border border-slate-200 dark:border-[#222] shadow-sm relative">
                <h3 class="text-lg font-black text-slate-800 dark:text-white mb-8 uppercase tracking-widest text-center">🎯 Estado de Agenda</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="estadosChart"></canvas>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div id="graficas-data" 
     data-ingresos="{{ json_encode($ingresosPorMes) }}" 
     data-estados="{{ json_encode($citasPorEstado) }}" 
     style="display: none;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- LEER DATOS DESDE EL HTML (Cero errores rojos) ---
        const dataContainer = document.getElementById('graficas-data');
        const ingresosData = JSON.parse(dataContainer.dataset.ingresos);
        const estadosData = JSON.parse(dataContainer.dataset.estados);
        
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#64748b'; // Gris claro u oscuro dependiendo del tema

        // --- 1. GRÁFICA DE BARRAS (INGRESOS) ---
        const ctxIngresos = document.getElementById('ingresosChart').getContext('2d');
        
        const mesesCrudos = Object.keys(ingresosData).length > 0 ? Object.keys(ingresosData) : ['Mes Actual'];
        const mesesBonitos = mesesCrudos.map(mes => {
            if(mes === 'Mes Actual') return mes;
            const [year, month] = mes.split('-');
            const date = new Date(year, month - 1);
            return date.toLocaleString('es-ES', { month: 'short', year: 'numeric' }).toUpperCase();
        });
        const ganancias = Object.values(ingresosData).length > 0 ? Object.values(ingresosData) : [0];

        new Chart(ctxIngresos, {
            type: 'bar',
            data: {
                labels: mesesBonitos,
                datasets: [{
                    label: 'Ganancias ($)',
                    data: ganancias,
                    backgroundColor: '#10b981', // Verde Esmeralda para ingresos
                    hoverBackgroundColor: '#059669',
                    borderRadius: 6, 
                    borderSkipped: false,
                    barThickness: 40 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#222' : '#fff',
                        titleColor: isDark ? '#fff' : '#333',
                        bodyColor: '#10b981',
                        borderColor: isDark ? '#444' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: { label: function(context) { return '$' + context.raw.toLocaleString(); } }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { color: isDark ? '#1a1a1a' : '#f1f5f9', drawBorder: false },
                        ticks: { color: textColor, font: { weight: 'bold' }, callback: function(value) { return '$' + value; } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: textColor, font: { weight: 'bold' } }
                    }
                }
            }
        });

        // --- 2. GRÁFICA DE DONA (ESTADOS) ---
        const ctxEstados = document.getElementById('estadosChart').getContext('2d');
        
        const labelsEstados = Object.keys(estadosData);
        const valoresEstados = Object.values(estadosData);
        const bgColors = [];

        labelsEstados.forEach(estado => {
            if(estado === 'pendiente') bgColors.push('#f59e0b');
            else if(estado === 'confirmada') bgColors.push('#3b82f6'); 
            else if(estado === 'completada') bgColors.push('#10b981'); 
            else if(estado === 'cancelada') bgColors.push('#f43f5e'); 
            else bgColors.push('#94a3b8');
        });

        const finalLabels = labelsEstados.length > 0 ? labelsEstados.map(l => l.toUpperCase()) : ['SIN CITAS'];
        const finalValues = valoresEstados.length > 0 ? valoresEstados : [1];
        const finalColors = bgColors.length > 0 ? bgColors : [isDark ? '#222' : '#e2e8f0'];

        new Chart(ctxEstados, {
            type: 'doughnut',
            data: {
                labels: finalLabels,
                datasets: [{
                    data: finalValues,
                    backgroundColor: finalColors,
                    borderWidth: isDark ? 4 : 2,
                    borderColor: isDark ? '#111' : '#fff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', 
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: textColor, padding: 20, font: { weight: 'bold', size: 11 }, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#222' : '#fff',
                        titleColor: isDark ? '#fff' : '#333',
                        bodyColor: isDark ? '#aaa' : '#666',
                        borderColor: isDark ? '#444' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12
                    }
                }
            }
        });

    });
</script>
@endsection