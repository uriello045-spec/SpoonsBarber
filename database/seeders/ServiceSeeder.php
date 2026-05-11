<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $servicios = [
            // 🟡 CORTES CLÁSICOS (45 MINUTOS)
            ['nombre' => 'Corte Militar (Buzz Cut)', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_1.png', 'descripcion' => 'El más limpio y práctico. Todo al mismo nivel.'],
            ['nombre' => 'Corte César', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_2.png', 'descripcion' => 'Corto y texturizado con flequillo recto horizontal.'],
            ['nombre' => 'Crew Cut', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_3.png', 'descripcion' => 'El clásico casquete corto. Lados rapados y arriba ligeramente más largo.'],
            ['nombre' => 'Ivy League', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_4.png', 'descripcion' => 'Estilo universitario elegante, suficiente largo para peinar de lado.'],
            ['nombre' => 'Side Part (Raya Lateral)', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_5.png', 'descripcion' => 'Corte de caballero formal con línea divisoria marcada.'],
            ['nombre' => 'Pompadour Clásico', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_6.png', 'descripcion' => 'Volumen alto en el frente peinado hacia atrás.'],
            ['nombre' => 'Flat Top (Corte Plano)', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_7.png', 'descripcion' => 'Corte militar retro con la parte superior totalmente plana.'],
            ['nombre' => 'Taper Clásico', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_8.png', 'descripcion' => 'Limpieza de contornos y nuca sin rapado extremo.'],
            ['nombre' => 'Slick Back', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_9.png', 'descripcion' => 'Todo el cabello peinado hacia atrás con efecto húmedo.'],
            ['nombre' => 'Corte Princeton', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'clasico', 'imagen' => 'galeria/corte_10.png', 'descripcion' => 'Variante del Ivy League, limpio y profesional.'],

            // 🔵 CORTES MODERNOS (45 MINUTOS)
            ['nombre' => 'Fade (Desvanecido)', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_11.png', 'descripcion' => 'Degradado estándar de corto a largo.'],
            ['nombre' => 'Low Fade', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_12.png', 'descripcion' => 'Desvanecido bajo, cerca de las orejas y nuca.'],
            ['nombre' => 'Mid Fade', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_13.png', 'descripcion' => 'Desvanecido medio, el equilibrio perfecto.'],
            ['nombre' => 'High Fade', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_14.png', 'descripcion' => 'Desvanecido alto, contraste muy marcado.'],
            ['nombre' => 'Skin Fade', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_15.png', 'descripcion' => 'Desvanecido que termina en piel total (cero absoluto).'],
            ['nombre' => 'Undercut', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_16.png', 'descripcion' => 'Lados desconectados (muy cortos) y arriba largo.'],
            ['nombre' => 'Pompadour Moderno', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte-pompadour-moderno.webp', 'descripcion' => 'Como el clásico pero con fade en los lados.'],
            ['nombre' => 'Quiff', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_17.png', 'descripcion' => 'Copete con volumen y textura, estilo relajado.'],
            ['nombre' => 'Crop Francés', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_18.png', 'descripcion' => 'Texturizado arriba con flequillo corto y fade lateral.'],
            ['nombre' => 'Texturizado', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_19.png', 'descripcion' => 'Corte desordenado a propósito para dar movimiento.'],
            ['nombre' => 'Mohicano (Mohawk)', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_20.png', 'descripcion' => 'Cresta central con lados rapados.'],
            ['nombre' => 'Burst Fade', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_21.png', 'descripcion' => 'Desvanecido circular alrededor de la oreja.'],
            ['nombre' => 'Mullet Moderno', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_22.png', 'descripcion' => 'Corto adelante y a los lados, largo atrás.'],
            ['nombre' => 'Comb Over Fade', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_23.png', 'descripcion' => 'Peinado de lado moderno con desvanecido.'],
            ['nombre' => 'Taper Fade', 'precio' => 100, 'duracion_minutos' => 45, 'categoria' => 'moderno', 'imagen' => 'galeria/corte_24.png', 'descripcion' => 'Desvanecido solo en patillas y nuca.'],

            // 🔥 EXTRAS Y COMBOS (TIEMPOS DISTINTOS: 60 MIN Y 30 MIN)
            ['nombre' => 'Corte con Diseño (Greca)', 'precio' => 150, 'duracion_minutos' => 60, 'categoria' => 'extra', 'imagen' => 'galeria/corte_25.png', 'descripcion' => 'Corte completo + Figuras o líneas con navaja.'],
            ['nombre' => 'PAQUETE: Corte + Barba', 'precio' => 130, 'duracion_minutos' => 60, 'categoria' => 'extra', 'imagen' => 'galeria/corte_26.png', 'descripcion' => 'Servicio completo: Corte, perfilado de barba y toalla caliente.'],
            ['nombre' => 'Afeitado / Barba Sola', 'precio' => 60, 'duracion_minutos' => 30, 'categoria' => 'extra', 'imagen' => 'galeria/corte_27.png', 'descripcion' => 'Solo servicio de barba (delineado o afeitado total).'],
            ['nombre' => 'Diseño de Ceja', 'precio' => 50, 'duracion_minutos' => 15, 'categoria' => 'extra', 'imagen' => 'galeria/corte_28.png', 'descripcion' => 'Perfilado de ceja con navaja.'],
        ];

        foreach ($servicios as $servicio) {
            Service::updateOrCreate(
                ['nombre' => $servicio['nombre']], // Evita duplicados si ejecutas el comando dos veces
                $servicio
            );
        }
    }
}