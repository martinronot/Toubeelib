<?php

declare(strict_types=1);

namespace toubeelib\application\services;

use toubeelib\application\interfaces\IAuthService;
use toubeelib\application\dto\AuthDTO;
use PDO;

class AuthService implements IAuthService
{
    public function __construct(
        private PDO $db
    ) {}

    public function verifyCredentials(string $email, string $password): AuthDTO
    {
        $stmt = $this->db->prepare('SELECT id, email, password, role FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new \RuntimeException('Invalid credentials');
        }

        return new AuthDTO(
            $user['id'],
            $user['email'],
            (int)$user['role']
        );
    }
}
