<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nota_id',
        'fecha_vencimiento',
        'completado'
    ];

    /**
     * Relación: Recordatorio pertenece a una nota (belongsTo).
     */
    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}