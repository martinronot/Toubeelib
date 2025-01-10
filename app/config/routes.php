<?php
declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use toubeelib\application\actions\rdv\GetRendezVousAction;
use toubeelib\application\actions\rdv\UpdateRendezVousAction;
use toubeelib\application\actions\rdv\CreateRendezVousAction;
use toubeelib\application\actions\rdv\CancelRendezVousAction;
use toubeelib\application\actions\rdv\ListDisponibilitesAction;
use toubeelib\application\actions\auth\SigninAction;
use toubeelib\application\actions\auth\GetMeAction;
use toubeelib\application\actions\praticien\ListPraticiensAction;
use toubeelib\application\middleware\JwtAuthMiddleware;
use toubeelib\application\middleware\PraticienAuthzMiddleware;

return function( \Slim\App $app):\Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    // Route d'authentification
    $app->post('/signin', SigninAction::class);
    
    // Route pour récupérer les informations de l'utilisateur connecté
    $app->get('/me', GetMeAction::class)->add(JwtAuthMiddleware::class);

    // Routes pour les rendez-vous (protégées)
    $app->group('/rdvs', function($group) {
        $group->get('/{id}', GetRendezVousAction::class);
        $group->patch('/{id}', UpdateRendezVousAction::class);
        $group->post('', CreateRendezVousAction::class);
        $group->delete('/{id}', CancelRendezVousAction::class);
    })->add(JwtAuthMiddleware::class);
    
    // Routes pour les praticiens (protégées avec autorisation)
    $app->group('/praticiens', function($group) {
        $group->get('', ListPraticiensAction::class);
        $group->get('/{id}/disponibilites', ListDisponibilitesAction::class);
    })->add(JwtAuthMiddleware::class);

    return $app;
};