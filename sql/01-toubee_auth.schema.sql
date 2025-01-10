-- Création de la base de données
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS "users" (
    "id" uuid DEFAULT uuid_generate_v4(),
    "email" varchar(255) NOT NULL UNIQUE,
    "password" varchar(255) NOT NULL,
    "role" integer NOT NULL DEFAULT 0,
    PRIMARY KEY ("id")
);

-- Table des spécialités médicales
CREATE TABLE IF NOT EXISTS "specialites" (
    "id" uuid DEFAULT uuid_generate_v4(),
    "nom" varchar(255) NOT NULL,
    "description" text,
    PRIMARY KEY ("id")
);

-- Table des praticiens
CREATE TABLE IF NOT EXISTS "praticiens" (
    "id" uuid DEFAULT uuid_generate_v4(),
    "user_id" uuid NOT NULL,
    "rpps" varchar(11) NOT NULL UNIQUE,
    "nom" varchar(255) NOT NULL,
    "prenom" varchar(255) NOT NULL,
    "adresse" text NOT NULL,
    "telephone" varchar(20) NOT NULL,
    "specialite_id" uuid NOT NULL,
    "honoraires" decimal(10,2) NOT NULL,
    "iban" varchar(34),
    PRIMARY KEY ("id"),
    FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE,
    FOREIGN KEY ("specialite_id") REFERENCES "specialites" ("id") ON DELETE RESTRICT
);

-- Table des patients
CREATE TABLE IF NOT EXISTS "patients" (
    "id" uuid DEFAULT uuid_generate_v4(),
    "user_id" uuid NOT NULL,
    "numero_secu" varchar(15) NOT NULL UNIQUE,
    "nom" varchar(255) NOT NULL,
    "prenom" varchar(255) NOT NULL,
    "date_naissance" date NOT NULL,
    "adresse" text NOT NULL,
    "telephone" varchar(20) NOT NULL,
    "medecin_traitant_id" uuid,
    PRIMARY KEY ("id"),
    FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE,
    FOREIGN KEY ("medecin_traitant_id") REFERENCES "praticiens" ("id") ON DELETE SET NULL
);

-- Table des rendez-vous
CREATE TABLE IF NOT EXISTS "rendez_vous" (
    "id" uuid DEFAULT uuid_generate_v4(),
    "patient_id" uuid NOT NULL,
    "praticien_id" uuid NOT NULL,
    "date_heure" timestamp NOT NULL,
    "duree" interval NOT NULL DEFAULT '30 minutes',
    "type" varchar(50) NOT NULL DEFAULT 'presentiel',
    "statut" varchar(50) NOT NULL DEFAULT 'planifie',
    "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ("id"),
    FOREIGN KEY ("patient_id") REFERENCES "patients" ("id") ON DELETE CASCADE,
    FOREIGN KEY ("praticien_id") REFERENCES "praticiens" ("id") ON DELETE CASCADE
);

-- Table des documents médicaux
CREATE TABLE IF NOT EXISTS "documents" (
    "id" uuid DEFAULT uuid_generate_v4(),
    "patient_id" uuid NOT NULL,
    "praticien_id" uuid NOT NULL,
    "type" varchar(50) NOT NULL,
    "titre" varchar(255) NOT NULL,
    "contenu" text NOT NULL,
    "date_creation" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ("id"),
    FOREIGN KEY ("patient_id") REFERENCES "patients" ("id") ON DELETE CASCADE,
    FOREIGN KEY ("praticien_id") REFERENCES "praticiens" ("id") ON DELETE CASCADE
);
