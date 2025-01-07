-- Création des tables pour Toubeelib

-- Table pour les spécialités médicales
CREATE TABLE IF NOT EXISTS "specialites" (
    "id" uuid NOT NULL DEFAULT gen_random_uuid(),
    "nom" VARCHAR(100) NOT NULL,
    CONSTRAINT "specialites_pkey" PRIMARY KEY ("id")
);

-- Table pour les praticiens
CREATE TABLE IF NOT EXISTS "praticiens" (
    "id" uuid NOT NULL DEFAULT gen_random_uuid(),
    "user_id" uuid NOT NULL REFERENCES "users"("id"),
    "numero_rpps" VARCHAR(11) NOT NULL UNIQUE,
    "nom" VARCHAR(100) NOT NULL,
    "prenom" VARCHAR(100) NOT NULL,
    "lieu_exercice" VARCHAR(255) NOT NULL,
    "donnees_bancaires" JSONB,
    CONSTRAINT "praticiens_pkey" PRIMARY KEY ("id")
);

-- Table de liaison praticiens-spécialités avec honoraires
CREATE TABLE IF NOT EXISTS "praticien_specialites" (
    "praticien_id" uuid NOT NULL REFERENCES "praticiens"("id"),
    "specialite_id" uuid NOT NULL REFERENCES "specialites"("id"),
    "honoraires_presentiel" DECIMAL(10,2) NOT NULL,
    "honoraires_teleconsultation" DECIMAL(10,2) NOT NULL,
    CONSTRAINT "praticien_specialites_pkey" PRIMARY KEY ("praticien_id", "specialite_id")
);

-- Table pour les contraintes temporelles des praticiens
CREATE TABLE IF NOT EXISTS "contraintes_temporelles" (
    "id" uuid NOT NULL DEFAULT gen_random_uuid(),
    "praticien_id" uuid NOT NULL REFERENCES "praticiens"("id"),
    "date_debut" TIMESTAMP NOT NULL,
    "date_fin" TIMESTAMP NOT NULL,
    "type" VARCHAR(50) NOT NULL, -- 'INDISPONIBILITE', 'CONGE', etc.
    "description" TEXT,
    CONSTRAINT "contraintes_temporelles_pkey" PRIMARY KEY ("id")
);

-- Table pour les patients
CREATE TABLE IF NOT EXISTS "patients" (
    "id" uuid NOT NULL DEFAULT gen_random_uuid(),
    "user_id" uuid NOT NULL REFERENCES "users"("id"),
    "numero_secu" VARCHAR(15) NOT NULL UNIQUE,
    "nom" VARCHAR(100) NOT NULL,
    "prenom" VARCHAR(100) NOT NULL,
    "date_naissance" DATE NOT NULL,
    "adresse" TEXT NOT NULL,
    "telephone" VARCHAR(20) NOT NULL,
    "medecin_traitant_id" uuid REFERENCES "praticiens"("id"),
    CONSTRAINT "patients_pkey" PRIMARY KEY ("id")
);

-- Table pour les rendez-vous
CREATE TABLE IF NOT EXISTS "rendez_vous" (
    "id" uuid NOT NULL DEFAULT gen_random_uuid(),
    "patient_id" uuid NOT NULL REFERENCES "patients"("id"),
    "praticien_id" uuid NOT NULL REFERENCES "praticiens"("id"),
    "specialite_id" uuid NOT NULL REFERENCES "specialites"("id"),
    "date_heure" TIMESTAMP NOT NULL,
    "duree_minutes" INTEGER NOT NULL DEFAULT 30,
    "type_consultation" VARCHAR(20) NOT NULL CHECK (type_consultation IN ('PRESENTIEL', 'TELECONSULTATION')),
    "statut" VARCHAR(20) NOT NULL CHECK (statut IN ('PLANIFIE', 'CONFIRME', 'ANNULE', 'TERMINE', 'ABSENT')),
    "paiement_status" VARCHAR(20) CHECK (paiement_status IN ('EN_ATTENTE', 'PAYE', 'TRANSMIS')),
    CONSTRAINT "rendez_vous_pkey" PRIMARY KEY ("id")
);

-- Table pour le personnel médical
CREATE TABLE IF NOT EXISTS "personnel_medical" (
    "id" uuid NOT NULL DEFAULT gen_random_uuid(),
    "user_id" uuid NOT NULL REFERENCES "users"("id"),
    "nom" VARCHAR(100) NOT NULL,
    "prenom" VARCHAR(100) NOT NULL,
    CONSTRAINT "personnel_medical_pkey" PRIMARY KEY ("id")
);

-- Table de liaison personnel médical - praticiens
CREATE TABLE IF NOT EXISTS "personnel_praticiens" (
    "personnel_id" uuid NOT NULL REFERENCES "personnel_medical"("id"),
    "praticien_id" uuid NOT NULL REFERENCES "praticiens"("id"),
    CONSTRAINT "personnel_praticiens_pkey" PRIMARY KEY ("personnel_id", "praticien_id")
);

-- Table pour les documents médicaux
CREATE TABLE IF NOT EXISTS "documents_medicaux" (
    "id" uuid NOT NULL DEFAULT gen_random_uuid(),
    "patient_id" uuid NOT NULL REFERENCES "patients"("id"),
    "praticien_id" uuid NOT NULL REFERENCES "praticiens"("id"),
    "rendez_vous_id" uuid REFERENCES "rendez_vous"("id"),
    "type" VARCHAR(50) NOT NULL, -- 'ORDONNANCE', 'COMPTE_RENDU', 'LETTRE', etc.
    "titre" VARCHAR(255) NOT NULL,
    "contenu" TEXT NOT NULL,
    "date_creation" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "documents_medicaux_pkey" PRIMARY KEY ("id")
);
