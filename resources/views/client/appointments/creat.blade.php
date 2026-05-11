@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto p-6 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-lg">

    <h1 class="text-4xl font-bold text-center text-yellow-400 mb-6 tracking-wide">
        ✂️ Nueva Cita
    </h1>

    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        {{-- Servicio --}}
        <div class="mb-5">
            <label for="service" class="block text-yellow-400 font-semibold mb-1">Servicio</label>
            <select name="service" id="service"
                class="w-full p-3 bg-black/50 text-white border border-gray-600 rounded-xl focus:border-yellow-400 focus:ring-yellow-400 transition"
                required>
                <option class="text-black" value="Corte de cabello">Corte de cabello</option>
                <option class="text-black" value="Afeitado">Afeitado</option>
                <option class="text-black" value="Degradado">Degradado</option>
                <option class="text-black" value="Corte + Barba">Corte + Barba</option>
                <option class="text-black" value="Arreglo de Cejas">Arreglo de Cejas</option>
            </select>
        </div>

        {{-- Fecha y hora --}}
        <div class="mb-5">
            <label for="appointment_date" class="block text-yellow-400 font-semibold mb-1">Fecha y Hora</label>

            <input type="datetime-local"
                name="appointment_date"
                id="appointment_date"
                class="w-full p-3 bg-black/50 text-white border border-gray-600 rounded-xl focus:border-yellow-400 focus:ring-yellow-400 transition"
                required>
        </div>

        {{-- Botón --}}
        <button type="submit"
            class="w-full bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 rounded-full shadow-lg transition transform hover:scale-105">
            Reservar Cita ✨
        </button>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="mt-4 bg-red-600/20 border border-red-500 text-red-300 p-3 rounded-xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </form>

</div>

{{-- SCRIPT PARA BLOQUEAR FECHAS PASADAS --}}
<script>
    const inputFecha = document.getElementById('appointment_date');
    const ahora = new Date().toISOString().slice(0, 16);
    inputFecha.min = ahora;
</script>

@endsection
