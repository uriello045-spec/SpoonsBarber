@extends('layouts.app')

@section('content')

<style>
    /* ==========================================
       ANIMACIÓN DE ESTRELLAS (AHORA EN AZUL NEÓN)
       ========================================== */
    .radio {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-direction: row-reverse;
    }

    .radio > input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    .radio > label {
        cursor: pointer;
        font-size: 30px;
        position: relative;
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .radio > label > svg {
        fill: #52525b; /* Gris apagado por defecto */
        transition: fill 0.3s ease;
    }

    .radio > label::before,
    .radio > label::after {
        content: "";
        position: absolute;
        width: 6px;
        height: 6px;
        background-color: #3b82f6; /* Azul base */
        border-radius: 50%;
        opacity: 0;
        transform: scale(0);
        transition: transform 0.4s ease, opacity 0.4s ease;
    }

    .radio > label::before { top: -15px; left: 50%; transform: translateX(-50%) scale(0); }
    .radio > label::after { bottom: -15px; left: 50%; transform: translateX(-50%) scale(0); }

    .radio > label:hover::before,
    .radio > label:hover::after {
        opacity: 1;
        transform: translateX(-50%) scale(1.5);
        animation: particle-explosion 1s ease-out;
    }

    .radio > label:hover {
        transform: scale(1.2);
        animation: pulse 0.6s infinite alternate;
    }

    .radio > label:hover > svg,
    .radio > label:hover ~ label > svg {
        fill: #3b82f6; /* Estrella azul al hacer hover */
        filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.7));
    }

    .radio > input:checked ~ label > svg,
    .radio > input:checked + label > svg {
        fill: #3b82f6; /* Estrella azul seleccionada */
        filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.7));
        animation: pulse 0.8s infinite alternate;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }

    @keyframes particle-explosion {
        0% { opacity: 0; transform: scale(0.5); }
        50% { opacity: 1; transform: scale(1.2); }
        100% { opacity: 0; transform: scale(0.5); }
    }
</style>

<div class="min-h-screen w-full bg-slate-50 dark:bg-zinc-900 transition-colors duration-300 py-10 px-4">
    <div class="max-w-5xl mx-auto space-y-12">
        
        <div class="text-center" data-aos="fade-down">
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-[#3b82f6] mb-3 tracking-tight">
                ⭐ Experiencias
            </h2>
            <p class="text-slate-500 dark:text-gray-400 text-lg font-medium">Lo que dicen nuestros clientes en Spoon’s Barber Shop</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-600/50 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-2xl flex items-center justify-center gap-3 shadow-lg" data-aos="fade-in">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold text-lg">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-600/50 text-red-700 dark:text-red-400 px-6 py-4 rounded-2xl flex flex-col justify-center gap-1 shadow-lg" data-aos="fade-in">
                @foreach ($errors->all() as $error)
                    <span class="font-bold text-sm">🚨 {{ $error }}</span>
                @endforeach
            </div>
        @endif

        @if(!auth()->check() || (auth()->check() && auth()->user()->role !== 'barbero' && !auth()->user()->is_superadmin))
            <div class="bg-white dark:bg-zinc-800 rounded-3xl p-8 border border-slate-200 dark:border-zinc-700 shadow-xl dark:shadow-2xl relative overflow-hidden group transition-colors duration-300" data-aos="fade-up">
                <div class="hidden dark:block absolute top-0 right-0 w-32 h-32 bg-[#3b82f6] opacity-10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>

                @if($puedeComentar)
                    <h3 class="text-2xl font-black text-slate-800 dark:text-gray-200 mb-2 flex items-center gap-2">
                        <span class="text-blue-500 dark:text-[#3b82f6]">✏️</span> Opina sobre tu cita
                    </h3>
                    <p class="text-slate-500 dark:text-gray-400 mb-6 font-medium">Recientemente completaste tu servicio de <strong class="text-blue-600 dark:text-[#3b82f6] uppercase">{{ $citaElegible->servicio ?? 'Barbería' }}</strong>. ¡Cuéntanos qué tal te pareció!</p>

                    <form action="{{ route('references.store') }}" method="POST" id="form-resena">
                        @csrf
                        
                        <div class="mb-6 relative">
                            <textarea id="txt-comentario" name="comentario" rows="4" placeholder="¿Cómo te atendieron? ¿Te gustó el resultado?" 
                                      minlength="10" maxlength="250" required 
                                      onkeydown="if(['<', '>'].includes(event.key)) event.preventDefault();"
                                      oninput="document.getElementById('char-count').innerText = this.value.length + ' / 250'; if(this.value.length > 250) this.value = this.value.substring(0, 250);"
                                      class="w-full bg-slate-50 dark:bg-zinc-900 border border-slate-300 dark:border-zinc-600 text-slate-900 dark:text-white rounded-xl px-6 py-5 
                                             focus:bg-white dark:focus:bg-zinc-800 focus:border-blue-500 dark:focus:border-[#3b82f6] focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-[#3b82f6]/20 outline-none transition-all resize-none text-lg font-medium placeholder-slate-400 dark:placeholder-gray-500"></textarea>
                            
                            <div class="absolute bottom-3 right-4">
                                <span id="char-count" class="text-xs font-bold text-slate-400 dark:text-gray-500">0 / 250</span>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-4">
                            
                            <div class="flex flex-col items-center md:items-start gap-3">
                                <label class="text-slate-500 dark:text-gray-400 font-bold uppercase text-sm tracking-wider">Calificación</label>
                                
                                <div class="radio">
                                    <input value="5" name="calificacion" type="radio" id="rating-5" required />
                                    <label title="5 estrellas" for="rating-5">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                    </label>

                                    <input value="4" name="calificacion" type="radio" id="rating-4" />
                                    <label title="4 estrellas" for="rating-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                    </label>

                                    <input value="3" name="calificacion" type="radio" id="rating-3" />
                                    <label title="3 estrellas" for="rating-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                    </label>

                                    <input value="2" name="calificacion" type="radio" id="rating-2" />
                                    <label title="2 estrellas" for="rating-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                    </label>

                                    <input value="1" name="calificacion" type="radio" id="rating-1" />
                                    <label title="1 estrella" for="rating-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                    </label>
                                </div>
                            </div>

                            <button id="btn-publicar" type="submit" class="w-full md:w-auto bg-blue-600 dark:bg-gradient-to-r dark:from-[#3b82f6] dark:to-[#2563eb] hover:bg-blue-700 dark:hover:from-[#60a5fa] dark:hover:to-[#3b82f6] text-white dark:text-white font-black text-lg px-10 py-4 rounded-xl shadow-lg dark:shadow-[0_0_15px_rgba(59,130,246,0.3)] transform hover:-translate-y-1 transition-all duration-300">
                                <span id="txt-btn-publicar">Publicar Reseña</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-6">
                        <div class="w-20 h-20 mx-auto bg-slate-100 dark:bg-zinc-700 rounded-full flex items-center justify-center mb-6 shadow-inner">
                            <span class="text-4xl opacity-80">🔒</span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-gray-200 mb-3">Reseñas Bloqueadas</h3>
                        <p class="text-slate-500 dark:text-gray-400 font-medium max-w-md mx-auto leading-relaxed">
                            Para mantener la autenticidad de las opiniones, solo puedes dejar una reseña <strong>dentro de las 24 horas posteriores</strong> a una cita completada.
                        </p>
                        @guest
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="inline-block bg-slate-100 dark:bg-zinc-700 border border-slate-200 dark:border-zinc-600 hover:bg-slate-200 dark:hover:bg-zinc-600 text-slate-700 dark:text-gray-300 font-bold px-6 py-3 rounded-xl transition-all">
                                    Inicia sesión para opinar
                                </a>
                            </div>
                        @else
                            <div class="mt-6">
                                <a href="{{ route('appointments.index') }}" class="inline-block bg-blue-600 dark:bg-[#3b82f6] text-white font-black px-6 py-3 rounded-xl hover:bg-blue-700 transition-all shadow-md transform hover:-translate-y-0.5">
                                    Agendar una cita
                                </a>
                            </div>
                        @endguest
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
            @forelse($references as $ref)
                <div class="bg-white dark:bg-zinc-800 rounded-3xl p-6 border border-slate-200 dark:border-zinc-700 hover:border-blue-400 dark:hover:border-zinc-500 transition-all duration-300 group hover:-translate-y-1 shadow-md dark:shadow-xl">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-slate-100 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-full flex items-center justify-center text-blue-600 dark:text-[#3b82f6] font-black text-xl shadow-inner group-hover:bg-white dark:group-hover:bg-zinc-800 transition-colors">
                                {{ strtoupper(substr($ref->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-800 dark:text-gray-200 text-lg leading-tight">{{ $ref->user->name }}</p>
                                <p class="text-xs font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider mt-1">{{ $ref->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center bg-blue-50 dark:bg-blue-900/10 px-3 py-1.5 rounded-lg border border-blue-200 dark:border-blue-900/30">
                            <span class="text-blue-500 dark:text-[#3b82f6] mr-1 text-lg">★</span>
                            <span class="font-black text-blue-700 dark:text-blue-400">{{ $ref->calificacion }}</span>
                            <span class="text-blue-600/50 dark:text-blue-600 text-xs mt-1 ml-0.5 font-bold">/5</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 bg-slate-50 dark:bg-zinc-900/50 p-4 rounded-2xl border border-slate-100 dark:border-zinc-700">
                        @php
                            // 🛡️ DICCIONARIO ANTI-TROLLS CENSURA AUTOMÁTICA
                            $groserias = [
                                'pinche', 'puto', 'puta', 'pendejo', 'pendeja', 'mierda', 
                                'verga', 'cabron', 'cabrona', 'culo', 'idiota', 'estupido'
                            ];
                            $comentarioLimpio = str_ireplace($groserias, '*****', $ref->comentario);
                        @endphp
                        
                        <p class="text-slate-600 dark:text-gray-300 leading-relaxed font-medium italic">"{{ $comentarioLimpio }}"</p>
                        
                        {{-- 🛡️ BOTÓN DE ELIMINAR: Solo visible para el Barbero o Superadmin --}}
                        @if(auth()->check() && (auth()->user()->role === 'barbero' || auth()->user()->is_superadmin))
                            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700/50 flex justify-end">
                                <button type="button" 
                                        onclick="eliminarResena('{{ $ref->id }}')" 
                                        class="text-xs bg-red-500/10 text-red-500 border border-red-500/30 hover:bg-red-500 hover:text-white px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                    🗑️ Eliminar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 text-center py-20 border-2 border-dashed border-slate-300 dark:border-zinc-700 rounded-3xl bg-white dark:bg-zinc-800/50">
                    <div class="w-20 h-20 mx-auto bg-slate-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-6 shadow-inner border border-slate-200 dark:border-zinc-700">
                        <span class="text-4xl opacity-80">💬</span>
                    </div>
                    <h3 class="text-slate-500 dark:text-gray-300 text-2xl font-black mb-2">Aún no hay reseñas</h3>
                    
                    @if(auth()->check() && (auth()->user()->role === 'barbero' || auth()->user()->is_superadmin))
                        <p class="text-blue-600 dark:text-[#3b82f6] font-bold">Cuando tus clientes dejen una opinión, aparecerá aquí.</p>
                    @else
                        <p class="text-blue-600 dark:text-[#3b82f6] font-bold">¡Sé el primero en compartir tu experiencia!</p>
                    @endif
                </div>
            @endforelse
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 🛡️ LÓGICA ANTI-DEDO NERVIOSO Y VALIDACIÓN
    document.addEventListener('DOMContentLoaded', function () {
        const formResena = document.getElementById('form-resena');
        if(formResena) {
            const btnPublicar = document.getElementById('btn-publicar');
            const txtBtnPublicar = document.getElementById('txt-btn-publicar');

            formResena.addEventListener('submit', function(e) {
                const radios = document.getElementsByName('calificacion');
                let formValid = false;
                let i = 0;
                while (!formValid && i < radios.length) {
                    if (radios[i].checked) formValid = true;
                    i++;        
                }

                if (!formValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Calificación requerida',
                        text: 'Por favor, selecciona una calificación de estrellas antes de enviar.',
                        background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                    });
                    return false;
                }

                btnPublicar.disabled = true;
                btnPublicar.classList.add('opacity-70', 'cursor-wait');
                btnPublicar.classList.remove('hover:-translate-y-1', 'hover:bg-blue-700');
                txtBtnPublicar.innerText = 'Publicando... ⏳';
            });
        }
    });

    // 🗑️ LÓGICA DE ELIMINACIÓN DE RESEÑAS
    function eliminarResena(id) {
        Swal.fire({
            title: '¿Borrar esta reseña?',
            text: "La eliminarás permanentemente del sistema.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: document.documentElement.classList.contains('dark') ? '#27272a' : '#f1f5f9',
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar',
            background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/referencias/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: 'La reseña fue borrada de la base de datos.',
                            background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'No se pudo eliminar la reseña.',
                            background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Hubo un problema al intentar comunicarse con el servidor.',
                        background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
                    });
                });
            }
        });
    }
</script>

@endsection