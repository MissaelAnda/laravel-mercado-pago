<?php

namespace MissaelAnda\MercadoPago;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MissaelAnda\MercadoPago\Http\Controllers\WebhookController;
use MissaelAnda\MercadoPago\Http\Middleware\VerifyWebhookSignature;

class MercadoPagoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mercado-pago.php', 'mercado-pago');

        $this->app->singleton(MercadoPago::class);
        $this->app->singleton(DefaultTenant::class, function ($app) {
            $default = $app['config']->get('mercado-pago.default');
            return new DefaultTenant($default['user_id'], $default['access_token'], null, $default['public_key']);
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/mercado-pago.php' => config_path('mercado-pago.php'),
            ], 'config');
        }

        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        // Register Webhook
        $webhook = $this->app['config']->get('mercado-pago.webhook');
        if ($webhook['should_register']) {
            $route = Route::post($webhook['url_path'], WebhookController::class)
                ->name($webhook['route_name'])
                ->prefix($webhook['url_prefix']);
            $route->domain($webhook['subdomain']);

            if ($webhook['verify_signature']) {
                $route->middleware(VerifyWebhookSignature::class);
            }
        }

        // Register OAuth Callback
        $oauth = $this->app['config']->get('mercado-pago.oauth');
        $route = Route::get($oauth['url_path'], $oauth['controller'])
            ->name($oauth['route_name'])
            ->prefix($oauth['url_prefix']);
        $route->domain($oauth['subdomain']);
    }
}
