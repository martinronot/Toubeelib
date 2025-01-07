-- Insertion des données de test pour Toubeelib

-- Insertion des spécialités
INSERT INTO "specialites" ("id", "nom") VALUES
('f47ac10b-58cc-4372-a567-0e02b2c3d479', 'Médecine générale'),
('f47ac10b-58cc-4372-a567-0e02b2c3d480', 'Cardiologie'),
('f47ac10b-58cc-4372-a567-0e02b2c3d481', 'Dermatologie'),
('f47ac10b-58cc-4372-a567-0e02b2c3d482', 'Pédiatrie'),
('f47ac10b-58cc-4372-a567-0e02b2c3d483', 'Psychiatrie');

-- Insertion des praticiens (liés aux utilisateurs existants avec le rôle 10)
INSERT INTO "praticiens" ("id", "user_id", "numero_rpps", "nom", "prenom", "lieu_exercice", "donnees_bancaires") VALUES
('a47ac10b-58cc-4372-a567-0e02b2c3d479', 'ee2f80f8-18d4-32ce-9e0b-339a5d266efa', '10101010101', 'DUPONT', 'Jean', 'Maison médicale de Nancy', '{"iban": "FR7630001007941234567890185", "bic": "BDFEFRPPXXX"}'),
('a47ac10b-58cc-4372-a567-0e02b2c3d480', '3ad5f7ae-8699-34c7-824d-25919c7752be', '10101010102', 'MARTIN', 'Sophie', 'Maison médicale de Nancy', '{"iban": "FR7630001007941234567890186", "bic": "BDFEFRPPXXX"}'),
('a47ac10b-58cc-4372-a567-0e02b2c3d481', 'add2e46a-8465-3aea-8f61-3129247d6b11', '10101010103', 'BERNARD', 'Pierre', 'Cabinet médical du Centre', '{"iban": "FR7630001007941234567890187", "bic": "BDFEFRPPXXX"}');

-- Association praticiens-spécialités avec honoraires
INSERT INTO "praticien_specialites" ("praticien_id", "specialite_id", "honoraires_presentiel", "honoraires_teleconsultation") VALUES
('a47ac10b-58cc-4372-a567-0e02b2c3d479', 'f47ac10b-58cc-4372-a567-0e02b2c3d479', 25.00, 23.00),
('a47ac10b-58cc-4372-a567-0e02b2c3d480', 'f47ac10b-58cc-4372-a567-0e02b2c3d480', 50.00, 45.00),
('a47ac10b-58cc-4372-a567-0e02b2c3d481', 'f47ac10b-58cc-4372-a567-0e02b2c3d481', 50.00, 45.00);

-- Insertion des patients (liés aux utilisateurs existants avec le rôle 5)
INSERT INTO "patients" ("id", "user_id", "numero_secu", "nom", "prenom", "date_naissance", "adresse", "telephone", "medecin_traitant_id") VALUES
('b47ac10b-58cc-4372-a567-0e02b2c3d479', '9778ecb7-9b84-3155-b0fc-d0a0b812bbb1', '196012345678912', 'DURAND', 'Pierre', '1960-01-01', '1 rue de la Paix, Nancy', '0601020304', 'a47ac10b-58cc-4372-a567-0e02b2c3d479'),
('b47ac10b-58cc-4372-a567-0e02b2c3d480', '3b1bb2f9-3d53-3307-ba2d-2ce4c5e07f50', '198123456789123', 'PETIT', 'Marie', '1981-05-15', '2 avenue Foch, Nancy', '0602030405', 'a47ac10b-58cc-4372-a567-0e02b2c3d479'),
('b47ac10b-58cc-4372-a567-0e02b2c3d481', '1c61a317-8e03-3204-b02f-2117f466eaf0', '199234567891234', 'ROBERT', 'Julie', '1992-08-22', '3 rue Stanislas, Nancy', '0603040506', 'a47ac10b-58cc-4372-a567-0e02b2c3d480');

-- Insertion des contraintes temporelles
INSERT INTO "contraintes_temporelles" ("id", "praticien_id", "date_debut", "date_fin", "type", "description") VALUES
('c47ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d479', '2025-02-01 00:00:00', '2025-02-15 23:59:59', 'CONGE', 'Vacances d''hiver'),
('c47ac10b-58cc-4372-a567-0e02b2c3d480', 'a47ac10b-58cc-4372-a567-0e02b2c3d480', '2025-01-10 09:00:00', '2025-01-10 12:00:00', 'INDISPONIBILITE', 'Formation');

-- Insertion des rendez-vous
INSERT INTO "rendez_vous" ("id", "patient_id", "praticien_id", "specialite_id", "date_heure", "duree_minutes", "type_consultation", "statut", "paiement_status") VALUES
('d47ac10b-58cc-4372-a567-0e02b2c3d479', 'b47ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d479', 'f47ac10b-58cc-4372-a567-0e02b2c3d479', '2025-01-08 09:00:00', 30, 'PRESENTIEL', 'PLANIFIE', 'EN_ATTENTE'),
('d47ac10b-58cc-4372-a567-0e02b2c3d480', 'b47ac10b-58cc-4372-a567-0e02b2c3d480', 'a47ac10b-58cc-4372-a567-0e02b2c3d480', 'f47ac10b-58cc-4372-a567-0e02b2c3d480', '2025-01-09 14:30:00', 45, 'PRESENTIEL', 'PLANIFIE', 'EN_ATTENTE'),
('d47ac10b-58cc-4372-a567-0e02b2c3d481', 'b47ac10b-58cc-4372-a567-0e02b2c3d481', 'a47ac10b-58cc-4372-a567-0e02b2c3d481', 'f47ac10b-58cc-4372-a567-0e02b2c3d481', '2025-01-10 10:00:00', 30, 'TELECONSULTATION', 'PLANIFIE', 'EN_ATTENTE');

-- Insertion du personnel médical
INSERT INTO "personnel_medical" ("id", "user_id", "nom", "prenom") VALUES
('e47ac10b-58cc-4372-a567-0e02b2c3d479', '346db59f-d583-31ac-aba8-a36043b4865c', 'LAMBERT', 'Marie', 'SECRETAIRE'),
('e47ac10b-58cc-4372-a567-0e02b2c3d480', 'a7df1a42-1cb3-3fda-8b7e-e2151aac0987', 'DUBOIS', 'Jean', 'ASSISTANT');

-- Association personnel médical - praticiens
INSERT INTO "personnel_praticiens" ("personnel_id", "praticien_id") VALUES
('e47ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d479'),
('e47ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d480'),
('e47ac10b-58cc-4372-a567-0e02b2c3d480', 'a47ac10b-58cc-4372-a567-0e02b2c3d481');

-- Insertion des documents médicaux
INSERT INTO "documents_medicaux" ("id", "patient_id", "praticien_id", "rendez_vous_id", "type", "titre", "contenu", "date_creation") VALUES
('f47ac10b-58cc-4372-a567-0e02b2c3d479', 'b47ac10b-58cc-4372-a567-0e02b2c3d479', 'a47ac10b-58cc-4372-a567-0e02b2c3d479', 'd47ac10b-58cc-4372-a567-0e02b2c3d479', 'ORDONNANCE', 'Ordonnance consultation du 08/01/2025', 'Contenu de l''ordonnance...', '2025-01-08 09:30:00'),
('f47ac10b-58cc-4372-a567-0e02b2c3d480', 'b47ac10b-58cc-4372-a567-0e02b2c3d480', 'a47ac10b-58cc-4372-a567-0e02b2c3d480', 'd47ac10b-58cc-4372-a567-0e02b2c3d480', 'COMPTE_RENDU', 'Compte-rendu consultation cardiologie', 'Contenu du compte-rendu...', '2025-01-09 15:15:00');
