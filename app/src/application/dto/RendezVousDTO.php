<?php

namespace toubeelib\application\dto;

class RendezVousDTO {
    public function __construct(
        public readonly string $id,
        public readonly string $patientId,
        public readonly string $praticienId,
        public readonly string $specialite,
        public readonly \DateTime $dateHeure,
        public readonly string $statut,
        public readonly ?string $lieu = null
    ) {}
}
