-- Structure de la base de données Toubeelib

-- Table pour les spécialités médicales
CREATE TABLE IF NOT EXISTS "specialites" (
    "id" uuid NOT NULL DEFAULT uuid_generate_v4(),
    "nom" VARCHAR(100) NOT NULL,
    "description" text,
    CONSTRAINT "specialites_pkey" PRIMARY KEY ("id")
);

-- Table pour les praticiens
CREATE TABLE IF NOT EXISTS "praticiens" (
    "id" uuid NOT NULL DEFAULT uuid_generate_v4(),
    "user_id" uuid NOT NULL REFERENCES "users"("id"),
    "rpps" VARCHAR(11) NOT NULL UNIQUE,
    "nom" VARCHAR(100) NOT NULL,
    "prenom" VARCHAR(100) NOT NULL,
    "adresse" text NOT NULL,
    "telephone" varchar(20) NOT NULL,
    "specialite_id" uuid NOT NULL REFERENCES "specialites"("id"),
    "honoraires_presentiel" DECIMAL(10,2) NOT NULL,
    "honoraires_teleconsultation" DECIMAL(10,2) NOT NULL,
    "donnees_bancaires" JSONB,
    CONSTRAINT "praticiens_pkey" PRIMARY KEY ("id")
);

-- Table pour les contraintes temporelles des praticiens
CREATE TABLE IF NOT EXISTS "contraintes_temporelles" (
    "id" uuid NOT NULL DEFAULT uuid_generate_v4(),
    "praticien_id" uuid NOT NULL REFERENCES "praticiens"("id"),
    "date_debut" TIMESTAMP NOT NULL,
    "date_fin" TIMESTAMP NOT NULL,
    "type" VARCHAR(50) NOT NULL, -- 'INDISPONIBILITE', 'CONGE', etc.
    "description" TEXT,
    CONSTRAINT "contraintes_temporelles_pkey" PRIMARY KEY ("id")
);

-- Table pour les patients
CREATE TABLE IF NOT EXISTS "patients" (
    "id" uuid NOT NULL DEFAULT uuid_generate_v4(),
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
    "id" uuid NOT NULL DEFAULT uuid_generate_v4(),
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
    "id" uuid NOT NULL DEFAULT uuid_generate_v4(),
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
CREATE TABLE IF NOT EXISTS "documents" (
    "id" uuid NOT NULL DEFAULT uuid_generate_v4(),
    "patient_id" uuid NOT NULL REFERENCES "patients"("id"),
    "praticien_id" uuid NOT NULL REFERENCES "praticiens"("id"),
    "type" varchar(50) NOT NULL,
    "titre" varchar(255) NOT NULL,
    "contenu" text NOT NULL,
    "date_creation" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT "documents_pkey" PRIMARY KEY ("id")
);
