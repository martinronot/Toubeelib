<?php

declare(strict_types=1);

namespace toubeelib\application\middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use toubeelib\application\providers\AuthProvider;
use Slim\Exception\HttpUnauthorizedException;

class JwtAuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthProvider $authProvider
    ) {}

    public function process(Request $request, RequestHandler $handler): Response
    {
        $authorization = $request->getHeaderLine('Authorization');

        if (empty($authorization)) {
            throw new HttpUnauthorizedException($request, 'Missing authorization header');
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $authorization, $matches)) {
            throw new HttpUnauthorizedException($request, 'Invalid authorization header format');
        }

        try {
            $token = $matches[1];
            $authDTO = $this->authProvider->verifyToken($token);
            
            // Ajouter le DTO d'authentification à la requête pour les middlewares suivants
            return $handler->handle($request->withAttribute('auth', $authDTO));
        } catch (\RuntimeException $e) {
            throw new HttpUnauthorizedException($request, 'Invalid token');
        }
    }
}
