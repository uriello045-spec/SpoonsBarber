@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#0a0a0a] text-slate-900 dark:text-gray-100 p-6 md:p-10 transition-colors duration-300 relative overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-64 bg-yellow-400 dark:bg-[#d4af37] opacity-[0.02] dark:opacity-[0.03] blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto space-y-8 relative z-10">
        
        <div class="mb-2">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-500 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-[#d4af37] transition-colors font-bold flex items-center gap-2 w-max">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver al Panel
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-500/50 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm dark:shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                <span class="font-bold">✅ {{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                <span class="text-yellow-600 dark:text-[#d4af37]">✂️</span> Gestión de Barberos
            </h2>

            <a href="{{ route('admin.barbers.create') }}" class="w-full md:w-auto text-center bg-gradient-to-r from-yellow-400 to-yellow-500 dark:from-[#d4af37] dark:to-[#b8962e] hover:from-yellow-500 hover:to-yellow-600 dark:hover:from-[#e0c15a] dark:hover:to-[#c9a43b] text-slate-900 dark:text-black font-black px-6 py-3.5 rounded-xl transition-all shadow-md dark:shadow-[0_0_20px_rgba(212,175,55,0.3)] transform hover:-translate-y-1 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg> 
                Agregar Barbero
            </a>
        </div>

        @if ($barbers->isEmpty())
            <div class="bg-white dark:bg-[#111] border border-slate-200 dark:border-[#222] rounded-3xl p-16 text-center shadow-sm dark:shadow-2xl">
                <div class="w-20 h-20 mx-auto bg-slate-50 dark:bg-[#1a1a1a] rounded-full flex items-center justify-center mb-4 border border-slate-100 dark:border-[#333]">
                    <span class="text-3xl opacity-50">👥</span>
                </div>
                <p class="text-slate-500 dark:text-gray-400 text-lg font-bold">Aún no hay otros barberos registrados.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($barbers as $barber)
                    @php
                        // 🌟 LÓGICA DINÁMICA BASADA EN LA BD 🌟
                        $isSuperAdminCard = $barber->is_superadmin;
                        $iAmSuperAdmin = (auth()->check() && auth()->user()->is_superadmin);
                    @endphp

                    {{-- Si la tarjeta es del SuperAdmin, pero el que inició sesión es un barbero normal, la ocultamos --}}
                    @if($isSuperAdminCard && !$iAmSuperAdmin)
                        @continue
                    @endif

                    <div id="tarjeta-barbero-{{ $barber->id }}" class="bg-white dark:bg-[#141414] p-6 rounded-3xl border {{ $isSuperAdminCard ? 'border-yellow-400 dark:border-[#d4af37]' : 'border-slate-200 dark:border-[#2a2a2a] hover:border-yellow-400 dark:hover:border-[#d4af37]/50' }} shadow-sm dark:shadow-lg hover:shadow-md dark:hover:shadow-[0_10px_30px_rgba(212,175,55,0.1)] transition-all duration-300 group flex flex-col relative overflow-hidden">
                        
                        {{-- Brillo sutil de fondo si es el SuperAdmin --}}
                        @if($isSuperAdminCard)
                            <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/10 dark:bg-[#d4af37]/10 rounded-full blur-3xl pointer-events-none -mr-10 -mt-10"></div>
                        @endif

                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-gradient-to-br dark:from-[#222] dark:to-[#111] flex items-center justify-center text-yellow-600 dark:text-[#d4af37] font-black text-2xl border {{ $isSuperAdminCard ? 'border-yellow-400 shadow-[0_0_15px_rgba(212,175,55,0.3)]' : 'border-slate-200 dark:border-[#333]' }} shadow-inner group-hover:scale-105 transition-transform">
                                {{ substr($barber->name, 0, 1) }}
                            </div>
                            
                            {{-- ETIQUETA DINÁMICA: SUPERADMIN O BARBERO --}}
                            @if($isSuperAdminCard)
                                <span class="bg-gradient-to-r from-yellow-400 to-yellow-500 dark:from-[#d4af37] dark:to-[#b8962e] text-black text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-md">SUPERADMIN</span>
                            @else
                                <span class="bg-yellow-50 dark:bg-[#d4af37]/10 text-yellow-700 dark:text-[#d4af37] text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-yellow-200 dark:border-[#d4af37]/20">Barbero</span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-black text-slate-800 dark:text-white group-hover:text-yellow-600 dark:group-hover:text-[#d4af37] transition-colors truncate relative z-10">
                            {{ $barber->name }}
                            @if($isSuperAdminCard) <span title="Cuenta Maestra">👑</span> @endif
                        </h3>
                        <p class="text-slate-500 dark:text-gray-400 text-sm font-medium mt-1 truncate relative z-10">{{ $barber->email }}</p>
                        
                        @if ($barber->phone)
                            <p class="text-slate-600 dark:text-gray-300 text-sm font-bold mt-4 flex items-center gap-2 bg-slate-50 dark:bg-[#0a0a0a] p-2.5 rounded-lg border border-slate-100 dark:border-[#222] relative z-10">
                                📞 {{ $barber->phone }}
                            </p>
                        @endif
                        
                        <div class="mt-auto pt-5 relative z-10">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-gray-500">Ingreso:</span>
                                <span class="text-xs text-slate-600 dark:text-gray-300 font-bold bg-slate-50 dark:bg-[#1a1a1a] px-2 py-1 rounded border border-slate-100 dark:border-[#333]">{{ $barber->created_at->format('d/m/Y') }}</span>
                            </div>

                            <div class="flex gap-2 border-t border-slate-100 dark:border-[#222] pt-4">
                                @if($isSuperAdminCard)
                                    @php $myId = $barber->id; @endphp
                                    {{-- 🌟 BOTÓN PARA EDITAR LA CUENTA DEL SUPERADMIN (Pide Contraseña) 🌟 --}}
                                    <button type="button" onclick="editarSuperAdmin('{{ $myId }}')" class="w-full text-center py-2 text-xs font-black text-yellow-700 dark:text-[#d4af37] bg-yellow-50 dark:bg-[#d4af37]/10 hover:bg-yellow-100 dark:hover:bg-[#d4af37]/20 rounded-lg border border-yellow-200 dark:border-[#d4af37]/30 transition-all">
                                        ✏️ Editar Mis Datos
                                    </button>
                                @else
                                    {{-- Botones normales para los barberos --}}
                                    <a href="{{ route('admin.barbers.edit', $barber->id) }}" class="flex-1 bg-slate-50 dark:bg-[#1a1a1a] hover:bg-yellow-400 dark:hover:bg-[#d4af37] text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-black font-bold py-2 rounded-lg text-xs text-center transition-all border border-slate-200 dark:border-[#333] hover:border-yellow-400 dark:hover:border-[#d4af37]">
                                        ✏️ Editar
                                    </a>
                                    
                                    @php $barberIdForJs = $barber->id; @endphp
                                    <button type="button" onclick="confirmarEliminacion('{{ $barberIdForJs }}')" class="flex-1 w-full bg-rose-50 dark:bg-rose-900/10 hover:bg-rose-500 dark:hover:bg-rose-600 text-rose-600 dark:text-rose-500 hover:text-white font-bold py-2 rounded-lg text-xs transition-all border border-rose-200 dark:border-rose-900/30 hover:border-rose-500 dark:hover:border-rose-600">
                                        🗑️ Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 🌟 FUNCIÓN 1: EDITAR DATOS DEL SUPERADMIN (Requiere Contraseña) 🌟
    function editarSuperAdmin(id) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: '🔒 Acceso Restringido',
            text: 'Por seguridad, ingresa tu contraseña maestra para editar tus datos:',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off',
                placeholder: 'Escribe tu contraseña...'
            },
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#d4af37', 
            cancelButtonColor: isDark ? '#1a1a1a' : '#f1f5f9', 
            confirmButtonText: 'Verificar y Editar',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#111111' : '#ffffff', 
            color: isDark ? '#ffffff' : '#0f172a', 
            iconColor: '#d4af37', 
            customClass: {
                popup: isDark ? 'border border-[#333] rounded-2xl shadow-2xl' : 'border border-slate-200 rounded-2xl shadow-xl',
                cancelButton: isDark ? 'text-white' : 'text-slate-700 border border-slate-300',
                input: isDark ? 'text-white bg-[#374151] border-[#4b5563] focus:border-[#d4af37] focus:ring-[#d4af37]' : 'text-gray-900 bg-white border-gray-300'
            },
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('Debes escribir una contraseña');
                    return false;
                }
                
                return fetch('{{ route("admin.verifyMaster") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ password: password })
                })
                .then(response => {
                    if (!response.ok) { return response.json().then(err => { throw new Error(err.message) }); }
                    return response.json();
                })
                .catch(error => { Swal.showValidationMessage(`❌ ${error.message}`); });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value && result.value.success) {
                // Si la contraseña es correcta, lo mandamos a la página de edición
                window.location.href = `/admin/barberos/${id}/editar`;
            }
        });
    }

    // 🌟 FUNCIÓN 2: ELIMINAR BARBERO NORMAL (Requiere Contraseña) 🌟
    function confirmarEliminacion(id) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: '🛡️ ALERTA DE SEGURIDAD',
            text: 'Para eliminar a este barbero, ingresa la contraseña de Uriel (SuperAdmin):',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off',
                placeholder: 'Escribe la clave maestra...'
            },
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', 
            cancelButtonColor: isDark ? '#1a1a1a' : '#f1f5f9', 
            confirmButtonText: 'Autorizar y Eliminar',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#111111' : '#ffffff', 
            color: isDark ? '#ffffff' : '#0f172a', 
            iconColor: isDark ? '#d4af37' : '#ca8a04', 
            customClass: {
                popup: isDark ? 'border border-[#333] rounded-2xl shadow-2xl' : 'border border-slate-200 rounded-2xl shadow-xl',
                cancelButton: isDark ? 'text-white' : 'text-slate-700 border border-slate-300',
                input: isDark ? 'text-white bg-[#374151] border-[#4b5563] focus:border-[#d4af37] focus:ring-[#d4af37]' : 'text-gray-900 bg-white border-gray-300'
            },
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('Debes escribir una contraseña');
                    return false;
                }
                
                return fetch(`/admin/barberos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ superadmin_password: password })
                })
                .then(response => {
                    if (!response.ok) { return response.json().then(err => { throw new Error(err.message) }); }
                    return response.json();
                })
                .catch(error => { Swal.showValidationMessage(`❌ ${error.message}`); });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value && result.value.success) {
                Swal.fire({
                    title: '¡Eliminado!',
                    text: result.value.message,
                    icon: 'success',
                    background: isDark ? '#111111' : '#ffffff', 
                    color: isDark ? '#ffffff' : '#0f172a',
                    customClass: { popup: isDark ? 'border border-[#333] rounded-2xl shadow-2xl' : 'border border-slate-200 rounded-2xl shadow-xl' }
                });
                
                let tarjeta = document.getElementById('tarjeta-barbero-' + id);
                if (tarjeta) {
                    tarjeta.style.transition = "all 0.5s ease";
                    tarjeta.style.transform = "scale(0.9)";
                    tarjeta.style.opacity = 0;
                    setTimeout(() => tarjeta.remove(), 500); 
                }
            }
        });
    }
</script>
@endsection