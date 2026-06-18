@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-[#222222] py-20 transition-colors duration-300">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white dark:bg-[#2a2a2a] p-8 md:p-12 rounded-3xl shadow-xl border border-slate-200 dark:border-zinc-700">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4">Aviso de Privacidad</h1>
                <p class="text-cyan-600 dark:text-cyan-400 font-bold">Spoon's Barber Shop</p>
                <p class="text-sm text-slate-500 mt-2">Última actualización: {{ date('F Y') }}</p>
            </div>

            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 space-y-6">
                
                <p>En Spoon's Barber Shop valoramos profundamente tu confianza y nos comprometemos a proteger tu información personal. Este aviso detalla cómo recopilamos y usamos tus datos.</p>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-cyan-500 pb-2">1. Información que recopilamos</h3>
                <p>Al registrarte en nuestro sistema, recopilamos tu nombre completo, dirección de correo electrónico y un registro de las citas (fechas, horas y servicios solicitados) que agendas con nosotros. Si utilizas el inicio de sesión con Google, recibiremos tu nombre, correo e identificador público proporcionado por Google.</p>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-cyan-500 pb-2">2. Uso de la información</h3>
                <p>Tus datos son utilizados exclusivamente para:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Gestionar y confirmar tus citas en la barbería.</li>
                    <li>Enviarte recordatorios automáticos por correo electrónico 15 minutos antes de tu servicio.</li>
                    <li>Mantener un historial de tus cortes para brindarte un servicio más personalizado.</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-cyan-500 pb-2">3. Protección y Compartición de Datos</h3>
                <p>Tus contraseñas están fuertemente encriptadas en nuestra base de datos. <strong>Spoon's Barber Shop no vende, alquila ni comparte tu información personal con terceros</strong> bajo ninguna circunstancia, excepto cuando sea requerido por la ley.</p>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-cyan-500 pb-2">4. Tus Derechos</h3>
                <p>Tienes el derecho de solicitar la eliminación total de tu cuenta y tus datos de nuestro sistema en cualquier momento, contactando a nuestro personal directamente en la sucursal o a través de los medios oficiales de contacto.</p>

            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ url('/') }}" class="inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-8 rounded-full transition-transform transform hover:scale-105 shadow-md">Entendido</a>
            </div>
        </div>
    </div>
</div>
@endsection