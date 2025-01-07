<?php

declare(strict_types=1);

namespace toubeelib\application\services;

use toubeelib\application\dto\AuthDTO;

class PraticienAuthzService
{
    // Rôles
    private const ROLE_PRATICIEN = 10;
    private const ROLE_ADMIN = 15;

    public function canAccessPraticien(AuthDTO $auth, string $praticienId): bool
    {
        // Vérifier si l'utilisateur a un rôle suffisant
        if (!in_array($auth->role, [self::ROLE_PRATICIEN, self::ROLE_ADMIN])) {
            return false;
        }

        // Si c'est un admin, il peut tout faire
        if ($auth->role === self::ROLE_ADMIN) {
            return true;
        }

        // Un praticien ne peut accéder qu'à son propre profil
        return $auth->id === $praticienId;
    }

    public function canModifyPraticien(AuthDTO $auth, string $praticienId): bool
    {
        // Même logique que pour l'accès
        return $this->canAccessPraticien($auth, $praticienId);
    }

    public function canDeletePraticien(AuthDTO $auth, string $praticienId): bool
    {
        // Seul l'admin peut supprimer un praticien
        return $auth->role === self::ROLE_ADMIN;
    }
}
