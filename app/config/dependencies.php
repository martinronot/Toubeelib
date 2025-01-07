<?php

use Psr\Container\ContainerInterface;
use PDO;
use toubeelib\application\interfaces\IAuthService;
use toubeelib\application\services\AuthService;
use toubeelib\application\providers\AuthProvider;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\infrastructure\repositories\ArrayPraticienRepository;
use toubeelib\application\services\RendezVousService;
use toubeelib\application\services\PraticienService;
use toubeelib\application\interfaces\IPraticienService;
use toubeelib\infrastructure\repositories\ArrayRdvRepository;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;
use DI;

return [
    PDO::class => function (ContainerInterface $c) {
        $host = 'toubeelib.db';
        $dbname = 'toubeelib';
        $username = 'toubeelib';
        $password = 'toubeelib';
        
        return new PDO(
            "pgsql:host=$host;dbname=$dbname",
            $username,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    },

    IAuthService::class => function (ContainerInterface $c) {
        return new AuthService($c->get(PDO::class));
    },

    AuthProvider::class => function (ContainerInterface $c) {
        return new AuthProvider(
            $c->get(IAuthService::class),
            [
                'jwt' => [
                    'key' => 'secret',
                    'access_token_expiration' => 60 * 60, // 1 heure en secondes
                    'refresh_token_expiration' => 24 * 60 * 60 // 24 heures en secondes
                ]
            ]
        );
    },

    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien(
            new ArrayPraticienRepository()
        );
    },

    IPraticienService::class => function (ContainerInterface $c) {
        return new PraticienService(
            $c->get(ServicePraticienInterface::class)
        );
    },

    LoggerInterface::class => function (ContainerInterface $c) {
        $logger = new Logger('toubeelib');
        $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
        return $logger;
    },

    RendezVousService::class => function (ContainerInterface $c) {
        return new RendezVousService(
            new ArrayRdvRepository(),
            $c->get(IPraticienService::class),
            $c->get(LoggerInterface::class)
        );
    }
];