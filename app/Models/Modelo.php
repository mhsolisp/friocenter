<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modelo extends Model
{
    protected $fillable = ['marca_id', 'nombre'];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }
}
