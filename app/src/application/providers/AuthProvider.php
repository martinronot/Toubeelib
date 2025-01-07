<?php

declare(strict_types=1);

namespace toubeelib\application\providers;

use toubeelib\application\interfaces\IAuthService;
use toubeelib\application\dto\AuthDTO;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthProvider
{
    private string $jwtKey;
    private int $accessTokenExpiration;
    private int $refreshTokenExpiration;

    public function __construct(
        private IAuthService $authService,
        array $settings
    ) {
        $this->jwtKey = $settings['jwt']['key'];
        $this->accessTokenExpiration = $settings['jwt']['access_token_expiration'];
        $this->refreshTokenExpiration = $settings['jwt']['refresh_token_expiration'];
    }

    public function signin(string $email, string $password): AuthDTO
    {
        $authDTO = $this->authService->verifyCredentials($email, $password);

        // Générer l'access token
        $accessToken = JWT::encode([
            'sub' => $authDTO->id,
            'email' => $authDTO->email,
            'role' => $authDTO->role,
            'exp' => time() + $this->accessTokenExpiration
        ], $this->jwtKey, 'HS256');

        // Générer le refresh token
        $refreshToken = JWT::encode([
            'sub' => $authDTO->id,
            'exp' => time() + $this->refreshTokenExpiration
        ], $this->jwtKey, 'HS256');

        $authDTO->accessToken = $accessToken;
        $authDTO->refreshToken = $refreshToken;

        return $authDTO;
    }

    public function verifyToken(string $token): AuthDTO
    {
        try {
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));
            return new AuthDTO(
                $decoded->sub,
                $decoded->email,
                $decoded->role
            );
        } catch (\Exception $e) {
            throw new \RuntimeException('Invalid token');
        }
    }
}
