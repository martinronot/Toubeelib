<?php

declare(strict_types=1);

namespace toubeelib\application\interfaces;

use toubeelib\application\dto\AuthDTO;

interface IAuthService
{
    /**
     * Vérifie les credentials d'un utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $password Mot de passe de l'utilisateur
     * @return AuthDTO DTO contenant les informations de l'utilisateur authentifié
     * @throws \RuntimeException si les credentials sont invalides
     */
    public function verifyCredentials(string $email, string $password): AuthDTO;
}
