@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-black py-20 transition-colors duration-300">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white dark:bg-zinc-900 p-8 md:p-12 rounded-3xl shadow-xl border border-slate-200 dark:border-zinc-800">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4">Política de Cookies</h1>
                <p class="text-orange-600 dark:text-orange-500 font-bold">Spoon's Barber Shop</p>
            </div>

            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 space-y-6">
                <p>Nuestra plataforma utiliza un número mínimo y estrictamente necesario de "cookies" (pequeños archivos de texto que se guardan en tu navegador) para garantizar el correcto funcionamiento del sistema de reservas.</p>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-orange-500 pb-2">¿Para qué usamos cookies?</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Cookies de Sesión (Estrictamente necesarias):</strong> Nos permiten recordar que has iniciado sesión para que no tengas que poner tu correo y contraseña cada vez que vas a agendar o cancelar una cita.</li>
                    <li><strong>Cookies de Seguridad (CSRF):</strong> Protegen los formularios de registro y reserva contra ataques malintencionados en la web.</li>
                    <li><strong>Preferencias visuales:</strong> Nos ayuda a recordar si prefieres el "Modo Oscuro" o el "Modo Claro" para que la página siempre se vea a tu gusto.</li>
                </ul>

                <p class="mt-6 text-sm text-slate-500 italic">Nota: No utilizamos cookies de rastreo publicitario ni vendemos tu historial de navegación a terceros. Puedes borrar las cookies en cualquier momento desde la configuración de tu navegador, aunque esto cerrará tu sesión activa en nuestro sistema.</p>
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ url('/') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-full transition-transform transform hover:scale-105">Aceptar y Regresar</a>
            </div>
        </div>
    </div>
</div>
@endsection