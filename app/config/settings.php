<?php

return [
    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    
    'db' => [
        'host' => 'db',
        'dbname' => 'toubeelib',
        'user' => 'toubeelib',
        'password' => 'toubeelib'
    ],
    
    'jwt' => [
        'secret_key' => 'your-secret-key-here',
        'access_token_expiration' => 3600,
        'refresh_token_expiration' => 86400
    ]
];
