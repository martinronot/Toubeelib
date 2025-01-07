<?php

declare(strict_types=1);

namespace toubeelib\application\middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use toubeelib\application\services\PraticienAuthzService;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;

class PraticienAuthzMiddleware implements MiddlewareInterface
{
    public function __construct(
        private PraticienAuthzService $authzService
    ) {}

    public function process(Request $request, RequestHandler $handler): Response
    {
        $auth = $request->getAttribute('auth');
        if (!$auth) {
            throw new HttpUnauthorizedException($request, 'Authentication required');
        }

        $praticienId = $request->getAttribute('id');
        if (!$praticienId) {
            throw new HttpBadRequestException($request, 'Praticien ID required');
        }

        // Vérifier l'autorisation selon la méthode HTTP
        $method = $request->getMethod();
        $authorized = match($method) {
            'GET' => $this->authzService->canAccessPraticien($auth, $praticienId),
            'PUT', 'PATCH' => $this->authzService->canModifyPraticien($auth, $praticienId),
            'DELETE' => $this->authzService->canDeletePraticien($auth, $praticienId),
            default => false
        };

        if (!$authorized) {
            throw new HttpForbiddenException($request, 'Access denied');
        }

        return $handler->handle($request);
    }
}
