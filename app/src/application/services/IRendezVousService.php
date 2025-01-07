<?php

namespace toubeelib\application\services;

use DateTimeImmutable;

interface IRendezVousService
{
    public function getDisponibilites(string $praticienId, DateTimeImmutable $debut, DateTimeImmutable $fin): array;
}
