<?php

namespace toubeelib\application\dto;

class PraticienDTO
{
    public function __construct(
        public string $id,
        public string $nom,
        public string $prenom,
        public array $specialites
    ) {}
}
