@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-zinc-900 py-20 transition-colors duration-300">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white dark:bg-zinc-800 p-8 md:p-12 rounded-3xl shadow-xl border border-slate-200 dark:border-zinc-700">
            
            <div class="text-center mb-10">
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mb-4">Términos y Condiciones</h1>
                <p class="text-blue-600 dark:text-[#3b82f6] font-bold">Spoon's Barber Shop</p>
                <p class="text-sm text-slate-500 mt-2">Última actualización: {{ date('F Y') }}</p>
            </div>

            <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 space-y-6">
                
                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-blue-500 pb-2">1. Uso del Servicio</h3>
                <p>Bienvenido al sistema de reservas de Spoon's Barber Shop. Al registrarte y utilizar nuestra plataforma, aceptas cumplir con estos términos. El servicio está diseñado exclusivamente para agendar, gestionar y consultar citas para servicios de barbería en nuestra sucursal física.</p>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-blue-500 pb-2">2. Política de Citas y Cancelaciones</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Puntualidad:</strong> Te pedimos llegar con 5 minutos de anticipación. Hay una tolerancia máxima de 10 minutos; pasado este tiempo, la cita podrá ser cancelada para no afectar a los siguientes clientes.</li>
                    <li><strong>Cancelaciones:</strong> Si no puedes asistir, por favor cancela tu cita desde tu panel de usuario con al menos 2 horas de anticipación.</li>
                    <li><strong>Penalizaciones:</strong> La acumulación de citas no asistidas sin cancelación previa podrá resultar en la suspensión temporal o definitiva de tu cuenta en el sistema.</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-blue-500 pb-2">3. Cuentas de Usuario</h3>
                <p>Eres responsable de mantener la confidencialidad de tu contraseña y de todas las actividades que ocurran bajo tu cuenta. Nos reservamos el derecho de rechazar el servicio o cancelar cuentas si detectamos un uso fraudulento o malintencionado del sistema.</p>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white border-b border-blue-500 pb-2">4. Modificaciones</h3>
                <p>Spoon's Barber Shop se reserva el derecho de modificar precios, horarios de apertura y estos términos de servicio en cualquier momento. Los cambios se verán reflejados inmediatamente en la plataforma.</p>

            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ url('/') }}" class="inline-block bg-blue-600 hover:bg-blue-700 dark:bg-[#3b82f6] dark:hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-full transition-transform transform hover:scale-105 shadow-md">Regresar al Inicio</a>
            </div>
        </div>
    </div>
</div>
@endsection