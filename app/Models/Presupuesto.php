<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presupuesto extends Model
{
    protected $fillable = [
        'turno_id',
        'usuario_id',
        'monto',
        'estado',
        'fecha_envio',
        'fecha_respuesta',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_envio' => 'datetime',
        'fecha_respuesta' => 'datetime',
    ];

    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
