<?php

namespace toubeelib\infrastructure\persistence;

use toubeelib\application\interfaces\IRendezVousRepository;
use toubeelib\application\dto\RendezVousDTO;

class InMemoryRendezVousRepository implements IRendezVousRepository {
    private array $rendezVous = [];

    public function findById(string $id): ?RendezVousDTO {
        return $this->rendezVous[$id] ?? null;
    }

    public function save(RendezVousDTO $rendezVous): void {
        $this->rendezVous[$rendezVous->id] = $rendezVous;
    }

    public function getRendezVousPraticien(string $praticienId, \DateTime $debut, \DateTime $fin): array {
        return array_filter(
            $this->rendezVous,
            function (RendezVousDTO $rdv) use ($praticienId, $debut, $fin) {
                return $rdv->praticienId === $praticienId
                    && $rdv->dateHeure >= $debut
                    && $rdv->dateHeure <= $fin
                    && $rdv->statut !== 'annule';
            }
        );
    }
}
