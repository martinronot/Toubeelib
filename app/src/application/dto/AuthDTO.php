<?php

declare(strict_types=1);

namespace toubeelib\application\dto;

class AuthDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly int $role,
        public ?string $accessToken = null,
        public ?string $refreshToken = null
    ) {}
}
