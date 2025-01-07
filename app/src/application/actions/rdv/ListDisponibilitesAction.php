<?php

declare(strict_types=1);

namespace toubeelib\application\actions\rdv;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubeelib\application\services\RendezVousService;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpBadRequestException;
use DateTime;

class ListDisponibilitesAction
{
    private RendezVousService $rdvService;

    public function __construct(RendezVousService $rdvService)
    {
        $this->rdvService = $rdvService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $praticienId = $args['id'] ?? '';
        
        // Récupérer les paramètres de requête optionnels
        $queryParams = $request->getQueryParams();
        $dateDebut = $queryParams['dateDebut'] ?? date('Y-m-d');
        $dateFin = $queryParams['dateFin'] ?? date('Y-m-d', strtotime('+7 days'));
        
        try {
            $debutDateTime = new DateTime($dateDebut);
            $finDateTime = new DateTime($dateFin);
            
            $disponibilites = $this->rdvService->getDisponibilites($praticienId, $debutDateTime, $finDateTime);
            
            $data = [
                'praticien_id' => $praticienId,
                'date_debut' => $debutDateTime->format('Y-m-d'),
                'date_fin' => $finDateTime->format('Y-m-d'),
                'disponibilites' => $disponibilites,
                'links' => [
                    'self' => [
                        'href' => "/praticiens/{$praticienId}/disponibilites?dateDebut={$dateDebut}&dateFin={$dateFin}"
                    ],
                    'praticien' => [
                        'href' => "/praticiens/{$praticienId}"
                    ]
                ]
            ];

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            throw new HttpNotFoundException($request, "Praticien {$praticienId} non trouvé");
        }
    }
}
