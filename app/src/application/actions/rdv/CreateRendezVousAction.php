<?php

declare(strict_types=1);

namespace toubeelib\application\actions\rdv;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use toubeelib\application\services\RendezVousService;
use toubeelib\application\dto\RendezVousDTO;
use Slim\Exception\HttpBadRequestException;

class CreateRendezVousAction
{
    private RendezVousService $rdvService;

    public function __construct(RendezVousService $rdvService)
    {
        $this->rdvService = $rdvService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        try {
            // Vérifier les données requises
            if (!isset($data['id_patient']) || !isset($data['id_praticien']) || 
                !isset($data['specialite_praticien']) || !isset($data['lieu']) || 
                !isset($data['horaire']) || !isset($data['type'])) {
                throw new HttpBadRequestException($request, 'Données manquantes pour la création du rendez-vous');
            }

            // Créer le DTO
            $rdvDTO = new RendezVousDTO();
            $rdvDTO->idPatient = $data['id_patient'];
            $rdvDTO->idPraticien = $data['id_praticien'];
            $rdvDTO->specialitePraticien = $data['specialite_praticien'];
            $rdvDTO->lieu = $data['lieu'];
            $rdvDTO->horaire = $data['horaire'];
            $rdvDTO->type = $data['type'];

            // Créer le rendez-vous
            $rdv = $this->rdvService->createRendezVous($rdvDTO);

            $responseData = [
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

            $response = $response->withHeader('Location', "/rdvs/{$rdv->id}/");
            $response->getBody()->write(json_encode($responseData));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (HttpBadRequestException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage());
        }
    }
}
