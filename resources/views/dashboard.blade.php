@extends('layouts.app')

@section('content')

{{-- 🌟 EXTRAEMOS LOS DATOS DINÁMICOS DE LA BASE DE DATOS 🌟 --}}
@php
    function getSetting($key, $default) {
        if(class_exists('\App\Models\Setting')) {
            $setting = \App\Models\Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        }
        return $default;
    }

    // Horarios
    $h_semana = getSetting('horario_semana', '8:00 AM - 9:00 PM');
    $h_sabado = getSetting('horario_sabado', '8:00 AM - 9:00 PM');
    $h_domingo = getSetting('horario_domingo', '8:00 AM - 9:00 PM');

    $horaApertura = getSetting('hora_apertura', '08:00');
    $horaCierre = getSetting('hora_cierre', '21:00');

    // Horarios Reales (Relojes del sistema)
    $ap_semana = getSetting('apertura_semana', '08:00');
    $ci_semana = getSetting('cierre_semana', '21:00');
    $ce_semana = getSetting('cerrado_semana', 'false');

    $ap_sabado = getSetting('apertura_sabado', '08:00');
    $ci_sabado = getSetting('cierre_sabado', '21:00');
    $ce_sabado = getSetting('cerrado_sabado', 'false');

    $ap_domingo = getSetting('apertura_domingo', '08:00');
    $ci_domingo = getSetting('cierre_domingo', '21:00');
    $ce_domingo = getSetting('cerrado_domingo', 'false');

    // Precios base
    $p_corte = getSetting('precio_corte', '100');
    $p_barba = getSetting('precio_barba', '130');
    $p_ceja = getSetting('precio_ceja', '50');
    $p_greca = getSetting('precio_greca', '150');
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
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
        background: #3b82f6;
        border-radius: 2px;
    }

    /* ==========================================================
       🌟 EFECTO 2: AURA DE COLORES EN TARJETAS Y GALERÍA 🌟
       ========================================================== */
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

    @keyframes pulseGlow {
        0% { opacity: 0.6; filter: blur(20px); }
        100% { opacity: 1; filter: blur(30px); transform: scale(1.02); }
    }

    .glow-purple-cyan { background: linear-gradient(to right, #984fff, #00ccff); }
    .glow-yellow-orange { background: linear-gradient(to right, #ffd700, #ff7300); }
    .glow-green-cyan { background: linear-gradient(to right, #00ff88, #00aaff); }
    .glow-gold { background: linear-gradient(to bottom, #d4af37, #b8860b); }

    /* TARJETAS ADAPTABLES */
    .glow-card {
        position: relative;
        z-index: 2;
        background-color: #ffffff; 
        transition: transform 0.4s ease, background-color 0.4s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .dark .glow-card { 
        background-color: #2a2a2a; /* Tarjetón sutilmente más claro que el fondo para que resalte */
        border: 1px solid rgba(255,255,255,0.05);
    }
    .glow-wrapper:hover .glow-card {
        transform: translateY(-5px);
    }

    /* Galería */
    .masonry-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem; }
    .gallery-img-container {
        overflow: hidden; border-radius: 12px; height: 100%; width: 100%; aspect-ratio: 1 / 1;
        position: relative; z-index: 2; background: #eee; display: block;
    }
    .dark .gallery-img-container { background: #111; }
    .gallery-img-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
    .glow-wrapper:hover .gallery-img-container img { transform: scale(1.15); }

    /* ==========================================================
       🔥 EFECTO 3: BOTÓN "SUPER" (Para el Header) 🔥
       ========================================================== */
    .wrap {
        --radius: 30px; 
        --bg: #f9fafb; 
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; position: relative; text-decoration: none; cursor: pointer; margin-top: 20px;
    }
    .dark .wrap {
        --bg: #222222;
    }

    .wrap::before {
        content: ""; position: absolute; width: 350px; height: 150px; border-radius: 50px;
        background-color: rgba(0, 0, 0, 0.05); filter: blur(60px); transform: skewY(-10deg);
    }
    .dark .wrap::before { background-color: rgba(255, 255, 255, 0.05); }

    .wrap::after {
        content: ""; position: absolute; width: 100%; height: 100%; border-radius: 50px;
        background-color: rgba(255, 255, 255, 0.8); filter: blur(30px);
    }
    .dark .wrap::after { background-color: rgba(0, 0, 0, 0.5); }

    .button {
        position: relative; overflow: hidden; width: 280px; height: 80px; background-color: var(--bg);
        z-index: 2; border: transparent; border-radius: var(--radius);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.9), inset 0 -6px 1px -4px #3b82f6, inset 0 -15px 6px -8px #2563eb;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .dark .button {
        box-shadow: inset 0 1px 1px rgb(255 255 255 / 20%), inset 0 -6px 1px -4px #3b82f6, inset 0 -15px 6px -8px #2563eb;
    }

    .button::before {
        content: ""; position: absolute; inset: 0; border-radius: calc(var(--radius) * 0.9);
        border: 1px solid rgba(0,0,0,0.1); filter: blur(1px); transition: all 0.5s ease;
    }
    .dark .button::before { border: 1px solid rgba(255,255,255,0.1); }

    .button::after {
        content: ""; position: absolute; left: 0; right: 0; margin: auto; top: 101%;
        height: 30px; width: 180px; border-radius: 50px 50px 0 0; background: #3b82f6;
        filter: contrast(10) blur(7px); transition: all 0.3s ease; opacity: 1;
    }

    .button .corner { transition: all 0.4s ease; opacity: 0.1; }
    .button .corner::before, .button .corner::after {
        content: ""; position: absolute; top: 0; border-top: 35px solid white;
        border-left: 10px solid transparent; border-right: 10px solid transparent; filter: blur(4px);
    }
    .button .corner::before { left: 8px; transform: rotate(-40deg); }
    .button .corner::after { right: 8px; transform: rotate(40deg); }
    .wrap:hover .button .corner { opacity: 0.3; }

    .button .inner {
        z-index: 9; position: absolute; display: flex; align-items: center; justify-content: center;
        inset: 4px; 
        border-radius: calc(var(--radius) * 0.85); 
        background: linear-gradient(180deg, #ffffff 5%, #f3f4f6 100%);
        transition: all 0.4s ease; 
        box-shadow: inset 0 1px 2px rgba(255,255,255,0.8), 0 2px 4px rgba(0,0,0,0.05);
        font-family: sans-serif; font-weight: 900; font-size: 1.25rem; letter-spacing: 2px; color: #3b82f6; text-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
    }
    .dark .button .inner {
        background: linear-gradient(180deg, #1c1c1c 0%, #0a0a0a 100%);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.1), 0 2px 5px rgba(0,0,0,0.5);
        color: #60a5fa; text-shadow: 0 0 10px #2563eb;
    }

    .bg {
        background-color: #d1d5db; position: absolute; inset: -7px; border-radius: calc(var(--radius) * 1.25);
        box-shadow: 0 20px 10px -10px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; overflow: hidden; z-index: 1;
    }
    .dark .bg { background-color: #222222; box-shadow: 0 20px 10px -10px rgba(0, 0, 0, 0.3); }

    .bg::before {
        content: ""; position: absolute; border-radius: inherit; box-shadow: inset 0 -2px 0px -1px rgb(120 176 255 / 32%), inset 0 0 5px 1px rgba(0,0,0,0.1), inset 0 0 0 1px rgba(0,0,0,0.05); inset: 0; z-index: 1;
    }
    .dark .bg::before { box-shadow: inset 0 -2px 0px -1px rgb(120 176 255 / 32%), inset 0 0 5px 1px #222222, inset 0 0 0 1px #222222; }

    .bg .shine-1, .bg .shine-2::before {
        content: ""; position: absolute; z-index: 0; transition: all 0.3s ease; background: rgb(59, 130, 246); width: 10px; height: 10px;
        left: 0; right: 0; bottom: 0; margin: auto; border-radius: 50%; filter: blur(2px); transform: translateY(0) scale(0); animation: bg 2.4s linear infinite;
    }
    .bg .shine-2 { transition: all 0.5s linear; opacity: 0; }

    .led {
        position: absolute; z-index: 10; top: 100%; border-radius: 50%; width: 8px; height: 8px; margin-top: 15px; transition: all 0.4s ease;
        background-color: #3b82f6; box-shadow: 0 -10px 35px 17px #2563eb, inset 0 1px 2px 0px rgba(255, 255, 255, 0.6), 0 0 0px 3px rgb(0 0 0 / 60%), 0 0 2px 4px rgba(30, 58, 138, 0.8);
    }
    .noise {
        position: absolute; top: -20px; bottom: -20px; left: 0; right: 0; opacity: 0.04;
        mask-image: linear-gradient(transparent 5%, white 30%, white 70%, transparent 95%); filter: grayscale(1);
    }
    .dark .noise { opacity: 0.08; }

    /* --- Hover Animations Super Button --- */
    .wrap:hover .button { transform: scale(1.05); box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.6), inset 0 -6px 1px -4px #00ccff, inset 0 -15px 6px -8px #0077cc, 0 0 25px 5px rgba(0, 204, 255, 0.4); }
    .wrap:hover .button .inner { color: #00ccff; text-shadow: 0 0 15px #0077cc, 0 0 30px #00ccff; }
    .wrap:hover .button::after { background: transparent; }
    .wrap:hover .led { background-color: #00ccff; box-shadow: 0 -10px 35px 17px #00ccff, inset 0 1px 2px 0px rgba(255, 255, 255, 0.8), 0 0 0px 3px rgba(0, 204, 255, 0.6), 0 0 2px 4px rgba(0, 153, 255, 0.9); animation: ledPulse 1.5s infinite alternate; }
    .wrap:hover .bg .shine-2 { opacity: 1; }
    .wrap:hover .bg .shine-2::before { background: rgba(0, 204, 255, 0.9); filter: blur(4px); animation: bgHover 2s infinite linear; }

    @keyframes ledPulse { 0% { box-shadow: 0 -10px 35px 17px #00ccff, inset 0 1px 2px 0px rgba(255, 255, 255, 0.8), 0 0 0px 3px rgba(0, 204, 255, 0.6), 0 0 2px 4px rgba(0, 153, 255, 0.9); } 100% { box-shadow: 0 -10px 50px 25px #00ccff, inset 0 1px 2px 0px rgba(255, 255, 255, 1), 0 0 0px 4px rgba(0, 204, 255, 0.8), 0 0 3px 6px rgba(0, 153, 255, 1); } }
    @keyframes bgHover { 0% { transform: translateY(0) scale(0); } 50% { transform: translateY(-100px) scale(20); } 100% { transform: translateY(-200px) scale(15); } }
    @keyframes bg { 0% { transform: translateY(0) scale(0); } 12% { transform: translateY(0) scale(25); } 60%, 100% { transform: translateY(-200px) scale(20, 18); } }
    
    /* Active States */
    .wrap:active .button { transform: scale(0.98); filter: contrast(1.1); }
    .wrap:active .button::before { box-shadow: 0 -10px 10px 10px rgba(0,0,0,0.1); }
    .dark .wrap:active .button::before { box-shadow: 0 -10px 10px 10px #222222; }
    
    .wrap:active .button .inner {
        background: linear-gradient(180deg, #e5e7eb 5%, #d1d5db 100%);
        box-shadow: inset 0 -5px 15px -1px rgba(0, 0, 0, 0.1), inset 0 -4px 3px -3px rgba(0,0,0,0.2), inset 0 -10px 20px -8px rgb(255 255 255 / 50%), inset 0 1px 0 1px rgb(255 255 255 / 30%);
    }
    .dark .wrap:active .button .inner {
        background: linear-gradient(180deg, #0a0a0a 5%, #000000 100%);
        box-shadow: inset 0 -5px 15px -1px rgba(0, 0, 0, 0.3), inset 0 -4px 3px -3px black, inset 0 -10px 20px -8px rgb(255 255 255 / 20%), inset 0 1px 0 1px rgb(255 255 255 / 10%);
    }

    /* ==========================================================
       🧼 NUEVO EFECTO: BOTÓN JELLY / ELÁSTICO (Ver Catálogo) 🧼
       ========================================================== */
    .jelly-button {
        padding: 15px 30px;
        font-size: 18px;
        font-weight: bold;
        outline: none;
        border: none;
        border-radius: 10px;
        transition: 0.5s;
        background: #222222;
        cursor: pointer;
        color: #3b82f6;
        box-shadow: 0 0 10px #1a1a1a, inset 0 0 10px #1a1a1a;
        display: inline-block;
        letter-spacing: 1px;
    }

    .jelly-button:hover {
        animation: jelly-anim 0.5s 1 linear;
        background: #111827;
        color: #fff;
        box-shadow: 0 0 15px #3b82f6;
    }

    @keyframes jelly-anim {
        0% { transform: scale(0.7, 1.3); }
        25% { transform: scale(1.3, 0.7); }
        50% { transform: scale(0.7, 1.3); }
        75% { transform: scale(1.3, 0.7); }
        100% { transform: scale(1, 1); }
    }

    /* ==========================================================
       🛠️ CORRECCIÓN DE CAJAS DE TEXTO SWEETALERT (MODO OSCURO)
       ========================================================== */
    .swal2-popup .swal2-input, 
    .swal2-popup .swal2-textarea, 
    .swal2-popup .swal2-file,
    .swal2-popup .swal2-select {
        color: #111827 !important; 
        background-color: #f9fafb !important;
        border: 1px solid #d1d5db !important;
    }

    /* Reglas cuando el switch está en Modo Oscuro */
    html.dark .swal2-popup .swal2-input, 
    html.dark .swal2-popup .swal2-textarea, 
    html.dark .swal2-popup .swal2-file,
    html.dark .swal2-popup .swal2-select,
    .dark .swal2-popup .swal2-input, 
    .dark .swal2-popup .swal2-textarea, 
    .dark .swal2-popup .swal2-file {
        color: #ffffff !important; 
        background-color: #2a2a2a !important; 
        border: 1px solid #3f3f46 !important; 
    }

    .swal2-popup .swal2-input:focus, 
    .swal2-popup .swal2-textarea:focus,
    .swal2-popup .swal2-select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3) !important;
        outline: none !important;
    }

</style>

{{-- APLICACIÓN DEL FONDO GRIS OSCURO EXACTO (#222222) EN LAS SECCIONES --}}
<div class="relative overflow-hidden bg-slate-50 dark:bg-[#222222] transition-colors duration-300" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="absolute inset-0">
        {{-- 🛡️ FOTO ESTÁTICA: Usamos asset() puro porque debe ir en public/img/galeria/ --}}
        <img src="{{ asset('img/galeria/foto11.jpeg') }}" 
             class="w-full h-full object-cover opacity-20 dark:opacity-20 transition-opacity duration-300" 
             alt="Barber Shop">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-50 via-transparent to-transparent dark:from-[#222222] dark:via-[#222222]/10 dark:to-[#222222]/50 transition-colors duration-300"></div>
    </div>

    <div class="relative container mx-auto px-4 flex flex-col items-center text-center py-20" data-aos="fade-down">
        
        {{-- TÍTULO CORREGIDO: HTML PURO, SÓLIDO Y LIMPIO SIN ANIMACIONES QUE LO PIERDAN --}}
        <div class="mb-4 md:mb-6">
            <h1 class="text-6xl md:text-7xl lg:text-[5.5rem] font-black tracking-tight drop-shadow-sm">
                <span class="text-black dark:text-white transition-colors duration-300">Spoon’s</span> 
                <span class="text-blue-500 transition-colors duration-300">Barber Shop</span>
            </h1>
        </div>
        
        <p class="text-xl md:text-2xl mb-12 max-w-2xl mx-auto text-black dark:text-white font-medium">
            Estilo, precisión y una experiencia de lujo en cada corte.
        </p>
        
        @if(auth()->check() && auth()->user()->role === 'barbero')
            <a href="{{ route('admin.dashboard') }}" class="wrap">
                <button class="button">
                    <div class="corner"></div>
                    <div class="inner">VER CITAS</div>
                </button>
                <div class="led"></div>
                <div class="bg">
                    <div class="shine-1"></div>
                    <div class="shine-2"></div>
                </div>
                <div class="noise"></div>
            </a>
        @else
            <a href="{{ route('appointments.index') }}" class="wrap">
                <button class="button">
                    <div class="corner"></div>
                    <div class="inner">AGENDAR CITA</div>
                </button>
                <div class="led"></div>
                <div class="bg">
                    <div class="shine-1"></div>
                    <div class="shine-2"></div>
                </div>
                <div class="noise"></div>
            </a>
        @endif
    </div>
</div>

<section class="py-20 bg-slate-50 dark:bg-[#222222] transition-colors duration-300">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center">
            <div class="section-title-wrapper" data-aos="zoom-in">
                <h2 class="text-3xl md:text-4xl font-black tracking-wide text-black dark:text-white">💈 Nuestra Barbería</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 text-slate-900 dark:text-white">
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="glow-wrapper">
                    <div class="glow-bg glow-purple-cyan"></div>
                    <div class="glow-card h-full p-6 flex flex-col items-center text-center rounded-2xl shadow-sm transition-colors duration-300 relative">
                        <h4 class="text-2xl mb-4 font-bold text-blue-700 dark:text-blue-400">📍 Ubicación</h4>
                        <p class="mb-6 text-lg text-black dark:text-white">Av. Hermenegildo Galeana #150<br>Metepec, Estado de México</p>
                        <div class="w-full aspect-video rounded-lg overflow-hidden shadow-inner border border-slate-200 dark:border-zinc-700">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235.44550742892187!2d-99.5390056666833!3d19.233247339849026!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85cdf4ae632bbc23%3A0x25189c5fe553fc90!2sMoto%20Servicio%20%22El%20Chino%22!5e0!3m2!1ses!2smx!4v1771651726925!5m2!1ses!2smx" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="200">
                <div class="glow-wrapper">
                    <div class="glow-bg glow-yellow-orange"></div>
                    <div class="glow-card h-full p-8 text-center flex flex-col justify-center rounded-2xl shadow-sm transition-colors duration-300 relative">
                        
                        @if(auth()->check() && auth()->user()->role === 'barbero')
                            <button onclick="editarHorarios()" class="absolute top-4 right-4 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded border border-blue-300 hover:bg-blue-200 transition">✏️ Editar</button>
                        @endif

                        <h4 class="text-2xl mb-6 font-bold text-blue-700 dark:text-blue-400">🕒 Horarios</h4>
                        <div class="space-y-5 text-lg text-black dark:text-white">
                            <div><strong class="block text-blue-700 dark:text-blue-400 font-bold">Lunes – Viernes</strong><br><span id="txt_h_semana">{{ $h_semana }}</span></div>
                            <div><strong class="block text-blue-700 dark:text-blue-400 font-bold">Sábado</strong><br><span id="txt_h_sabado">{{ $h_sabado }}</span></div>
                            <div><strong class="block text-blue-700 dark:text-blue-400 font-bold">Domingo</strong><br><span id="txt_h_domingo">{{ $h_domingo }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div data-aos="fade-up" data-aos-delay="300">
                <div class="glow-wrapper">
                    <div class="glow-bg glow-green-cyan"></div>
                    <div class="glow-card h-full p-8 rounded-2xl shadow-sm transition-colors duration-300 relative">
                        
                        @if(auth()->check() && auth()->user()->role === 'barbero')
                            <button onclick="editarPrecios()" class="absolute top-4 right-4 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded border border-blue-300 hover:bg-blue-200 transition">✏️ Editar</button>
                        @endif

                        <h4 class="text-2xl text-center mb-8 font-bold text-blue-700 dark:text-blue-400">💵 Precios</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center border-b border-slate-100 dark:border-zinc-800 pb-2">
                                <span class="text-lg text-black dark:text-white">Cortes (Cualquiera)</span>
                                <span class="text-xl font-bold text-blue-700 dark:text-blue-400">$<span id="txt_p_corte">{{ $p_corte }}</span></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-100 dark:border-zinc-800 pb-2">
                                <span class="text-lg text-black dark:text-white">Corte + Barba</span>
                                <span class="text-xl font-bold text-blue-700 dark:text-blue-400">$<span id="txt_p_barba">{{ $p_barba }}</span></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-100 dark:border-zinc-800 pb-2">
                                <span class="text-lg text-black dark:text-white">Diseño de ceja</span>
                                <span class="text-xl font-bold text-blue-700 dark:text-blue-400">$<span id="txt_p_ceja">{{ $p_ceja }}</span></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-100 dark:border-zinc-800 pb-2">
                                <span class="text-lg text-black dark:text-white">Corte + Diseño (Greca)</span>
                                <span class="text-xl font-bold text-blue-700 dark:text-blue-400">$<span id="txt_p_greca">{{ $p_greca }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-slate-100 dark:bg-[#222222] transition-colors duration-300">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 relative">
             <div class="section-title-wrapper" data-aos="zoom-in">
                <h2 class="text-3xl md:text-4xl font-black tracking-wide text-black dark:text-white">📸 Galería de Cortes</h2>
            </div>
        </div>

        <div class="masonry-grid">
            
            @if(auth()->check() && auth()->user()->role === 'barbero')
                <div data-aos="fade-up" class="cursor-pointer" onclick="subirFotoGaleria()">
                    <div class="glow-wrapper h-full">
                        <div class="glow-bg glow-gold"></div>
                        <div class="gallery-img-container border-2 border-dashed border-[#3b82f6] flex flex-col items-center justify-center bg-blue-50 dark:bg-[#3b82f6]/10 text-blue-600 dark:text-[#3b82f6] transition hover:bg-blue-100 dark:hover:bg-[#3b82f6]/20">
                            <span class="text-6xl mb-3">📸<span class="text-4xl">+</span></span>
                            <span class="font-black text-lg">Subir Nueva Foto</span>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $fotosDB = [];
                if(class_exists('\App\Models\Gallery')) {
                    $fotosDB = \App\Models\Gallery::where('activa', true)->orderBy('created_at', 'desc')->get();
                }

                // 🛡️ FOTOS ESTÁTICAS: Usamos asset() puro, seguras en public/img/galeria
                $fotosOriginales = [
                    asset('img/galeria/foto0.jpeg'),
                    asset('img/galeria/foto2.jpeg'),
                    asset('img/galeria/foto3.jpeg'),
                    asset('img/galeria/foto4.jpeg'),
                    asset('img/galeria/foto5.jpeg'),
                    asset('img/galeria/foto6.jpeg'),
                    asset('img/galeria/foto7.jpeg'),
                    asset('img/galeria/foto8.jpeg'),
                    asset('img/galeria/foto9.jpeg'),
                    asset('img/galeria/foto10.jpeg'),
                ];
            @endphp

            {{-- FOTOS DE LA BASE DE DATOS (Las que sube el barbero usan Storage::url) --}}
            @foreach($fotosDB as $index => $foto)
                @php 
                    $imgLimpia = str_replace('public/', '', $foto->imagen);
                    $urlStorage = Storage::url($imgLimpia);
                @endphp
                <div id="div-foto-{{ $foto->id }}" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <div class="glow-wrapper">
                        <div class="glow-bg glow-gold"></div>
                        <div class="gallery-img-container border border-slate-200 dark:border-zinc-800 relative group">
                            
                            @if(auth()->check() && auth()->user()->role === 'barbero')
                                <button onclick="eliminarFotoGaleria('{{ $foto->id }}')" 
                                        class="absolute top-2 right-2 z-30 bg-red-600/90 hover:bg-red-700 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-lg border border-red-400"
                                        title="Eliminar Foto">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif

                            <a href="{{ $urlStorage }}" data-fancybox="gallery">
                                <img src="{{ $urlStorage }}" alt="Corte Barbería" loading="lazy">
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- FOTOS DE RELLENO SIEMPRE VISIBLES (Usan asset) --}}
            @foreach($fotosOriginales as $index => $img)
                <div data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <div class="glow-wrapper">
                        <div class="glow-bg glow-gold"></div>
                        <a href="{{ $img }}" data-fancybox="gallery" class="gallery-img-container border border-slate-200 dark:border-zinc-800">
                            <img src="{{ $img }}" alt="Corte Barbería" loading="lazy">
                        </a>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="flex justify-center mt-20" data-aos="fade-up">
            <a href="{{ route('catalogo') }}">
                <button class="jelly-button">
                    VER CATÁLOGO ✂️
                </button>
            </a>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({ once: true, duration: 800, offset: 50 });
    Fancybox.bind('[data-fancybox="gallery"]', {});

    async function editarHorarios() {
        const isDark = document.documentElement.classList.contains('dark');
        
        const ap_sem = "{{ $ap_semana }}"; 
        const ci_sem = "{{ $ci_semana }}"; 
        const ce_sem = "{{ $ce_semana }}";
        
        const ap_sab = "{{ $ap_sabado }}"; 
        const ci_sab = "{{ $ci_sabado }}"; 
        const ce_sab = "{{ $ce_sabado }}";
        
        const ap_dom = "{{ $ap_domingo }}"; 
        const ci_dom = "{{ $ci_domingo }}"; 
        const ce_dom = "{{ $ce_domingo }}";

        const { value: formValues } = await Swal.fire({
            title: '🕒 Editar Horarios',
            width: 600,
            html: `
                <div style="text-align: left; margin-bottom: 15px; background: ${isDark ? '#2a2a2a' : '#f8fafc'}; padding: 15px; border-radius: 10px; border: 1px solid ${isDark ? '#3f3f46' : '#e2e8f0'};">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label style="color: ${isDark ? '#3b82f6' : '#2563eb'}; font-size: 15px; font-weight: 900; text-transform: uppercase;">Lunes - Viernes</label>
                        <label style="font-size: 12px; color: #ff4444; font-weight: bold; cursor: pointer;">
                            <input type="checkbox" id="cerr_sem" ${ce_sem === 'true' ? 'checked' : ''} onchange="document.getElementById('ap_sem').disabled = this.checked; document.getElementById('ci_sem').disabled = this.checked;" style="accent-color: red;"> Cerrado
                        </label>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div style="flex: 1;">
                            <label style="font-size: 11px; color: gray; font-weight: bold;">Apertura</label>
                            <input type="time" id="ap_sem" class="swal2-input" style="margin:0; width: 100%; height: 38px; font-size: 14px;" value="${ap_sem}" ${ce_sem === 'true' ? 'disabled' : ''}>
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 11px; color: gray; font-weight: bold;">Cierre</label>
                            <input type="time" id="ci_sem" class="swal2-input" style="margin:0; width: 100%; height: 38px; font-size: 14px;" value="${ci_sem}" ${ce_sem === 'true' ? 'disabled' : ''}>
                        </div>
                    </div>
                </div>

                <div style="text-align: left; margin-bottom: 15px; background: ${isDark ? '#2a2a2a' : '#f8fafc'}; padding: 15px; border-radius: 10px; border: 1px solid ${isDark ? '#3f3f46' : '#e2e8f0'};">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label style="color: ${isDark ? '#3b82f6' : '#2563eb'}; font-size: 15px; font-weight: 900; text-transform: uppercase;">Sábado</label>
                        <label style="font-size: 12px; color: #ff4444; font-weight: bold; cursor: pointer;">
                            <input type="checkbox" id="cerr_sab" ${ce_sab === 'true' ? 'checked' : ''} onchange="document.getElementById('ap_sab').disabled = this.checked; document.getElementById('ci_sab').disabled = this.checked;" style="accent-color: red;"> Cerrado
                        </label>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div style="flex: 1;">
                            <label style="font-size: 11px; color: gray; font-weight: bold;">Apertura</label>
                            <input type="time" id="ap_sab" class="swal2-input" style="margin:0; width: 100%; height: 38px; font-size: 14px;" value="${ap_sab}" ${ce_sab === 'true' ? 'disabled' : ''}>
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 11px; color: gray; font-weight: bold;">Cierre</label>
                            <input type="time" id="ci_sab" class="swal2-input" style="margin:0; width: 100%; height: 38px; font-size: 14px;" value="${ci_sab}" ${ce_sab === 'true' ? 'disabled' : ''}>
                        </div>
                    </div>
                </div>

                <div style="text-align: left; margin-bottom: 15px; background: ${isDark ? '#2a2a2a' : '#f8fafc'}; padding: 15px; border-radius: 10px; border: 1px solid ${isDark ? '#3f3f46' : '#e2e8f0'};">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <label style="color: ${isDark ? '#3b82f6' : '#2563eb'}; font-size: 15px; font-weight: 900; text-transform: uppercase;">Domingo</label>
                        <label style="font-size: 12px; color: #ff4444; font-weight: bold; cursor: pointer;">
                            <input type="checkbox" id="cerr_dom" ${ce_dom === 'true' ? 'checked' : ''} onchange="document.getElementById('ap_dom').disabled = this.checked; document.getElementById('ci_dom').disabled = this.checked;" style="accent-color: red;"> Cerrado
                        </label>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div style="flex: 1;">
                            <label style="font-size: 11px; color: gray; font-weight: bold;">Apertura</label>
                            <input type="time" id="ap_dom" class="swal2-input" style="margin:0; width: 100%; height: 38px; font-size: 14px;" value="${ap_dom}" ${ce_dom === 'true' ? 'disabled' : ''}>
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 11px; color: gray; font-weight: bold;">Cierre</label>
                            <input type="time" id="ci_dom" class="swal2-input" style="margin:0; width: 100%; height: 38px; font-size: 14px;" value="${ci_dom}" ${ce_dom === 'true' ? 'disabled' : ''}>
                        </div>
                    </div>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar Ajustes',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3b82f6',
            background: isDark ? '#222222' : '#ffffff',
            color: isDark ? '#ffffff' : '#0f172a',
            preConfirm: () => {
                const c_sem = document.getElementById('cerr_sem').checked ? 'true' : 'false';
                const c_sab = document.getElementById('cerr_sab').checked ? 'true' : 'false';
                const c_dom = document.getElementById('cerr_dom').checked ? 'true' : 'false';

                const a_sem = document.getElementById('ap_sem').value; 
                const ci_s = document.getElementById('ci_sem').value;
                
                const a_sab = document.getElementById('ap_sab').value; 
                const ci_sa = document.getElementById('ci_sab').value;
                
                const a_dom = document.getElementById('ap_dom').value; 
                const ci_d = document.getElementById('ci_dom').value;

                if ((c_sem === 'false' && (!a_sem || !ci_s)) || 
                    (c_sab === 'false' && (!a_sab || !ci_sa)) || 
                    (c_dom === 'false' && (!a_dom || !ci_d))) {
                    Swal.showValidationMessage('Debes especificar las horas de apertura y cierre si el día no está marcado como "Cerrado".');
                    return false;
                }

                return { 
                    cerrado_semana: c_sem, apertura_semana: a_sem, cierre_semana: ci_s,
                    cerrado_sabado: c_sab, apertura_sabado: a_sab, cierre_sabado: ci_sa,
                    cerrado_domingo: c_dom, apertura_domingo: a_dom, cierre_domingo: ci_d
                }
            }
        });

        if (formValues) {
            guardarAjustes(formValues);
        }
    }

    async function editarPrecios() {
        const isDark = document.documentElement.classList.contains('dark');
        
        const { value: formValues } = await Swal.fire({
            title: '💵 Editar Precios Base',
            html: `
                <div style="text-align: left; margin-bottom: 10px;">
                    <label style="color: ${isDark ? '#ccc' : '#555'}; font-size: 14px; font-weight: bold;">Cortes (Cualquiera) $</label>
                    <input id="p_corte" type="number" min="1" max="9999" step="0.5" class="swal2-input" style="width: 90%; margin-top: 5px; box-sizing: border-box;" value="${document.getElementById('txt_p_corte').innerText}"
                           onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
                           oninput="if(this.value > 9999) this.value = 9999;">
                </div>
                <div style="text-align: left; margin-bottom: 10px;">
                    <label style="color: ${isDark ? '#ccc' : '#555'}; font-size: 14px; font-weight: bold;">Corte + Barba $</label>
                    <input id="p_barba" type="number" min="1" max="9999" step="0.5" class="swal2-input" style="width: 90%; margin-top: 5px; box-sizing: border-box;" value="${document.getElementById('txt_p_barba').innerText}"
                           onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
                           oninput="if(this.value > 9999) this.value = 9999;">
                </div>
                <div style="text-align: left; margin-bottom: 10px;">
                    <label style="color: ${isDark ? '#ccc' : '#555'}; font-size: 14px; font-weight: bold;">Diseño de Ceja $</label>
                    <input id="p_ceja" type="number" min="1" max="9999" step="0.5" class="swal2-input" style="width: 90%; margin-top: 5px; box-sizing: border-box;" value="${document.getElementById('txt_p_ceja').innerText}"
                           onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
                           oninput="if(this.value > 9999) this.value = 9999;">
                </div>
                <div style="text-align: left; margin-bottom: 10px;">
                    <label style="color: ${isDark ? '#ccc' : '#555'}; font-size: 14px; font-weight: bold;">Corte + Greca $</label>
                    <input id="p_greca" type="number" min="1" max="9999" step="0.5" class="swal2-input" style="width: 90%; margin-top: 5px; box-sizing: border-box;" value="${document.getElementById('txt_p_greca').innerText}"
                           onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
                           oninput="if(this.value > 9999) this.value = 9999;">
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3b82f6',
            background: isDark ? '#222222' : '#ffffff',
            color: isDark ? '#ffffff' : '#0f172a',
            preConfirm: () => {
                const pCorte = parseFloat(document.getElementById('p_corte').value);
                const pBarba = parseFloat(document.getElementById('p_barba').value);
                const pCeja = parseFloat(document.getElementById('p_ceja').value);
                const pGreca = parseFloat(document.getElementById('p_greca').value);

                if (isNaN(pCorte) || pCorte <= 0 || pCorte > 9999 || 
                    isNaN(pBarba) || pBarba <= 0 || pBarba > 9999 || 
                    isNaN(pCeja) || pCeja <= 0 || pCeja > 9999 || 
                    isNaN(pGreca) || pGreca <= 0 || pGreca > 9999) {
                    Swal.showValidationMessage('Todos los precios deben ser números válidos mayores a $0 y menores a $10,000.');
                    return false;
                }

                return { 
                    precio_corte: pCorte, 
                    precio_barba: pBarba, 
                    precio_ceja: pCeja, 
                    precio_greca: pGreca 
                }
            }
        });

        if (formValues) {
            guardarAjustes(formValues);
        }
    }

    function guardarAjustes(datos) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({ 
            title: 'Guardando...', 
            allowOutsideClick: false, 
            didOpen: () => { 
                Swal.showLoading(); 
            } 
        });

        fetch('{{ route("admin.settings.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json' 
            },
            body: JSON.stringify(datos)
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
                Swal.fire({ 
                    title: '¡Éxito!', 
                    text: data.message, 
                    icon: 'success', 
                    background: isDark ? '#222222' : '#ffffff', 
                    color: isDark ? '#ffffff' : '#0f172a' 
                }).then(() => location.reload()); 
            } else {
                Swal.fire('Error', data.message || 'No se pudo guardar.', 'error');
            }
        }).catch((error) => {
            Swal.fire('🚨 Detalle del Error', error.message || 'Hubo un problema de conexión.', 'error');
        });
    }

    async function subirFotoGaleria() {
        const isDark = document.documentElement.classList.contains('dark');
        
        const { value: file } = await Swal.fire({
            title: '📸 Subir Nueva Foto',
            input: 'file',
            inputAttributes: { 'accept': 'image/*' },
            showCancelButton: true,
            confirmButtonText: 'Subir Imagen',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3b82f6',
            background: isDark ? '#222222' : '#ffffff',
            color: isDark ? '#ffffff' : '#0f172a',
        });

        if (file) {
            let formData = new FormData();
            formData.append('foto', file);
            
            Swal.fire({ 
                title: 'Subiendo...', 
                allowOutsideClick: false, 
                didOpen: () => { 
                    Swal.showLoading(); 
                } 
            });

            fetch('{{ route("admin.galeria.store") }}', {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json' 
                },
                body: formData
            })
            .then(async res => {
                if (!res.ok) {
                    let err = await res.json();
                    throw new Error(err.message || 'Error interno del servidor.');
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({ 
                        title: '¡Éxito!', 
                        text: data.message, 
                        icon: 'success', 
                        background: isDark ? '#222222' : '#ffffff', 
                        color: isDark ? '#ffffff' : '#0f172a' 
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'No se pudo subir la foto.', 'error');
                }
            }).catch((error) => {
                Swal.fire('🚨 Detalle del Error', error.message || 'Hubo un problema al subir la foto.', 'error');
            });
        }
    }

    function eliminarFotoGaleria(idFoto) {
        const isDark = document.documentElement.classList.contains('dark');

        Swal.fire({
            title: '¿Eliminar Foto?',
            text: 'Esta acción borrará la imagen para siempre.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: isDark ? '#3f3f46' : '#cbd5e1',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: isDark ? '#222222' : '#ffffff',
            color: isDark ? '#ffffff' : '#0f172a',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/galeria/${idFoto}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let divFoto = document.getElementById('div-foto-' + idFoto);
                        if(divFoto) {
                            divFoto.style.transition = 'all 0.5s ease';
                            divFoto.style.opacity = '0';
                            divFoto.style.transform = 'scale(0.8)';
                            setTimeout(() => divFoto.remove(), 500);
                        }
                        Swal.fire({ 
                            title: '¡Eliminada!', 
                            text: data.message, 
                            icon: 'success', 
                            background: isDark ? '#222222' : '#ffffff', 
                            color: isDark ? '#ffffff' : '#0f172a' 
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo eliminar.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
            }
        });
    }
</script>

@endsection