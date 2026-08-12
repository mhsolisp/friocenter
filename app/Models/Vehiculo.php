<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    protected $fillable = [
        'cliente_id',
        'patente',
        'modelo_id',
        'vehiculo_otro',
        'anio',
        'color',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(Modelo::class);
    }

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class);
    }

    /**
     * Nombre legible del vehículo, ya sea desde el selector o desde "Otro".
     */
    public function getDescripcionAttribute(): string
    {
        if ($this->vehiculo_otro) {
            return $this->vehiculo_otro;
        }

        return trim(($this->modelo?->marca?->nombre ?? '') . ' ' . ($this->modelo?->nombre ?? ''));
    }
}
