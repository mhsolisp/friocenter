<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';

    protected $fillable = [
        'turno_id',
        'usuario_id',
        'tomada_at',
        'tareas',
        'repuestos',
        'tiempo_estimado',
    ];

    protected $casts = [
        'tomada_at' => 'datetime',
    ];

    public function estaTomada(): bool
    {
        return ! is_null($this->tomada_at);
    }

    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
