<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RubroProveedor extends Model
{
    protected $table = 'rubros_proveedor';

    protected $fillable = ['nombre'];

    public function proveedores(): HasMany
    {
        return $this->hasMany(Proveedor::class, 'rubro_id');
    }
}
