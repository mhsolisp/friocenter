<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'razon_social',
        'cuit',
        'condicion_fiscal',
        'telefono',
        'email',
        'rubro_id',
        'direccion',
        'observaciones',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function rubro(): BelongsTo
    {
        return $this->belongsTo(RubroProveedor::class, 'rubro_id');
    }
}
