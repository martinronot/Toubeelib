<?php

declare(strict_types=1);

namespace toubeelib\application\actions\rdv;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubeelib\application\services\RendezVousService;
use Slim\Exception\HttpNotFoundException;

class GetRendezVousAction
{
    private RendezVousService $rdvService;

    public function __construct(RendezVousService $rdvService)
    {
        $this->rdvService = $rdvService;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? '';
        
        try {
            $rdv = $this->rdvService->getRendezVous($id);
            
            $data = [
                'rendez_vous' => [
                    'id' => $rdv->id,
                    'id_patient' => $rdv->idPatient,
                    'id_praticien' => $rdv->idPraticien,
                    'spécialité_praticien' => $rdv->specialitePraticien,
                    'lieu' => $rdv->lieu,
                    'horaire' => $rdv->horaire,
                    'type' => $rdv->type
                ],
                'links' => [
                    'self' => [
                        'href' => "/rdvs/{$rdv->id}/"
                    ],
                    'modifier' => [
                        'href' => "/rdvs/{$rdv->id}/"
                    ],
                    'annuler' => [
                        'href' => "/rdvs/{$rdv->id}/"
                    ],
                    'praticien' => [
                        'href' => "/praticiens/{$rdv->idPraticien}"
                    ],
                    'patient' => [
                        'href' => "/patients/{$rdv->idPatient}"
                    ]
                ]
            ];

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            throw new HttpNotFoundException($request, "Rendez-vous {$id} not found");
        }
    }
}
