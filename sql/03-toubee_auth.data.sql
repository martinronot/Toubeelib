-- Insertion des spécialités
INSERT INTO "specialites" ("id", "nom", "description") VALUES
('550e8400-e29b-41d4-a716-446655440000', 'Médecine générale', 'Médecin généraliste'),
('550e8400-e29b-41d4-a716-446655440001', 'Cardiologie', 'Spécialiste du cœur'),
('550e8400-e29b-41d4-a716-446655440002', 'Dermatologie', 'Spécialiste de la peau');

-- Insertion des utilisateurs
INSERT INTO "users" ("id", "email", "password", "role") VALUES
('118a9bca-b30e-360a-9acb-0f44498fa9cb', 'munoz.theophile@laposte.net', '$2y$10$quwpZib5aTqYwiBm9hLg2u5.xZheZpiM0Kr6gOzEQ.0AVfObtWcjW', 0),
('ee2f80f8-18d4-32ce-9e0b-339a5d266efa', 'obourgeois@leduc.net', '$2y$10$5i81RafslZdqPx2l0Bme/uo6OndfFRk1MK/BOOwNujGu60EVs1fee', 10),
('3ad5f7ae-8699-34c7-824d-25919c7752be', 'guillaume90@boulay.com', '$2y$10$5i81RafslZdqPx2l0Bme/uo6OndfFRk1MK/BOOwNujGu60EVs1fee', 10),
('add2e46a-8465-3aea-8f61-3129247d6b11', 'noel.descamps@tele2.fr', '$2y$10$lBtNDO46UE75B4ueBe7TCu4T3HNlADlSRqXnSJ0/qGNSJ7PvgrKK2', 10),
('346db59f-d583-31ac-aba8-a36043b4865c', 'jreynaud@free.fr', '$2y$10$OsduIH9Xr06u4jUbswSy7.Y0dVlYfBvVfhcYy8KIuCktgCyly32d2', 10),
('a7df1a42-1cb3-3fda-8b7e-e2151aac0987', 'lthibault@langlois.fr', '$2y$10$yN1VszGhFzVr2FgLu74vK.kHqeZCiXrXk36LPWfkC69HAVoIZMIJi', 10),
('9778ecb7-9b84-3155-b0fc-d0a0b812bbb1', 'marcelle10@live.com', '$2y$10$Kc4MOlZEfAF9MrH6LF7mh.TWmK4K9Ym.uReGF2SHb090ju6zbsX7W', 5),
('3b1bb2f9-3d53-3307-ba2d-2ce4c5e07f50', 'blaroche@laposte.net', '$2y$10$65.TC916AHpziu77nr6ooOeDNiNggfSp/qTatiNek5rOoDboIkfu.', 5),
('1c61a317-8e03-3204-b02f-2117f466eaf0', 'leveque.alphonse@tele2.fr', '$2y$10$sH4s2KO7.oZuVTEWPVKfWO/j8ZyOL7gTtGeVTjuPGMc9Sj4CP/5VO', 5);

-- Insertion des praticiens
INSERT INTO "praticiens" ("id", "user_id", "rpps", "nom", "prenom", "adresse", "telephone", "specialite_id", "honoraires") VALUES
('677e98d4-cfea-4000-0000-000000000001', 'ee2f80f8-18d4-32ce-9e0b-339a5d266efa', '10101010101', 'Bourgeois', 'Olivier', '1 rue de la Paix, Nancy', '0123456789', '550e8400-e29b-41d4-a716-446655440000', 25.00),
('677e98d4-cfea-4000-0000-000000000002', '3ad5f7ae-8699-34c7-824d-25919c7752be', '20202020202', 'Guillaume', 'Martin', '2 avenue Foch, Nancy', '0123456789', '550e8400-e29b-41d4-a716-446655440001', 50.00);

-- Insertion des patients
INSERT INTO "patients" ("id", "user_id", "numero_secu", "nom", "prenom", "date_naissance", "adresse", "telephone", "medecin_traitant_id") VALUES
('677e98d4-cfea-4000-0000-000000000003', '118a9bca-b30e-360a-9acb-0f44498fa9cb', '196123456789', 'Munoz', 'Théophile', '1996-01-01', '3 rue des Jardins, Nancy', '0123456789', '677e98d4-cfea-4000-0000-000000000001');
