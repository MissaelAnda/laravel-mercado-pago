<?php

return [
    /**
     * The application ID
     *
     * Required if you are using oauth authorization
     */
    'app_id' => env('MERCADO_PAGO_APP_ID'),

    /**
     * The application Secret
     *
     * Required for oauth authorization
     */
    'app_secret' => env('MERCADO_PAGO_APP_SECRET'),

    /**
     * Default tenant variables
     */
    'default' => [
        /**
         * The tenant id
         */
        'user_id' => env('MERCADO_PAGO_USER_ID'),

        /**
         * The access token
         */
        'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),

        /**
         * The public key required for CheckOut Pro
         */
        'public_key' => env('MERCADO_PAGO_PUBLIC_KEY'),
    ],

    /**
     * Webhook keys
     */
    'webhook' => [
        /**
         * Wether the webhook route should be active/registered
         */
        'should_register' => env('MERCADO_PAGO_WEBHOOK_ENABLED', true),

        /**
         * The webhook subdomain (null for none)
         */
        'subdomain' => null,

        /**
         * The url prefix (null for none)
         */
        'url_prefix' => 'mercado-pago',

        /**
         * Url name for `route` function generation (required)
         */
        'route_name' => 'mercado-pago.webhook',

        /**
         * The webhook final path (required)
         *
         * The acual url will be <scheme>://<subdomain>.APP_URL/<prefix>/<url_path>
         */
        'url_path' => env('MERCADO_PAGO_WEBHOOK_PATH', 'webhook'),

        /**
         * The webhook secret required for signature verification
         */
        'secret' => env('MERCADO_PAGO_WEBHOOK_SECRET'),

        /**
         * Wether webhook events' signature should be verified before processing them
         */
        'verify_signature' => env('MERCADO_PAGO_WEBHOOK_VERIFY', true),
    ],

    /**
     * OAuth callback redirect url
     */
    'oauth' => [
        /**
         * OAuth callback subdomain (null for none)
         */
        'subdomain' => null,

        /**
         * OAuth callback url prefix (null for none)
         */
        'url_prefix' => 'mercado-pago',

        /**
         * OAuth route name (required)
         */
        'route_name' => 'mercado-pago.oauth.callback',

        /**
         * OAuth callback url path (required)
         *
         * The final url will be <scheme>://<subdomain>.APP_URL/<prefix>/<url_path>
         * Must match the redirect url placed in the dashboard
         * https://www.mercadopago.com.mx/developers/panel/app/<YOUR_APP_ID>/edit-app
         */
        'url_path' => env('MERCADO_PAGO_OAUTH_CALLBACK_PATH', 'oauth/callback'),

        /**
         * The controller that handles the successful redirect after mercado pago authorizes
         */
        'controller' => \MissaelAnda\MercadoPago\Http\Controllers\OAuthController::class,

        /**
         * A custom view to render when the client has successfully authorized the application
         */
        'view' => env('MERCADO_PAGO_AUTHORIZED_VIEW'),

        /**
         * A route or url to redirect when the client has successfully authorized the application
         */
        'redirect' => env('MERCADO_PAGO_AUTHORIZED_REDIRECT'),
    ],
];
