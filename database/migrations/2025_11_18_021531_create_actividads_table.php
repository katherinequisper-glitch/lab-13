<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividads', function (Blueprint $table) {
            $table->id();
            // Clave foránea: se elimina en cascada si la nota es ELIMINADA PERMANENTEMENTE de la BD
            $table->foreignId('nota_id')->constrained('notas')->onDelete('cascade');
            $table->string('descripcion'); 
            $table->boolean('completada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividads');
    }
};
?>
