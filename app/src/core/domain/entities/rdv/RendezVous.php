<?php

namespace toubeelib\core\domain\entities\rdv;

use DateTime;
use Ramsey\Uuid\Uuid;

class RendezVous
{
    private string $id;
    private string $praticienId;
    private string $patientId;
    private string $specialite;
    private DateTime $debut;
    private DateTime $fin;
    private string $statut;

    public function __construct(
        string $praticienId,
        string $patientId,
        string $specialite,
        DateTime $debut
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->specialite = $specialite;
        $this->debut = $debut;
        $this->fin = (clone $debut)->modify('+30 minutes');
        $this->statut = 'planifie';
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getPraticienId(): string
    {
        return $this->praticienId;
    }

    public function getPatientId(): string
    {
        return $this->patientId;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

    public function getDebut(): DateTime
    {
        return $this->debut;
    }

    public function getFin(): DateTime
    {
        return $this->fin;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }
}
