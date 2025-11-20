<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nota;
use App\Models\Recordatorio;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear usuarios
        $user1 = User::create([
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
        ]);
        $user2 = User::create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Crear notas con recordatorios asociados

        // Nota 1: Aparecerá (fecha futura)
        $note1 = Nota::create([
            'user_id' => $user1->id,
            'titulo' => 'Meeting Notes',
            'contenido' => 'Prepare for project meeting.',
        ]);
        $note1->recordatorio()->create([
            'fecha_vencimiento' => now()->addDays(2),
        ]);

        // Nota 2: Aparecerá (fecha futura)
        $note2 = Nota::create([
            'user_id' => $user1->id,
            'titulo' => 'Grocery List',
            'contenido' => 'Buy milk and eggs.',
        ]);
        $note2->recordatorio()->create([
            'fecha_vencimiento' => now()->addHours(5),
        ]);

        // Nota 3: NO aparecerá por el Alcance Global (fecha pasada y completado = true)
        $note3 = Nota::create([
            'user_id' => $user2->id,
            'titulo' => 'Study Plan',
            'contenido' => 'Review Laravel Eloquent.',
        ]);
        $note3->recordatorio()->create([
            'fecha_vencimiento' => now()->subDay(),
            'completado' => true,
        ]);
    }
}
