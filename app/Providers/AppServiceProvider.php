<?php

namespace App\Providers;

use App\Models\ConfiguracionCorreo;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->aplicarConfiguracionDeCorreo();
    }

    /**
     * Carga los datos SMTP cargados por Administración desde
     * /administracion/configuracion/correo y los aplica sobre la
     * configuración de mail de Laravel para este request. Así no hace
     * falta editar el .env del servidor cada vez que cambian los datos.
     */
    protected function aplicarConfiguracionDeCorreo(): void
    {
        try {
            // Evita romper comandos como "migrate" antes de que exista la tabla.
            if (! Schema::hasTable('configuracion_correo')) {
                return;
            }

            $config = ConfiguracionCorreo::query()->first();

            if (! $config || ! $config->estaConfigurado()) {
                return;
            }

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host', $config->mail_host);
            Config::set('mail.mailers.smtp.port', $config->mail_port ?: 587);
            Config::set('mail.mailers.smtp.username', $config->mail_username);
            Config::set('mail.mailers.smtp.password', $config->mail_password);
            Config::set('mail.mailers.smtp.encryption', $config->mail_encryption ?: null);

            if ($config->mail_from_address) {
                Config::set('mail.from.address', $config->mail_from_address);
                Config::set('mail.from.name', $config->mail_from_name ?: config('app.name'));
            }
        } catch (\Throwable $e) {
            // Si la base todavía no está lista (por ejemplo, durante el
            // primer deploy) simplemente no se aplica la configuración.
            report($e);
        }
    }
}
