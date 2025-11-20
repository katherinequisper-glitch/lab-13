<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class Nota extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['user_id', 'titulo', 'contenido'];

    // =======================================================
    // MÉTODO ÚNICO BOOTED (Eventos de Modelo)
    // =======================================================
    protected static function booted()
    {
        // Evento 'deleting' para Borrado en Cascada (Soft Delete)
        // Solo mantiene la lógica para eliminar actividades.
        static::deleting(function (Nota $nota) {
            
            // Elimina todas las Actividades relacionadas (hasMany)
            $nota->actividads()->delete(); 
        });
    }

    // =======================================================
    // RELACIONES
    // =======================================================
    
    // Relación 1:∞ (belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function recordatorio() 
    {
        return $this->hasOne(Recordatorio::class);
    }
    // Relación 1:∞ (hasMany) - Mantenida para Actividades
    public function actividads()
    {
        return $this->hasMany(Actividad::class);
    }

    // Accesor: Retorna solo el título (Ya no calcula el estado de completado)
    public function getTituloFormateadoAttribute()
    {
        return $this->titulo;
    }
}