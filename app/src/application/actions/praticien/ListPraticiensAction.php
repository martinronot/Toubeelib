<?php

declare(strict_types=1);

namespace toubeelib\application\actions\praticien;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubeelib\core\services\praticien\ServicePraticienInterface;

class ListPraticiensAction
{
    public function __construct(
        private ServicePraticienInterface $praticienService
    ) {}

    public function __invoke(Request $request, Response $response): Response
    {
        $praticiens = $this->praticienService->getAllPraticiens();
        
        $response->getBody()->write(json_encode([
            'praticiens' => $praticiens
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
