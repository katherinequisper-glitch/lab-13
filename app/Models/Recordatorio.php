<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recordatorio extends Model
{
    use HasFactory, SoftDeletes;
    
    // Indica que la tabla usa snake_case (recordatorios)
    protected $table = 'recordatorios';
    
    protected $fillable = [
        'nota_id',
        'fecha_vencimiento',
        'completado',
    ];

    /**
     * Define la relación 1:1 inversa con la Nota.
     */
    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}