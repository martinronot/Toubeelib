<?php

namespace toubeelib\application\services;

use toubeelib\application\interfaces\IPraticienService;
use toubeelib\application\dto\PraticienDTO;
use toubeelib\core\services\praticien\ServicePraticien;

class PraticienService implements IPraticienService
{
    public function __construct(
        private ServicePraticien $corePraticienService
    ) {}

    public function getPraticien(string $id): PraticienDTO
    {
        $praticien = $this->corePraticienService->getPraticienById($id);
        return new PraticienDTO(
            $praticien->getId(),
            $praticien->getNom(),
            $praticien->getPrenom(),
            $praticien->getSpecialites()
        );
    }

    public function getAllPraticiens(): array
    {
        $praticiens = $this->corePraticienService->getAllPraticiens();
        return array_map(
            fn($p) => new PraticienDTO(
                $p->getId(),
                $p->getNom(),
                $p->getPrenom(),
                $p->getSpecialites()
            ),
            $praticiens
        );
    }
}
