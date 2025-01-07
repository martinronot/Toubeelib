<?php

namespace toubeelib\infrastructure\repositories;

use toubeelib\core\domain\entities\rdv\RendezVous;
use toubeelib\application\interfaces\IRendezVousRepository;
use toubeelib\application\dto\RendezVousDTO;
use DateTime;

class ArrayRdvRepository implements IRendezVousRepository
{
    private array $rendezVous = [];

    public function __construct()
    {
        // Initialisation avec des rendez-vous de test
        $r1 = new RendezVous('p1', 'pa1', 'A', new DateTime('2024-09-02 09:00'));
        $r1->setId('r1');
        $r2 = new RendezVous('p1', 'pa1', 'A', new DateTime('2024-09-02 10:00'));
        $r2->setId('r2');
        $r3 = new RendezVous('p2', 'pa1', 'A', new DateTime('2024-09-02 09:30'));
        $r3->setId('r3');

        $this->rendezVous = ['r1' => $r1, 'r2' => $r2, 'r3' => $r3];
    }

    private function toDTO(RendezVous $rdv): RendezVousDTO
    {
        return new RendezVousDTO(
            $rdv->getId(),
            $rdv->getPraticienId(),
            $rdv->getPatientId(),
            $rdv->getSpecialite(),
            $rdv->getDebut()
        );
    }

    private function toEntity(RendezVousDTO $dto): RendezVous
    {
        $rdv = new RendezVous(
            $dto->praticienId,
            $dto->patientId,
            $dto->specialite,
            $dto->dateHeure
        );
        $rdv->setId($dto->id);
        return $rdv;
    }

    public function findById(string $id): ?RendezVousDTO
    {
        $rdv = $this->rendezVous[$id] ?? null;
        return $rdv ? $this->toDTO($rdv) : null;
    }

    public function save(RendezVousDTO $rendezVousDTO): void
    {
        $rdv = $this->toEntity($rendezVousDTO);
        $this->rendezVous[$rdv->getId()] = $rdv;
    }

    public function getRendezVousPraticien(string $praticienId, DateTime $debut, DateTime $fin): array
    {
        $rdvs = array_filter($this->rendezVous, function (RendezVous $rdv) use ($praticienId, $debut, $fin) {
            return $rdv->getPraticienId() === $praticienId
                && $rdv->getDebut() >= $debut
                && $rdv->getDebut() <= $fin;
        });

        return array_map([$this, 'toDTO'], $rdvs);
    }
}