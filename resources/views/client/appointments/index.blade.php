@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto p-6 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 shadow-lg">

    <h1 class="text-4xl font-bold text-center text-yellow-400 mb-6 tracking-wide">
        📅 Mis Citas
    </h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="bg-green-600/20 text-green-300 border border-green-500 p-3 mb-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Lista de citas --}}
    <ul class="space-y-4">
        @forelse($appointments as $appointment)
            <li class="p-4 bg-black/40 border border-gray-700 rounded-xl hover:border-yellow-400 transition">

                <p class="text-lg text-white">
                    <span class="font-bold text-yellow-400">Servicio:</span>
                    {{ $appointment->service }}
                </p>

                <p class="text-gray-300">
                    <span class="font-bold text-yellow-400">Fecha:</span>
                    {{ $appointment->appointment_date->format('d/m/Y H:i') }}
                </p>

                <p class="text-gray-300">
                    <span class="font-bold text-yellow-400">Estado:</span>
                    <span class="uppercase">{{ $appointment->status }}</span>
                </p>

            </li>
        @empty
            <li class="p-4 bg-black/40 border border-gray-700 rounded-xl text-center text-gray-300">
                No tienes citas registradas.
            </li>
        @endforelse
    </ul>

    {{-- Botón --}}
    <div class="text-center mt-6">
        <a href="{{ route('appointments.create') }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-6 py-2 rounded-full transition duration-300 shadow-lg">
            ➕ Nueva Cita
        </a>
    </div>

</div>

@endsection
