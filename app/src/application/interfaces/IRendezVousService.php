<?php

namespace toubeelib\application\interfaces;

use toubeelib\application\dto\RendezVousDTO;

interface IRendezVousService {
    /**
     * Consulter un rendez-vous
     */
    public function getRendezVous(string $id): RendezVousDTO;
    
    /**
     * Créer un nouveau rendez-vous
     */
    public function creerRendezVous(
        string $patientId,
        string $praticienId,
        string $specialite,
        \DateTime $dateHeure
    ): RendezVousDTO;
    
    /**
     * Annuler un rendez-vous
     */
    public function annulerRendezVous(string $id): void;
    
    /**
     * Lister les disponibilités d'un praticien
     */
    public function getDisponibilites(
        string $praticienId,
        \DateTime $debut,
        \DateTime $fin
    ): array;
    
    /**
     * Modifier la spécialité d'un rendez-vous
     */
    public function modifierSpecialite(string $id, string $nouvelleSpecialite): RendezVousDTO;
    
    /**
     * Modifier le patient d'un rendez-vous
     */
    public function modifierPatient(string $id, string $nouveauPatientId): RendezVousDTO;
    
    /**
     * Marquer un rendez-vous comme honoré
     */
    public function marquerCommeHonore(string $id): void;
    
    /**
     * Marquer un rendez-vous comme non honoré
     */
    public function marquerCommeNonHonore(string $id): void;
    
    /**
     * Marquer un rendez-vous comme payé
     */
    public function marquerCommePaye(string $id): void;
}
