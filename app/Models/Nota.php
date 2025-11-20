<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nota extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'titulo',
        'contenido',
    ];

    // ===============================
    // BOOTED (solo para eliminaciones)
    // ===============================
    protected static function booted()
    {
        // Cuando se elimina una nota, se eliminan también sus actividades y recordatorio
        static::deleting(function (Nota $nota) {

            // Eliminar recordatorio si existe
            if ($nota->recordatorio) {
                $nota->recordatorio->delete();
            }

            // Eliminar actividades relacionadas
            if ($nota->actividads()->exists()) {
                $nota->actividads()->delete();
            }
        });
    }

    // ===============================
    // RELACIONES
    // ===============================

    // Una nota pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Una nota tiene un recordatorio
    public function recordatorio()
    {
        return $this->hasOne(Recordatorio::class);
    }

    // Una nota tiene muchas actividades
    public function actividads()
    {
        return $this->hasMany(Actividad::class);
    }

    // Accesor opcional para mostrar título formateado
    public function getTituloFormateadoAttribute()
    {
        $isCompleted = $this->recordatorio?->completado;

        return $isCompleted
            ? "[Completado] {$this->titulo}"
            : $this->titulo;
    }
}
