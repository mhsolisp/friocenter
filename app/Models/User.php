<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'permiso_ver_dia',
        'permiso_ver_dias_programados',
        'permiso_ver_historial',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permiso_ver_dia' => 'boolean',
            'permiso_ver_dias_programados' => 'boolean',
            'permiso_ver_historial' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    public function esAdministracion(): bool
    {
        return $this->rol === 'administracion';
    }

    public function esTaller(): bool
    {
        return $this->rol === 'taller';
    }

    /**
     * Administración siempre tiene acceso a todo lo que ve Taller,
     * más allá de los permisos puntuales que tenga marcados.
     */
    public function tienePermiso(string $permiso): bool
    {
        if ($this->esAdministracion()) {
            return true;
        }

        $campo = 'permiso_' . $permiso;

        return (bool) ($this->{$campo} ?? false);
    }
}
