<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionCorreo extends Model
{
    protected $table = 'configuracion_correo';

    protected $fillable = [
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    protected $hidden = [
        'mail_password',
    ];

    /**
     * Siempre hay un único registro de configuración (id=1). Se crea la
     * primera vez que se pide, si todavía no existe.
     */
    public static function actual(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    public function estaConfigurado(): bool
    {
        return ! empty($this->mail_host) && ! empty($this->mail_username) && ! empty($this->mail_password);
    }
}
