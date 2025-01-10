<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
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

return [
    'PDO' => function (ContainerInterface $c) {
        $settings = $c->get('settings');
        $dbSettings = $settings['db'];
        
        return new \PDO(
            "pgsql:host={$dbSettings['host']};dbname={$dbSettings['dbname']}",
            $dbSettings['user'],
            $dbSettings['password'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    },

    IAuthService::class => function (ContainerInterface $c) {
        return new AuthService($c->get('PDO'));
    },

    AuthProvider::class => function (ContainerInterface $c) {
        $settings = $c->get('settings');
        return new AuthProvider(
            $c->get(IAuthService::class),
            [
                'jwt' => [
                    'key' => $settings['jwt']['secret_key'],
                    'access_token_expiration' => $settings['jwt']['access_token_expiration'],
                    'refresh_token_expiration' => $settings['jwt']['refresh_token_expiration']
                ]
            ]
        );
    },

    ServicePraticienInterface::class => function (ContainerInterface $c) {
        return new ServicePraticien(new ArrayPraticienRepository());
    },

    IPraticienService::class => function (ContainerInterface $c) {
        return new PraticienService($c->get(ServicePraticienInterface::class));
    },

    RendezVousService::class => function (ContainerInterface $c) {
        return new RendezVousService(
            new ArrayRdvRepository(),
            $c->get(IPraticienService::class),
            $c->get(LoggerInterface::class)
        );
    },

    LoggerInterface::class => function (ContainerInterface $c) {
        $logger = new Logger('toubeelib');
        $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
        return $logger;
    },

    'settings' => function (ContainerInterface $c) {
        return require __DIR__ . '/settings.php';
    }
];