<?php

declare(strict_types=1);

namespace toubeelib\application\actions\auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubeelib\application\providers\AuthProvider;
use Slim\Exception\HttpBadRequestException;

class SigninAction
{
    public function __construct(
        private AuthProvider $authProvider
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Valider les données
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new HttpBadRequestException($request, 'Missing credentials');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new HttpBadRequestException($request, 'Invalid email format');
        }

        try {
            $authDTO = $this->authProvider->signin($data['email'], $data['password']);
            
            $responseData = [
                'access_token' => $authDTO->accessToken,
                'refresh_token' => $authDTO->refreshToken,
                'user' => [
                    'id' => $authDTO->id,
                    'email' => $authDTO->email,
                    'role' => $authDTO->role
                ]
            ];

            $response->getBody()->write(json_encode($responseData));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\RuntimeException $e) {
            throw new HttpBadRequestException($request, 'Invalid credentials');
        }
    }
}
