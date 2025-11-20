<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recordatorio extends Model
{
    // QUITA ESTA LÍNEA si existe:
    // use SoftDeletes;
    
    protected $fillable = ['nota_id', 'fecha_vencimiento', 'completado'];
    
    public function nota(): BelongsTo
    {
        return $this->belongsTo(Nota::class);
    }
}