<?php

namespace toubeelib\tests;

use PHPUnit\Framework\TestCase;
use toubeelib\application\services\RendezVousService;
use toubeelib\infrastructure\persistence\InMemoryRendezVousRepository;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class RendezVousServiceTest extends TestCase {
    private RendezVousService $service;
    private InMemoryRendezVousRepository $repository;
    private Logger $logger;

    protected function setUp(): void {
        $this->repository = new InMemoryRendezVousRepository();
        $this->logger = new Logger('test');
        $this->logger->pushHandler(new NullHandler());
        
        // Mock du service praticien
        $praticienService = $this->createMock(IPraticienService::class);
        $praticienService->method('getPraticien')
            ->willReturn((object)[
                'id' => '1',
                'specialites' => ['generaliste', 'pediatre'],
                'lieu' => 'Cabinet A'
            ]);
        
        $this->service = new RendezVousService(
            $this->repository,
            $praticienService,
            $this->logger
        );
    }

    public function testCreerRendezVous(): void {
        $dateHeure = new \DateTime('2025-01-08 10:00:00');
        $rdv = $this->service->creerRendezVous(
            'patient1',
            'praticien1',
            'generaliste',
            $dateHeure
        );

        $this->assertNotNull($rdv);
        $this->assertEquals('prevu', $rdv->statut);
        $this->assertEquals('patient1', $rdv->patientId);
        $this->assertEquals('praticien1', $rdv->praticienId);
        $this->assertEquals('generaliste', $rdv->specialite);
        $this->assertEquals($dateHeure, $rdv->dateHeure);
    }

    public function testAnnulerRendezVous(): void {
        $dateHeure = new \DateTime('2025-01-08 10:00:00');
        $rdv = $this->service->creerRendezVous(
            'patient1',
            'praticien1',
            'generaliste',
            $dateHeure
        );

        $this->service->annulerRendezVous($rdv->id);
        $rdvAnnule = $this->service->getRendezVous($rdv->id);
        
        $this->assertEquals('annule', $rdvAnnule->statut);
    }

    public function testGetDisponibilites(): void {
        $debut = new \DateTime('2025-01-08'); // Mercredi
        $fin = new \DateTime('2025-01-08');
        
        $disponibilites = $this->service->getDisponibilites('praticien1', $debut, $fin);
        
        // Il devrait y avoir 16 créneaux de 30 minutes entre 9h et 17h
        $this->assertCount(16, $disponibilites);
    }
}
