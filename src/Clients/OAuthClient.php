<?php

namespace MissaelAnda\MercadoPago\Clients;

use MissaelAnda\MercadoPago\Resources\OAuth;
use MissaelAnda\MercadoPago\Exceptions\MissingConfigurationException;
use Illuminate\Support\Str;

class OAuthClient extends Client
{
    protected const OAUTH_URL = 'https://auth.mercadopago.com.mx/authorization';

    protected const OAUTH_API = '/oauth/token';

    protected string $appId;

    protected string $appSecret;

    public function __construct(
        ?string $appId,
        ?string $appSecret,
        protected ?string $oauthRedirectUrl = null,
    ) {
        if (empty($appId)) {
            throw new MissingConfigurationException('The Mercado Pago App ID is required.');
        }

        if (empty($appSecret)) {
            throw new MissingConfigurationException('The Mercado Pago App Secret is required.');
        }

        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * @param  ?string  $verifyCode If PKCE is enabled this code is needed to prevent CSRF attacks
     * @param  ?string  $stateId The intent id to validate each try to oauth is unique
     */
    public function generateOAuthLink(
        ?string &$verifyCode = null,
        ?string &$stateId = null,
        bool $generateVerifyCode = false,
        string $challengeMethod = 'S256',
    ): string {
        $stateId = $stateId ?: Str::random(rand(20, 60));
        $baseUrl = self::OAUTH_URL . '?' . http_build_query([
            'client_id' => $this->appId,
            'response_type' => 'code',
            'platform_id' => 'mp',
        ]);
        $baseUrl .= "&state={$stateId}&redirect_uri={$this->getOauthRedirectUrl()}";

        if (empty($verifyCode) && !$generateVerifyCode) {
            return $baseUrl;
        }

        if (!$generateVerifyCode) {
            if (!preg_match('/^[a-zA-Z0-9_-]{43,128}$/', $verifyCode)) {
                throw new \InvalidArgumentException('The verify code must be RFC 7636 compliant.');
            }
        } else {
            $verifyCode = $this->generateVerifyCode();
        }

        $challenge = $this->generateCodeChallenge($verifyCode, $challengeMethod);

        $challengeMethod = strtoupper($challengeMethod);
        return "{$baseUrl}&code_challenge={$challenge}&code_challenge_method={$challengeMethod}";
    }

    public function createAccessToken(string $code, ?string $verifyCode = null): OAuth
    {
        $data = [
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getOauthRedirectUrl(),
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'code' => $code,
            // 'test_token' => true, // TODO
        ];

        if ($verifyCode) {
            $data['code_verifier'] = $verifyCode;
        }

        $data = $this->post(self::OAUTH_API, $data);
        return new OAuth($data);
    }

    public function refreshAccessToken(string $refreshToken): OAuth
    {
        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'refresh_token' => $refreshToken,
            // 'test_token' => true, // TODO
        ];

        $data = $this->post(self::OAUTH_API, $data);
        return new OAuth($data);
    }

    public function generateCodeChallenge(string $verifyCode, string $method = 'S256'): string
    {
        return match (strtolower($method)) {
            's256' => rtrim(strtr(base64_encode(hash('sha256', $verifyCode, true)), '+/', '-_'), '='),
            'plain' => $verifyCode,
            default => throw new \InvalidArgumentException('The allowed methods are S256 and PLAIN.'),
        };
    }

    public function generateVerifyCode(): string
    {
        return Str::random(rand(43, 128));
    }

    public function getOauthRedirectUrl(): string
    {
        if (empty($this->oauthRedirectUrl)) {
            throw MissingConfigurationException::make('oauth_redirect_url');
        }

        return $this->oauthRedirectUrl;
    }

    public function setOauthRedirectUrl(string $url): static
    {
        $this->oauthRedirectUrl = $url;

        return $this;
    }
}
