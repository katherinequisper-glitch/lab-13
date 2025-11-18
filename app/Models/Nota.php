<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Recordatorio; 
use App\Models\Actividad;
use Illuminate\Support\Facades\Log; 

class Nota extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['user_id', 'titulo', 'contenido'];

    protected static function booted()
    {
        // 1. Alcance global ('activa')
        static::addGlobalScope('activa', function (Builder $builder) {
            $builder->whereHas('recordatorio', function ($query) {
                $query->where('fecha_vencimiento', '>=', now())->where('completado', false);
            });
        });
        
        // 2. Lógica de Borrado en Cascada (Evento 'deleting')
        // Se ejecuta ANTES del Soft Delete de la Nota.
        static::deleting(function (Nota $nota) {
            // Elimina el Recordatorio (Soft Delete, si el modelo lo usa, o Hard Delete)
            $nota->recordatorio()->delete(); 
            
            // Elimina todas las Actividades (Hard Delete, ya que el modelo Actividad no usa Soft Deletes)
            $nota->actividads()->delete(); 
        });
    }
    
    // Accesor: Formatear título con estado (Ya debe existir)
    public function getTituloFormateadoAttribute()
    {
        // Usamos isset() o una comprobación para evitar errores si no hay recordatorio
        if ($this->recordatorio) {
            return $this->recordatorio->completado ? "[Completado] {$this->titulo}" : $this->titulo;
        }
        return $this->titulo;
    }
    
    // Relación: Nota pertenece a un usuario (Ya debe existir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relación: Nota tiene un recordatorio (Ya debe existir)
    public function recordatorio()
    {
        return $this->hasOne(Recordatorio::class);
    }
    
    // Relación: Nota tiene muchas actividades (NUEVO)
    public function actividads()
    {
        return $this->hasMany(Actividad::class);
    }
}