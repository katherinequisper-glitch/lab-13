<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Nota;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create users
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
        // Create notes with reminders
        $note1 = Nota::create([
            'user_id' => $user1->id,
            'titulo' => 'Meeting Notes',
            'contenido' => 'Prepare for project meeting.',
        ]);
        $note2 = Nota::create([
            'user_id' => $user1->id,
            'titulo' => 'Grocery List',
            'contenido' => 'Buy milk and eggs.',
        ]);
        $note3 = Nota::create([
            'user_id' => $user2->id,
            'titulo' => 'Study Plan',
            'contenido' => 'Review Laravel Eloquent.',
        ]);
    }
}
