<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log; // Asegúrate de importar Log si lo usas.

class Nota extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['user_id', 'titulo', 'contenido'];

    // =======================================================
    // MÉTODO ÚNICO BOOTED (Global Scope y Eventos de Modelo)
    // =======================================================
    protected static function booted()
    {
        // 1. Alcance global ('activa'): Solo mostrará notas con recordatorios activos (futuros y no completados).
        static::addGlobalScope('activa', function (Builder $builder) {
            $builder->whereHas('recordatorio', function ($query) {
                $query->where('fecha_vencimiento', '>=', now())->where('completado', false);
            });
        });
        
        // 2. Evento 'deleting' para Borrado en Cascada (Soft Delete)
        // Se ejecuta ANTES del Soft Delete de la Nota.
        static::deleting(function (Nota $nota) {
            // Elimina el Recordatorio relacionado (hasOne)
            $nota->recordatorio()->delete(); 
            
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
    
    // Relación 1:1 (hasOne)
    public function recordatorio()
    {
        return $this->hasOne(Recordatorio::class);
    }
    
    // Relación 1:∞ (hasMany) - ¡Nueva relación para Actividades!
    public function actividads()
    {
        return $this->hasMany(Actividad::class);
    }

    // Accesor: Formatear título con estado [cite: 241, 73]
    public function getTituloFormateadoAttribute()
    {
        // Asume que siempre hay recordatorio debido al Global Scope/lógica de creación.
        if ($this->recordatorio) {
            return $this->recordatorio->completado ? "[Completado] {$this->titulo}" : $this->titulo;
        }
        return $this->titulo;
    }
}