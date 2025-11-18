<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota_id', 
        'descripcion', 
        'completada'
    ];

    /**
     * Relación: Una Actividad pertenece a una Nota (∞ -> 1)
     */
    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}