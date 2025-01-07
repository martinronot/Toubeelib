<?php

declare(strict_types=1);

namespace toubeelib\application\middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CorsMiddleware implements MiddlewareInterface
{
    private array $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = array_merge([
            'allowedOrigins' => ['*'],
            'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
            'allowedHeaders' => ['X-Requested-With', 'Content-Type', 'Accept', 'Origin', 'Authorization'],
            'exposedHeaders' => ['Location'],
            'maxAge' => 3600,
            'allowCredentials' => true
        ], $settings);
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        $origin = $request->getHeaderLine('Origin');

        // Vérifier si l'origine est autorisée
        if (in_array('*', $this->settings['allowedOrigins']) || in_array($origin, $this->settings['allowedOrigins'])) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $origin ?: '*')
                ->withHeader('Access-Control-Allow-Methods', implode(', ', $this->settings['allowedMethods']))
                ->withHeader('Access-Control-Allow-Headers', implode(', ', $this->settings['allowedHeaders']))
                ->withHeader('Access-Control-Expose-Headers', implode(', ', $this->settings['exposedHeaders']))
                ->withHeader('Access-Control-Max-Age', (string) $this->settings['maxAge']);

            if ($this->settings['allowCredentials']) {
                $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
            }

            // Gérer les requêtes OPTIONS (preflight)
            if ($request->getMethod() === 'OPTIONS') {
                return $response->withStatus(204);
            }
        }

        return $response;
    }
}
