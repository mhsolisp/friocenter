<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Presupuesto extends Model
{
    protected $fillable = [
        'turno_id',
        'usuario_id',
        'numero_ejercicio',
        'numero_correlativo',
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

    /**
     * Número completo tal como se imprime en el papel, ej. "924825-0729".
     * Si todavía no se le asignó número (presupuestos viejos, previos a
     * este módulo) devuelve null.
     */
    public function getNumeroCompletoAttribute(): ?string
    {
        if (! $this->numero_ejercicio || ! $this->numero_correlativo) {
            return null;
        }

        return $this->numero_ejercicio.'-'.str_pad((string) $this->numero_correlativo, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Código de ejercicio anual del taller (septiembre a agosto) para una
     * fecha dada. Ej.: cualquier fecha entre 01/09/2024 y 31/08/2025
     * devuelve "924825".
     */
    public static function ejercicioParaFecha(Carbon $fecha): string
    {
        $anio = (int) $fecha->format('y'); // año de 2 dígitos
        $mes = (int) $fecha->format('n');

        if ($mes >= 9) {
            $inicio = $anio;
            $fin = $anio + 1;
        } else {
            $inicio = $anio - 1;
            $fin = $anio;
        }

        return sprintf('9%02d8%02d', $inicio % 100, $fin % 100);
    }

    /**
     * Asigna de forma atómica el próximo número correlativo dentro del
     * ejercicio vigente para $fecha, y lo deja cargado en el modelo (no
     * guarda por sí solo: se usa antes de save()/create()).
     *
     * Usa un lock a nivel de fila para que, si dos presupuestos se cargan
     * al mismo tiempo, no terminen con el mismo número.
     */
    public function asignarNumero(?Carbon $fecha = null): void
    {
        $fecha = $fecha ?: now();
        $ejercicio = static::ejercicioParaFecha($fecha);

        $siguiente = DB::transaction(function () use ($ejercicio) {
            $ultimo = static::where('numero_ejercicio', $ejercicio)
                ->lockForUpdate()
                ->max('numero_correlativo');

            return ($ultimo ?? 0) + 1;
        });

        $this->numero_ejercicio = $ejercicio;
        $this->numero_correlativo = $siguiente;
    }
}
