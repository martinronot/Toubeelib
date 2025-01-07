<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use toubeelib\application\middleware\CorsMiddleware;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php' );
$builder->addDefinitions(__DIR__ . '/dependencies.php');

$c = $builder->build();
$app = AppFactory::createFromContainer($c);

// Configuration CORS spécifique pour notre API
$corsSettings = [
    'allowedOrigins' => ['http://localhost:3000', 'https://toubeelib.fr'], // Origines autorisées
    'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
    'allowedHeaders' => ['X-Requested-With', 'Content-Type', 'Accept', 'Origin', 'Authorization'],
    'exposedHeaders' => ['Location'], // Important pour la création de ressources
    'maxAge' => 3600, // Cache des résultats preflight pendant 1 heure
    'allowCredentials' => true // Autoriser l'envoi des cookies
];

// Ajouter le middleware CORS avec la configuration
$app->add(new CorsMiddleware($corsSettings));

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false);

$app = (require_once __DIR__ . '/routes.php')($app);
$routeParser = $app->getRouteCollector()->getRouteParser();

return $app;