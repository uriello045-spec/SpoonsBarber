@extends('layouts.app')

@section('content')

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- TÍTULOS DE SECCIÓN --- */
    .section-title-wrapper {
        position: relative;
        display: inline-block;
        padding-bottom: 15px;
        margin-bottom: 3rem;
    }
    .section-title-wrapper::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: #ffd700;
        border-radius: 2px;
    }

    /* Efectos Glow y Tarjetas */
    .glow-wrapper {
        position: relative;
        z-index: 1;
        height: 100%;
    }
    .glow-bg {
        position: absolute;
        inset: -2px;
        border-radius: 1.5rem;
        filter: blur(15px);
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: -1;
    }
    .glow-wrapper:hover .glow-bg {
        opacity: 0.85;
        filter: blur(25px);
        animation: pulseGlow 2s infinite alternate;
    }
    
    .glow-card {
        position: relative;
        z-index: 2;
        background-color: #ffffff;
        transition: transform 0.4s ease, background-color 0.4s ease;
        border: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .dark .glow-card {
        background-color: #18181b;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .glow-wrapper:hover .glow-card {
        transform: translateY(-5px);
    }

    .glow-clasico { background: linear-gradient(to bottom, #d4af37, #b8860b); } 
    .glow-moderno { background: linear-gradient(to right, #984fff, #00ccff); } 
    .glow-extra { background: linear-gradient(to right, #ff7300, #ff0055); } 

    .jelly-button {
        padding: 15px 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 10px;
        transition: 0.5s;
        background: #1e1e1e;
        cursor: pointer;
        color: greenyellow;
        box-shadow: 0 0 10px #363636;
        display: inline-block;
        text-transform: uppercase;
        text-decoration: none;
    }

    .jelly-button:hover {
        background: #000;
        color: #fff;
        box-shadow: 0 0 15px greenyellow;
    }
    
    .btn-container {
        display: flex;
        justify-content: center;
        margin-top: 4rem;
        padding-bottom: 2rem;
    }

    .swal2-popup .swal2-input, .swal2-popup .swal2-textarea, .swal2-popup .swal2-select {
        color: #111827 !important; 
        background-color: #f9fafb !important;
    }

    html.dark .swal2-popup .swal2-input, html.dark .swal2-popup .swal2-textarea, html.dark .swal2-popup .swal2-select {
        color: #ffffff !important; 
        background-color: #374151 !important; 
    }
</style>

<div class="bg-slate-50 dark:bg-black transition-colors duration-300 min-h-screen pt-20 pb-20">
    
    <div class="container mx-auto px-4 text-center mb-16">
        <div class="section-title-wrapper" data-aos="zoom-in">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white">
                📖 Catálogo de Servicios
            </h1>
        </div>
        <p class="text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto" data-aos="fade-up">
            Explora nuestros estilos exclusivos y servicios premium para caballeros.
        </p>
    </div>

    @php
        $serviciosAMostrar = [];
        
        if(class_exists('\App\Models\Service')) {
            $serviciosDB = \App\Models\Service::all();
            
            foreach($serviciosDB as $s) {
                $numeroUnico = ($s->id % 28) + 1;
                $imagenOriginal = $s->imagen ?? '';
                
                // 🛡️ SOLUCIÓN: Rutas estables. Dinámica -> Storage::url / Estática -> asset puro
                $rutaLimpia = str_replace('public/', '', $imagenOriginal);
                $esDinamica = ($imagenOriginal && !str_contains($imagenOriginal, 'fake'));
                
                $rutaImagen = $esDinamica
                              ? Storage::url($rutaLimpia) 
                              : asset("img/galeria/corte_{$numeroUnico}.png");

                $serviciosAMostrar[] = [
                    'id' => $s->id,
                    'nombre' => $s->nombre,
                    'precio' => $s->precio,
                    'tiempo' => $s->duracion_minutos . ' min',
                    'cat' => $s->categoria,
                    'img' => $rutaImagen,
                    'ruta_limpia' => $rutaLimpia,
                    'desc' => $s->descripcion ?? 'Corte profesional de la casa.'
                ];
            }
        }
    @endphp

    <div class="container mx-auto px-4">
        
        <div class="flex justify-center gap-4 mb-10 flex-wrap" data-aos="fade-up">
            <span class="px-4 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">🟡 Clásicos</span>
            <span class="px-4 py-1 rounded-full text-xs font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">🔵 Modernos</span>
            <span class="px-4 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">🔥 Extras</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @if(auth()->check() && auth()->user()->role === 'barbero')
                <div data-aos="fade-up" class="cursor-pointer" onclick="agregarServicioNuevo()">
                    <div class="glow-wrapper h-full">
                        <div class="glow-bg glow-gold"></div>
                        <div class="glow-card rounded-2xl h-full border-2 border-dashed border-yellow-500 flex flex-col items-center justify-center bg-yellow-50 dark:bg-yellow-900/10 text-yellow-600 dark:text-yellow-400 transition hover:bg-yellow-100 dark:hover:bg-yellow-900/20 min-h-[400px]">
                            <span class="text-6xl mb-3">✂️<span class="text-4xl">+</span></span>
                            <span class="font-black text-lg text-center px-4">Agregar Nuevo Servicio</span>
                        </div>
                    </div>
                </div>
            @endif

            @foreach($serviciosAMostrar as $index => $s)
                @php
                    $fallbackCorte = asset('img/galeria/corte_' . (($s['id'] % 28) + 1) . '.png');
                @endphp
                <div id="card-servicio-{{ $s['id'] }}" data-aos="fade-up" data-aos-delay="{{ ($index % 10) * 50 }}">
                    <div class="glow-wrapper">
                        <div class="glow-bg {{ strtolower($s['cat']) === 'clásico' || strtolower($s['cat']) === 'clasico' ? 'glow-clasico' : (strtolower($s['cat']) === 'moderno' ? 'glow-moderno' : 'glow-extra') }}"></div>
                        
                        <div class="glow-card rounded-2xl h-full relative">
                            
                            @if(auth()->check() && auth()->user()->role === 'barbero')
                                <div class="absolute top-4 left-4 z-20">
                                    <button type="button" 
                                            data-id="{{ $s['id'] }}" 
                                            data-nombre="{{ $s['nombre'] }}" 
                                            onclick="eliminarServicio(this.getAttribute('data-id'), this.getAttribute('data-nombre'))"
                                            class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2.5 shadow-lg border border-red-400 transition transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            @endif

                            <div class="h-56 overflow-hidden relative z-10 bg-zinc-800">
                                {{-- 🛡️ ESCUDO: Si la dinámica falla, el fallback con asset puro la salva --}}
                                <img src="{{ $s['img'] }}" 
                                     onerror="this.onerror=null; this.src='{{ $fallbackCorte }}';"
                                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" 
                                     alt="{{ $s['nombre'] }}">
                                
                                <div class="absolute top-4 right-4 backdrop-blur-md font-bold px-3 py-1 rounded-full text-sm border bg-black/70 text-yellow-400 border-yellow-500/30">
                                    ${{ number_format($s['precio'], 2) }}
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-grow z-10">
                                <div class="mb-3">
                                    @if(strtolower($s['cat']) === 'clásico' || strtolower($s['cat']) === 'clasico')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-700/50 shadow-sm">🟡 Clásico</span>
                                    @elseif(strtolower($s['cat']) === 'moderno')
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-cyan-100 dark:bg-cyan-900/30 text-cyan-800 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-700/50 shadow-sm">🔵 Moderno</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400 border border-orange-200 dark:border-orange-700/50 shadow-sm">🔥 Extra</span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-slate-900 dark:text-white leading-tight mb-2">{{ $s['nombre'] }}</h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm mb-6 flex-grow leading-relaxed">{{ $s['desc'] }}</p>
                                
                                <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100 dark:border-zinc-800">
                                    <span class="text-xs font-semibold text-slate-500 flex items-center gap-1">⏱ {{ $s['tiempo'] }}</span>
                                    <a href="{{ route('appointments.index') }}" class="text-sm font-bold text-yellow-600 dark:text-yellow-500 hover:underline transition-colors">Reservar Ahora ➜</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="btn-container" data-aos="fade-up">
        <a href="{{ route('dashboard') }}" class="jelly-button">⬅ Volver al Inicio</a>
    </div>

</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ once: true, duration: 800, offset: 50 });

    async function agregarServicioNuevo() {
        const isDark = document.documentElement.classList.contains('dark');
        const { value: formValues } = await Swal.fire({
            title: '✂️ Agregar Nuevo Servicio',
            html: `
                <div style="text-align: left; margin-bottom: 5px;"><label style="font-size: 13px; color: gray;">Nombre del Servicio</label></div>
                <input id="swal-nombre" type="text" class="swal2-input" style="margin-top:0;" placeholder="Ej. Corte Clásico" minlength="3" maxlength="50">
                
                <div style="text-align: left; margin-bottom: 5px; margin-top: 15px;"><label style="font-size: 13px; color: gray;">Precio ($)</label></div>
                <input id="swal-precio" type="number" class="swal2-input" style="margin-top:0;" placeholder="Ej. 150" min="1" max="9999" step="0.5"
                       onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
                       oninput="if(this.value > 9999) this.value = 9999;">
                
                <div style="text-align: left; margin-bottom: 5px; margin-top: 15px;"><label style="font-size: 13px; color: gray;">Duración (Minutos)</label></div>
                <input id="swal-tiempo" type="number" class="swal2-input" style="margin-top:0;" placeholder="Ej. 45" value="45" min="30" max="60" step="5"
                       onkeydown="if(['e', 'E', '+', '-', '.'].includes(event.key)) event.preventDefault();"
                       oninput="if(this.value > 60) this.value = 60;">
                
                <div style="text-align: left; margin-bottom: 5px; margin-top: 15px;"><label style="font-size: 13px; color: gray;">Categoría</label></div>
                <select id="swal-cat" class="swal2-select" style="margin-top:0;">
                    <option value="Clásico">🟡 Clásico</option>
                    <option value="Moderno">🔵 Moderno</option>
                    <option value="Extra">🔥 Extra</option>
                </select>
                
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 5px; margin-top: 15px;">
                    <label style="font-size: 13px; color: gray;">Descripción (Opcional)</label>
                    <span id="char-count" style="font-size: 11px; color: #d4af37; font-weight: bold;">0 / 120</span>
                </div>
                <textarea id="swal-desc" class="swal2-textarea" style="margin-top:0; resize: none; height: 80px;" placeholder="Descripción breve..." maxlength="120"
                          onkeydown="if(['<', '>'].includes(event.key)) event.preventDefault();"
                          oninput="document.getElementById('char-count').innerText = this.value.length + ' / 120'; if(this.value.length > 120) this.value = this.value.substring(0, 120);"></textarea>
                
                <div style="text-align: left; margin-bottom: 5px; margin-top: 15px;"><label style="font-size: 13px; color: gray;">Foto (Opcional)</label></div>
                <input type="file" id="swal-foto" class="swal2-file" style="margin-top:0;" accept="image/png, image/jpeg, image/webp">
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d4af37',
            background: isDark ? '#111111' : '#ffffff',
            color: isDark ? '#ffffff' : '#0f172a',
            preConfirm: () => {
                const nombre = document.getElementById('swal-nombre').value.trim();
                const precio = parseFloat(document.getElementById('swal-precio').value);
                const duracion = parseInt(document.getElementById('swal-tiempo').value);
                const categoria = document.getElementById('swal-cat').value;
                const descripcion = document.getElementById('swal-desc').value.trim();
                const foto = document.getElementById('swal-foto').files[0];

                if (!nombre || nombre.length < 3 || nombre.length > 50) {
                    Swal.showValidationMessage('⚠️ El nombre es obligatorio (Entre 3 y 50 letras).');
                    return false;
                }
                
                if (!precio || isNaN(precio) || precio <= 0 || precio > 9999) {
                    Swal.showValidationMessage('⚠️ El precio debe ser un número real entre $1 y $9,999.');
                    return false;
                }
                
                if (!duracion || isNaN(duracion) || duracion < 30 || duracion > 60) {
                    Swal.showValidationMessage('⚠️ La duración debe ser mínimo 30 min y máximo 60 min.');
                    return false;
                }
                
                if (descripcion.length > 120) {
                    Swal.showValidationMessage('⚠️ La descripción es muy larga (Máximo 120 letras).');
                    return false;
                }

                return { nombre, precio, duracion, categoria, descripcion, foto };
            }
        });

        if (formValues && formValues.nombre) {
            Swal.fire({ title: 'Guardando...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            let formData = new FormData();
            formData.append('nombre', formValues.nombre);
            formData.append('precio', formValues.precio);
            formData.append('duracion', formValues.duracion);
            formData.append('categoria', formValues.categoria);
            formData.append('descripcion', formValues.descripcion);
            if (formValues.foto) formData.append('foto', formValues.foto);

            fetch('{{ route("admin.servicios.store") }}', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json' 
                },
                body: formData
            })
            .then(async res => {
                if (!res.ok) {
                    let err = await res.json();
                    if (err.errors) {
                        let mensajes = Object.values(err.errors).map(e => e.join('\n')).join('\n');
                        throw new Error(mensajes);
                    }
                    throw new Error(err.message || 'Error interno del servidor.');
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('¡Éxito!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'No se pudo guardar el servicio.', 'error');
                }
            })
            .catch((error) => {
                Swal.fire('🚨 Detalle del Error', error.message || 'Hubo un problema de conexión con el servidor.', 'error');
            });
        }
    }

    function eliminarServicio(id, nombre) {
        Swal.fire({
            title: '¿Eliminar Servicio?',
            text: `¿Estás seguro de borrar "${nombre}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Sí, eliminar',
            background: document.documentElement.classList.contains('dark') ? '#111' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/servicios/${id}`, {
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
                        let card = document.getElementById('card-servicio-' + id);
                        if (card) {
                            card.style.transition = "all 0.5s ease";
                            card.style.opacity = "0";
                            card.style.transform = "scale(0.8)";
                            setTimeout(() => card.remove(), 500);
                        }
                        Swal.fire('¡Eliminado!', data.message, 'success');
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Problema de red al eliminar.', 'error'));
            }
        });
    }
</script>

@endsection