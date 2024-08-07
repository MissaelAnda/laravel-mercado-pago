<?php

namespace MissaelAnda\MercadoPago\Resources;

/**
 * @method static setUserId(int $userId)
 * @method static setExpiresIn(int $expiresIn)
 * @method static setLiveMode(bool $liveMode)
 * @method static setScope(string $scope)
 * @method static setTokenType(string $tokenType)
 * @method static setPublicKey(string $publicKey)
 * @method static setAccessToken(string $accessToken)
 * @method static setRefreshToken(string $refreshToken)
 */
class OAuth extends Resource
{
    public int $userId;
    public int $expiresIn;
    public bool $liveMode;
    public string $scope;
    public string $tokenType;
    public string $publicKey;
    public string $accessToken;
    public string $refreshToken;
}
