<?php

namespace toubeelib\core\repositoryInterfaces;

use toubeelib\core\domain\entities\rdv\RendezVous;
use DateTime;

interface RdvRepositoryInterface
{
    public function getRendezVousPraticien(string $praticienId, DateTime $debut, DateTime $fin): array;
    public function getRendezVousPatient(string $patientId, DateTime $debut, DateTime $fin): array;
    public function save(RendezVous $rdv): void;
    public function getById(string $id): ?RendezVous;
    public function delete(string $id): void;
}
