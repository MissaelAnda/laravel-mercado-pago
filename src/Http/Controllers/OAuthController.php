<?php

namespace MissaelAnda\MercadoPago\Http\Controllers;

use Illuminate\Http\Request;
use MissaelAnda\MercadoPago\Events\OAuthCallbackReceived;

class OAuthController
{
    public function __invoke(Request $request)
    {
        OAuthCallbackReceived::dispatch($request->query('code'), $request->query('state'));

        if ($view = config('mercado-pago.oauth.view')) {
            return view($view);
        }

        if ($redirect = config('mercado-pago.oauth.redirect')) {
            return redirect($redirect);
        }

        return response();
    }
}
