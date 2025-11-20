<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder; // Necesario para el Alcance Global

class Nota extends Model
{
    use HasFactory, SoftDeletes; // Habilitar eliminaciones lógicas
    
    protected $fillable = ['user_id', 'titulo', 'contenido'];

    // =======================================================
    // MÉTODO BOOTED (Alcance Global y Eventos de Modelo)
    // =======================================================
    protected static function booted()
    {
        // 1. ALCANCE GLOBAL (REQUISITO LAB 13)
        // Solo mostrará notas "activas" (recordatorio futuro y no completado).
        static::addGlobalScope('activa', function (Builder $builder) {
            $builder->whereHas('recordatorio', function ($query) {
                $query->where('fecha_vencimiento', '>=', now())->where('completado', false);
            });
        });

        // 2. EVENTO DELETING (REQUISITO LAB 14)
        // Al realizar Soft Delete en la Nota, elimina en cascada sus relaciones.
        static::deleting(function (Nota $nota) {
            
            // Elimina el Recordatorio asociado (hasOne)
            $nota->recordatorio()->delete(); 

            // Elimina todas las Actividades relacionadas (hasMany)
            $nota->actividads()->delete(); 
        });
    }

    // =======================================================
    // RELACIONES
    // =======================================================
    
    // Relación 1:∞ Inversa (belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relación 1:1 (hasOne) - CRUCIAL para Lab 13 y el Global Scope
    public function recordatorio() 
    {
        return $this->hasOne(Recordatorio::class);
    }

    // Relación 1:∞ (hasMany) - Requisito Lab 14
    public function actividads()
    {
        // Asumiendo que el modelo se llama 'Actividad' (singular)
        return $this->hasMany(Actividad::class);
    }

    // =======================================================
    // ACCESORES
    // =======================================================

    // Accesor: Retorna el título formateado (Requisito Lab 13)
    public function getTituloFormateadoAttribute()
    {
        // Verifica si la relación recordatorio existe y si está completado
        return $this->recordatorio && $this->recordatorio->completado 
            ? "[Completado] {$this->titulo}" 
            : $this->titulo;
    }
}
