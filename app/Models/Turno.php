<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Turno extends Model
{
    protected $fillable = [
        'token',
        'cliente_id',
        'vehiculo_id',
        'problematica',
        'fecha_turno',
        'hora_turno',
        'estado',
        'fecha_ingreso_real',
    ];

    protected $casts = [
        'fecha_turno' => 'date',
        'fecha_ingreso_real' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Turno $turno) {
            if (empty($turno->token)) {
                $turno->token = static::generarTokenUnico();
            }
        });
    }

    public static function generarTokenUnico(): string
    {
        do {
            $token = Str::random(48);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    // Se completan en etapas posteriores del flujo (fuera del alcance de este módulo).
    public function ordenTrabajo(): HasOne
    {
        return $this->hasOne(OrdenTrabajo::class);
    }

    public function presupuesto(): HasOne
    {
        return $this->hasOne(Presupuesto::class);
    }

    /**
     * Fecha y hora combinadas del turno, como objeto Carbon.
     */
    public function getFechaHoraAttribute(): Carbon
    {
        return Carbon::parse($this->fecha_turno->format('Y-m-d') . ' ' . $this->hora_turno);
    }

    /**
     * Regla de negocio: el cliente puede cancelar/reprogramar hasta las 14 hs
     * del día anterior al turno.
     */
    public function getPuedeGestionarseAttribute(): bool
    {
        if ($this->estado !== 'reservado') {
            return false;
        }

        $limite = Carbon::parse($this->fecha_turno->format('Y-m-d'))
            ->subDay()
            ->setTime(14, 0);

        return now()->lessThanOrEqualTo($limite);
    }
}
