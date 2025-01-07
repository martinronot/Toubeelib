<?php

namespace toubeelib\application\interfaces;

use toubeelib\application\dto\RendezVousDTO;

interface IRendezVousRepository
{
    public function findById(string $id): ?RendezVousDTO;
    public function save(RendezVousDTO $rendezVous): void;
    public function getRendezVousPraticien(string $praticienId, \DateTime $debut, \DateTime $fin): array;
}
