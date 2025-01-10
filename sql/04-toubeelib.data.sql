-- Insertion des spécialités médicales
INSERT INTO "specialites" ("id", "nom", "description") VALUES
    ('a47ac10b-58cc-4372-a567-0e02b2c3d470', 'Médecine générale', 'Médecine de premier recours'),
    ('a47ac10b-58cc-4372-a567-0e02b2c3d471', 'Cardiologie', 'Spécialité des maladies du cœur'),
    ('a47ac10b-58cc-4372-a567-0e02b2c3d472', 'Pédiatrie', 'Médecine des enfants'),
    ('a47ac10b-58cc-4372-a567-0e02b2c3d473', 'Dermatologie', 'Spécialité des maladies de la peau'),
    ('a47ac10b-58cc-4372-a567-0e02b2c3d474', 'Psychiatrie', 'Spécialité des troubles mentaux');

-- Insertion des praticiens
INSERT INTO "praticiens" ("id", "user_id", "rpps", "nom", "prenom", "adresse", "telephone", "specialite_id", "honoraires_presentiel", "honoraires_teleconsultation", "donnees_bancaires") VALUES
    ('a47ac10b-58cc-4372-a567-0e02b2c3d479', 'ee2f80f8-18d4-32ce-9e0b-339a5d266efa', '10101010101', 'DUPONT', 'Jean', 'Maison médicale de Nancy', '0383000001', 'a47ac10b-58cc-4372-a567-0e02b2c3d470', 25.00, 20.00, '{"iban": "FR7630001007941234567890185", "bic": "BDFEFRPPXXX"}'),
    ('a47ac10b-58cc-4372-a567-0e02b2c3d480', '3ad5f7ae-8699-34c7-824d-25919c7752be', '10101010102', 'MARTIN', 'Sophie', 'Maison médicale de Nancy', '0383000002', 'a47ac10b-58cc-4372-a567-0e02b2c3d471', 50.00, 40.00, '{"iban": "FR7630001007941234567890186", "bic": "BDFEFRPPXXX"}'),
    ('a47ac10b-58cc-4372-a567-0e02b2c3d481', 'add2e46a-8465-3aea-8f61-3129247d6b11', '10101010103', 'BERNARD', 'Pierre', 'Cabinet médical du Centre', '0383000003', 'a47ac10b-58cc-4372-a567-0e02b2c3d472', 45.00, 35.00, '{"iban": "FR7630001007941234567890187", "bic": "BDFEFRPPXXX"}');

-- Insertion des patients
INSERT INTO "patients" ("id", "user_id", "numero_secu", "nom", "prenom", "date_naissance", "adresse", "telephone", "medecin_traitant_id") VALUES
    ('b58ac10b-58cc-4372-a567-0e02b2c3d479', '3ad5f7ae-8699-34c7-824d-25919c7752bf', '1234567890123', 'DURAND', 'Marie', '1980-01-01', '1 rue de la Paix, Nancy', '0601020304', 'a47ac10b-58cc-4372-a567-0e02b2c3d479'),
    ('b58ac10b-58cc-4372-a567-0e02b2c3d480', 'add2e46a-8465-3aea-8f61-3129247d6b12', '2345678901234', 'PETIT', 'Paul', '1990-02-02', '2 rue de la Liberté, Nancy', '0602030405', 'a47ac10b-58cc-4372-a567-0e02b2c3d479');

-- Insertion du personnel médical
INSERT INTO "personnel_medical" ("id", "user_id", "nom", "prenom") VALUES
    ('c58ac10b-58cc-4372-a567-0e02b2c3d479', 'ee2f80f8-18d4-32ce-9e0b-339a5d266efb', 'ROBERT', 'Julie'),
    ('c58ac10b-58cc-4372-a567-0e02b2c3d480', '3ad5f7ae-8699-34c7-824d-25919c7752c0', 'MICHEL', 'Luc');

-- Liaison personnel médical - praticiens
INSERT INTO "personnel_praticiens" ("personnel_id", "praticien_id") VALUES
    ('c58ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d479'),
    ('c58ac10b-58cc-4372-a567-0e02b2c3d480', 'a47ac10b-58cc-4372-a567-0e02b2c3d480');

-- Insertion des rendez-vous
INSERT INTO "rendez_vous" ("id", "patient_id", "praticien_id", "specialite_id", "date_heure", "type_consultation", "statut", "paiement_status") VALUES
    ('d58ac10b-58cc-4372-a567-0e02b2c3d479', 'b58ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d470', '2025-01-10 09:00:00', 'PRESENTIEL', 'PLANIFIE', 'EN_ATTENTE'),
    ('d58ac10b-58cc-4372-a567-0e02b2c3d480', 'b58ac10b-58cc-4372-a567-0e02b2c3d480', 'a47ac10b-58cc-4372-a567-0e02b2c3d480', 'a47ac10b-58cc-4372-a567-0e02b2c3d471', '2025-01-10 10:00:00', 'TELECONSULTATION', 'PLANIFIE', 'EN_ATTENTE');
