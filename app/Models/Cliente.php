<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'nombre_apellido',
        'condicion_fiscal',
        'dni',
        'cuit',
        'razon_social',
        'condicion_iva',
        'telefono',
        'email',
    ];

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculo::class);
    }

    public function turnos(): HasMany
    {
        return $this->hasMany(Turno::class);
    }

    public function movimientosCuentaCorriente(): HasMany
    {
        return $this->hasMany(MovimientoCuentaCorriente::class);
    }
}
