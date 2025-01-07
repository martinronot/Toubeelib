<?php

namespace toubeelib\application\services;

use toubeelib\application\interfaces\IRendezVousService;
use toubeelib\application\interfaces\IRendezVousRepository;
use toubeelib\application\interfaces\IPraticienService;
use toubeelib\application\dto\RendezVousDTO;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use DateTime;

class RendezVousService implements IRendezVousService {
    // Constantes pour les créneaux de rendez-vous
    private const DEBUT_JOURNEE = '09:00';
    private const FIN_JOURNEE = '17:00';
    private const DUREE_RENDEZVOUS = 30; // en minutes
    private const JOURS_TRAVAILLES = [1, 2, 3, 4, 5]; // Du lundi au vendredi

    public function __construct(
        private IRendezVousRepository $repository,
        private IPraticienService $praticienService,
        private LoggerInterface $logger
    ) {}

    public function getRendezVous(string $id): RendezVousDTO {
        $rdv = $this->repository->findById($id);
        if ($rdv === null) {
            throw new \RuntimeException("Rendez-vous non trouvé");
        }
        $this->logger->info("Consultation du rendez-vous {$id}");
        return $rdv;
    }

    public function creerRendezVous(
        string $patientId,
        string $praticienId,
        string $specialite,
        DateTime $dateHeure
    ): RendezVousDTO {
        // Vérifier que le praticien existe et a la spécialité
        $praticien = $this->praticienService->getPraticien($praticienId);
        if (!in_array($specialite, $praticien->specialites)) {
            throw new \RuntimeException("Le praticien n'a pas cette spécialité");
        }

        // Vérifier la disponibilité
        $rdvs = $this->repository->getRendezVousPraticien(
            $praticienId,
            $dateHeure,
            $dateHeure->modify('+' . self::DUREE_RENDEZVOUS . ' minutes')
        );

        if (!empty($rdvs)) {
            throw new \RuntimeException("Le créneau n'est pas disponible");
        }

        // Créer le rendez-vous
        $rdv = new RendezVousDTO(
            Uuid::uuid4()->toString(),
            $praticienId,
            $patientId,
            $specialite,
            $dateHeure
        );

        $this->repository->save($rdv);
        $this->logger->info("Création du rendez-vous {$rdv->id}");
        return $rdv;
    }

    public function getDisponibilites(string $praticienId, DateTime $debut, DateTime $fin): array
    {
        $this->logger->info("Récupération des disponibilités pour le praticien {$praticienId}");

        // Vérifier que le praticien existe
        $praticien = $this->praticienService->getPraticien($praticienId);
        if (!$praticien) {
            throw new \Exception("Le praticien n'existe pas");
        }

        // Récupérer les rendez-vous du praticien pour la période donnée
        $rdvs = $this->repository->getRendezVousPraticien($praticienId, $debut, $fin);

        // Créer un tableau des créneaux disponibles
        $creneaux = [];
        $currentDate = clone $debut;
        
        while ($currentDate <= $fin) {
            // Ne traiter que les jours travaillés
            if (in_array((int)$currentDate->format('N'), self::JOURS_TRAVAILLES)) {
                // Réinitialiser l'heure au début de la journée
                $currentDate->setTime(
                    (int)substr(self::DEBUT_JOURNEE, 0, 2),
                    (int)substr(self::DEBUT_JOURNEE, 3, 2)
                );
                
                $finJournee = clone $currentDate;
                $finJournee->setTime(
                    (int)substr(self::FIN_JOURNEE, 0, 2),
                    (int)substr(self::FIN_JOURNEE, 3, 2)
                );

                // Pour chaque créneau de la journée
                while ($currentDate < $finJournee) {
                    $finCreneau = (clone $currentDate)->modify('+' . self::DUREE_RENDEZVOUS . ' minutes');
                    
                    // Vérifier si le créneau est libre
                    $creneau_occupe = false;
                    foreach ($rdvs as $rdv) {
                        if ($rdv->dateHeure >= $currentDate && $rdv->dateHeure < $finCreneau) {
                            $creneau_occupe = true;
                            break;
                        }
                    }

                    if (!$creneau_occupe) {
                        $creneaux[] = [
                            'debut' => $currentDate->format('Y-m-d H:i:s'),
                            'fin' => $finCreneau->format('Y-m-d H:i:s')
                        ];
                    }

                    $currentDate = $finCreneau;
                }
            }
            
            // Passer au jour suivant
            $currentDate->modify('+1 day');
            $currentDate->setTime(0, 0);
        }

        return [
            'praticien' => [
                'id' => $praticien->id,
                'nom' => $praticien->nom,
                'prenom' => $praticien->prenom
            ],
            'creneaux' => $creneaux
        ];
    }

    public function annulerRendezVous(string $id): void {
        $rdv = $this->getRendezVous($id);
        $nouveauRdv = new RendezVousDTO(
            $rdv->id,
            $rdv->patientId,
            $rdv->praticienId,
            $rdv->specialite,
            $rdv->dateHeure,
            'annule',
            $rdv->lieu
        );
        $this->repository->save($nouveauRdv);
        $this->logger->info("Annulation du rendez-vous {$id}");
    }

    public function modifierSpecialite(string $id, string $nouvelleSpecialite): RendezVousDTO {
        $rdv = $this->getRendezVous($id);
        $praticien = $this->praticienService->getPraticien($rdv->praticienId);
        
        if (!in_array($nouvelleSpecialite, $praticien->specialites)) {
            throw new \RuntimeException("Le praticien n'a pas cette spécialité");
        }
        
        $nouveauRdv = new RendezVousDTO(
            $rdv->id,
            $rdv->patientId,
            $rdv->praticienId,
            $nouvelleSpecialite,
            $rdv->dateHeure,
            $rdv->statut,
            $rdv->lieu
        );
        
        $this->repository->save($nouveauRdv);
        $this->logger->info("Modification de la spécialité du rendez-vous {$id}");
        return $nouveauRdv;
    }

    public function modifierPatient(string $id, string $nouveauPatientId): RendezVousDTO {
        $rdv = $this->getRendezVous($id);
        $nouveauRdv = new RendezVousDTO(
            $rdv->id,
            $nouveauPatientId,
            $rdv->praticienId,
            $rdv->specialite,
            $rdv->dateHeure,
            $rdv->statut,
            $rdv->lieu
        );
        
        $this->repository->save($nouveauRdv);
        $this->logger->info("Modification du patient du rendez-vous {$id}");
        return $nouveauRdv;
    }

    public function marquerCommeHonore(string $id): void {
        $this->changerStatut($id, 'honore');
    }

    public function marquerCommeNonHonore(string $id): void {
        $this->changerStatut($id, 'non_honore');
    }

    public function marquerCommePaye(string $id): void {
        $this->changerStatut($id, 'paye');
    }

    private function changerStatut(string $id, string $nouveauStatut): void {
        $rdv = $this->getRendezVous($id);
        $nouveauRdv = new RendezVousDTO(
            $rdv->id,
            $rdv->patientId,
            $rdv->praticienId,
            $rdv->specialite,
            $rdv->dateHeure,
            $nouveauStatut,
            $rdv->lieu
        );
        
        $this->repository->save($nouveauRdv);
        $this->logger->info("Changement de statut du rendez-vous {$id} vers {$nouveauStatut}");
    }
}
