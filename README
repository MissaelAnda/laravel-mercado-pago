# Mercado Pago Laravel API

The MercadoPago API with all the commodities for Laravel.

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [OAuth](#oauth)
- [Webhooks](#webhooks)
- [License](#license)

## Installation

```bash
composer require missael-anda/laravel-mercado-pago
```

## Configuration

If you want to simply use a single default tenant you will need to define the `MERCADO_PAGO_USER_ID`, `MERCADO_PAGO_ACCESS_TOKEN` and `MERCADO_PAGO_PUBLIC_KEY`.

If you want to manage other users account with a MercadoPago application you will need to provide the `MERCADO_PAGO_APP_ID` and `MERCADO_PAGO_APP_SECRET`.

You can publish the full [configuration file](config/mercado-pago.php) with the following command:

```bash
php artisan vendor:publish --provider="MissaelAnda\MercadoPago\MercadoPagoServiceProvider" --tag=config
```

## Usage

You can get the default tenant via the `MercadoPago::default()` method or directly to all the features, if you want to provide multiple tenants you will need to implement the [`MercadoPagoTenant`](src/Contracts/MercadoPagoTenant.php) interface and pass it to the `MercadoPago::tenant($tenant)` function or pass it to the service.

### Services

- [x] `oauth()` (Does not require a tenant)
- [x] `stores()`
- [x] `pos()`
- [x] `points()`
- [x] `payments()`
- [x] `me()`

## OAuth

If you wish to manage multiple tenants you have to implement the [authorization flow](https://www.mercadopago.com.mx/developers/es/docs/subscriptions/additional-content/security/oauth/creation) you will need to provide a oauth redirect ult in the configuration, you can also turn on the PKCE.

You can create the authorization link for the client with `MercadoPago::oauth()->generateOAuthLink()`. When successfully authorized the user will be redirected to the configured url, this will trigger the `OAuthCallbackReceived` event which contains the `code` and the `state` which you can use to generate the access token with the `MercadoPago::oauth()->createAccessToken()` function. You can refresh the token with the `refresh_token` and the `MercadoPago::oauth()->refreshAccessToken()`.

## Webhooks

[Webhooks](https://www.mercadopago.com.mx/developers/en/docs/your-integrations/notifications/webhooks) are automatically handled and verified, these will fire the `WebhookReceived` event and the `PointIntegrationEvent` or the `WebhookEvent` depending on the event itself.

If you want to disable the webhook or the signature verification you can do so in the configuration file.

## Missing features

- CheckoutPro
- CheckoutApi
- CheckoutBricks
- PaymentLinks
- Subscriptions
- WalletConnect
- SplitPayments

## License

This project is licensed under the [MIT License](LICENSE).
