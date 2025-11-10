<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
<<<<<<< HEAD
=======
    /**
     * Run the migrations.
     *
     * @return void
     */
>>>>>>> 5d98d8a6a0cadc7fdc842dd338c9908a820fcd03
    public function up(): void
    {
        Schema::create('recordatorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_id')->constrained()->onDelete('cascade');
<<<<<<< HEAD
            $table->dateTime('fecha_vencimiento');
            $table->boolean('completado')->default(false);
=======
            $table->dateTime('fecha_vencimiento'); // Cambiado 'due_date' a 'fecha_vencimiento'
            $table->boolean('completado')->default(false); // Cambiado 'is_completed' a 'completado'
>>>>>>> 5d98d8a6a0cadc7fdc842dd338c9908a820fcd03
            $table->timestamps();
        });
    }

<<<<<<< HEAD
=======
    /**
     * Reverse the migrations.
     *
     * @return void
     */
>>>>>>> 5d98d8a6a0cadc7fdc842dd338c9908a820fcd03
    public function down(): void
    {
        Schema::dropIfExists('recordatorios');
    }
};