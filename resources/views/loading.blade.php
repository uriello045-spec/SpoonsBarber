<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciando Sistema...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { margin: 0; padding: 0; background-color: #050505; color: white; overflow: hidden; }
        
        .main-container {
            display: flex; justify-content: center; align-items: center;
            height: 100vh; width: 100vw;
        }
        .loader { width: 100%; max-width: 800px;}
        .trace-bg { stroke: #222; stroke-width: 1.8; fill: none; }
        
        .trace-flow {
            stroke-width: 2.5; fill: none;
            stroke-dasharray: 40 400; stroke-dashoffset: 438;
            filter: drop-shadow(0 0 6px currentColor);
            animation: flow 3s cubic-bezier(0.5, 0, 0.9, 1) infinite;
        }

        .yellow { stroke: #ffea00; color: #ffea00; }
        .blue { stroke: #00ccff; color: #00ccff; }
        .green { stroke: #00ff15; color: #00ff15; }
        .purple { stroke: #9900ff; color: #9900ff; }
        .red { stroke: #ff3300; color: #ff3300; }

        @keyframes flow { to { stroke-dashoffset: 0; } }
        
        /* 🌟 ANIMACIÓN PARA DESVANECER LA PANTALLA ANTES DE SALIR 🌟 */
        .fade-out { animation: fadeOut 0.5s forwards; }
        @keyframes fadeOut { to { opacity: 0; } }
    </style>
</head>
<body>

    <div class="main-container" id="loading-screen">
        <div class="loader">
            <svg viewBox="0 0 800 500" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="chipGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#2d2d2d"></stop>
                        <stop offset="100%" stop-color="#0f0f0f"></stop>
                    </linearGradient>
                    <linearGradient id="textGradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#d4af37"></stop>
                        <stop offset="100%" stop-color="#8a7220"></stop>
                    </linearGradient>
                    <linearGradient id="pinGradient" x1="1" y1="0" x2="0" y2="0">
                        <stop offset="0%" stop-color="#bbbbbb"></stop>
                        <stop offset="50%" stop-color="#888888"></stop>
                        <stop offset="100%" stop-color="#555555"></stop>
                    </linearGradient>
                </defs>

                <g id="traces">
                    <path d="M100 100 H200 V210 H296" class="trace-bg"></path>
                    <path d="M100 100 H200 V210 H296" class="trace-flow purple"></path>
                    <path d="M80 180 H180 V230 H296" class="trace-bg"></path>
                    <path d="M80 180 H180 V230 H296" class="trace-flow blue"></path>
                    <path d="M60 260 H150 V250 H296" class="trace-bg"></path>
                    <path d="M60 260 H150 V250 H296" class="trace-flow yellow"></path>
                    <path d="M100 350 H200 V270 H296" class="trace-bg"></path>
                    <path d="M100 350 H200 V270 H296" class="trace-flow green"></path>

                    <path d="M700 90 H560 V210 H504" class="trace-bg"></path>
                    <path d="M700 90 H560 V210 H504" class="trace-flow blue"></path>
                    <path d="M740 160 H580 V230 H504" class="trace-bg"></path>
                    <path d="M740 160 H580 V230 H504" class="trace-flow green"></path>
                    <path d="M720 250 H590 V250 H504" class="trace-bg"></path>
                    <path d="M720 250 H590 V250 H504" class="trace-flow red"></path>
                    <path d="M680 340 H570 V270 H504" class="trace-bg"></path>
                    <path d="M680 340 H570 V270 H504" class="trace-flow yellow"></path>
                </g>

                <rect x="300" y="190" width="200" height="100" rx="15" ry="15" fill="url(#chipGradient)" stroke="#d4af37" stroke-width="1.5" filter="drop-shadow(0 0 10px rgba(212,175,55,0.3))"></rect>

                <g>
                    <rect x="292" y="205" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                    <rect x="292" y="225" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                    <rect x="292" y="245" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                    <rect x="292" y="265" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                </g>

                <g>
                    <rect x="500" y="205" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                    <rect x="500" y="225" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                    <rect x="500" y="245" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                    <rect x="500" y="265" width="8" height="10" fill="url(#pinGradient)" rx="2"></rect>
                </g>

                <text x="400" y="235" font-family="system-ui, sans-serif" font-weight="900" font-size="18" fill="url(#textGradient)" text-anchor="middle" alignment-baseline="middle" letter-spacing="2">
                    SPOON'S
                </text>
                <text x="400" y="255" font-family="system-ui, sans-serif" font-weight="bold" font-size="12" fill="#888" text-anchor="middle" alignment-baseline="middle" letter-spacing="4">
                    BARBER SHOP
                </text>

                <circle cx="100" cy="100" r="4" fill="#333"></circle>
                <circle cx="80" cy="180" r="4" fill="#333"></circle>
                <circle cx="60" cy="260" r="4" fill="#333"></circle>
                <circle cx="100" cy="350" r="4" fill="#333"></circle>
                <circle cx="700" cy="90" r="4" fill="#333"></circle>
                <circle cx="740" cy="160" r="4" fill="#333"></circle>
                <circle cx="720" cy="250" r="4" fill="#333"></circle>
                <circle cx="680" cy="340" r="4" fill="#333"></circle>
            </svg>
        </div>
    </div>

    @php
        // 🌟 CALCULAMOS A DÓNDE IRÁ EL USUARIO EN PHP PURO 🌟
        // Si es barbero, la variable guarda el link del panel admin, si no, el del cliente.
        $rutaDestino = Auth::user()->role === 'barbero' ? route('admin.dashboard') : route('dashboard');
    @endphp

    <div id="redirect-data" data-url="{{ $rutaDestino }}" style="display: none;"></div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                document.getElementById('loading-screen').classList.add('fade-out');
                
                setTimeout(() => {
                    // Leemos la ruta desde el div invisible (Código JS 100% limpio)
                    const destinoUrl = document.getElementById('redirect-data').dataset.url;
                    window.location.href = destinoUrl;
                }, 500);

            }, 3000); 
        });
    </script>
</body>
</html>