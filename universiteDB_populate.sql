-- requêtes nécessaires au remplissage des tables de la base de donnée universite
-- v 0.1
-- 2025-12-03

-- ----------------------------------------------------------------------------
--                  EFFACEMENT DES TABLES EXISTANTES
-- ----------------------------------------------------------------------------

-- désactivation des clé étrangères pour permettre l'effacement
SET FOREIGN_KEY_CHECKS = 0;

-- on vide les tables (DELETE ne bloque pas sur les contraintes si checks=0)
DELETE FROM inscription;
DELETE FROM enseigne;
DELETE FROM prerequis;
DELETE FROM etudiant;
DELETE FROM enseignant;
DELETE FROM cours;
DELETE FROM utilisateur;

-- compteurs des ID à 1
ALTER TABLE inscription AUTO_INCREMENT = 1;
ALTER TABLE enseigne AUTO_INCREMENT = 1;
ALTER TABLE prerequis AUTO_INCREMENT = 1;
ALTER TABLE etudiant AUTO_INCREMENT = 1;
ALTER TABLE enseignant AUTO_INCREMENT = 1;
ALTER TABLE cours AUTO_INCREMENT = 1;
ALTER TABLE utilisateur AUTO_INCREMENT = 1;

-- réactivation des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------
--                  CRÉATION DES TABLES UTILISATEURS
-- ----------------------------------------------------------------------------
-- Les rôles sont définis à cette étapes
-- un mot de passe générique `123456` pour tous (hashage par BCRYPT)
-- (à noter : politique de changement de mot de passe est de la responsabilité
-- de l'application - utilisation du flag `mot_de_passe_provisoire`)

INSERT INTO utilisateur (id, login, mot_de_passe, nom, prenom, email, role) VALUES

-- ADMIN (ID 1)
(1, 'admin', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Admin', 'Système', 'admin@univ.fr', 'admin'),

-- ENSEIGNANTS (ID 2 à 11)
(2, 'turing', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Turing', 'Alan', 'alan.turing@univ.fr', 'enseignant'),
(3, 'lovelace', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Lovelace', 'Ada', 'ada.lovelace@univ.fr', 'enseignant'),
(4, 'hopper', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Hopper', 'Grace', 'grace.hopper@univ.fr', 'enseignant'),
(5, 'berners', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Berners-Lee', 'Tim', 'tim.bl@univ.fr', 'enseignant'),
(6, 'shannon', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Shannon', 'Claude', 'claude.shannon@univ.fr', 'enseignant'),
(7, 'hamilton', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Hamilton', 'Margaret', 'margaret.hamilton@univ.fr', 'enseignant'),
(8, 'ritchie', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Ritchie', 'Dennis', 'dennis.ritchie@univ.fr', 'enseignant'),
(9, 'thompson', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Thompson', 'Ken', 'ken.thompson@univ.fr', 'enseignant'),
(10, 'knuth', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Knuth', 'Donald', 'donald.knuth@univ.fr', 'enseignant'),
(11, 'torvalds', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Torvalds', 'Linus', 'linus.torvalds@univ.fr', 'enseignant'),

-- ETUDIANTS (ID 12 à 41) - 30 étudiants
(12, 'etud1', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Dupont', 'Jean', 'jean.dupont@etu.univ.fr', 'etudiant'),
(13, 'etud2', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Durand', 'Marie', 'marie.durand@etu.univ.fr', 'etudiant'),
(14, 'etud3', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Petit', 'Pierre', 'pierre.petit@etu.univ.fr', 'etudiant'),
(15, 'etud4', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Martin', 'Sophie', 'sophie.martin@etu.univ.fr', 'etudiant'),
(16, 'etud5', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Bernard', 'Lucas', 'lucas.bernard@etu.univ.fr', 'etudiant'),
(17, 'etud6', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Dubois', 'Emma', 'emma.dubois@etu.univ.fr', 'etudiant'),
(18, 'etud7', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Thomas', 'Hugo', 'hugo.thomas@etu.univ.fr', 'etudiant'),
(19, 'etud8', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Robert', 'Chloe', 'chloe.robert@etu.univ.fr', 'etudiant'),
(20, 'etud9', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Richard', 'Nathan', 'nathan.richard@etu.univ.fr', 'etudiant'),
(21, 'etud10', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Simon', 'Lea', 'lea.simon@etu.univ.fr', 'etudiant'),
(22, 'etud11', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Michel', 'Paul', 'paul.michel@etu.univ.fr', 'etudiant'),
(23, 'etud12', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Lefebvre', 'Julie', 'julie.lefebvre@etu.univ.fr', 'etudiant'),
(24, 'etud13', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Leroy', 'Thomas', 'thomas.leroy@etu.univ.fr', 'etudiant'),
(25, 'etud14', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Moreau', 'Manon', 'manon.moreau@etu.univ.fr', 'etudiant'),
(26, 'etud15', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Petit', 'Antoine', 'antoine.petit@etu.univ.fr', 'etudiant'),
(27, 'etud16', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Girard', 'Camille', 'camille.girard@etu.univ.fr', 'etudiant'),
(28, 'etud17', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Bonnet', 'Alexandre', 'alexandre.bonnet@etu.univ.fr', 'etudiant'),
(29, 'etud18', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Andre', 'Sarah', 'sarah.andre@etu.univ.fr', 'etudiant'),
(30, 'etud19', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Mercier', 'Julien', 'julien.mercier@etu.univ.fr', 'etudiant'),
(31, 'etud20', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Blanc', 'Laura', 'laura.blanc@etu.univ.fr', 'etudiant'),
(32, 'etud21', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Guerin', 'Romain', 'romain.guerin@etu.univ.fr', 'etudiant'),
(33, 'etud22', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Boyer', 'Celine', 'celine.boyer@etu.univ.fr', 'etudiant'),
(34, 'etud23', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Garnier', 'Mathieu', 'mathieu.garnier@etu.univ.fr', 'etudiant'),
(35, 'etud24', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Chevalier', 'Anais', 'anais.chevalier@etu.univ.fr', 'etudiant'),
(36, 'etud25', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Francois', 'Nicolas', 'nicolas.francois@etu.univ.fr', 'etudiant'),
(37, 'etud26', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Legrand', 'Elodie', 'elodie.legrand@etu.univ.fr', 'etudiant'),
(38, 'etud27', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Gauthier', 'Bastien', 'bastien.gauthier@etu.univ.fr', 'etudiant'),
(39, 'etud28', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Garcia', 'Audrey', 'audrey.garcia@etu.univ.fr', 'etudiant'),
(40, 'etud29', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Perrin', 'Florian', 'florian.perrin@etu.univ.fr', 'etudiant'),
(41, 'etud30', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', 'Robin', 'Lisa', 'lisa.robin@etu.univ.fr', 'etudiant');

-- ----------------------------------------------------------------------------
--                  CRÉATION DES PROFILS UTILISATEURS
-- ----------------------------------------------------------------------------
-- on reprend les ID définies précédemment

-- Enseignants (pour les ID 2 à 11)
INSERT INTO enseignant (utilisateur_id, bureau, telephone, specialite, statut) VALUES
(2, 'B101', '0102030401', 'Intelligence Artificielle', 'titulaire'),
(3, 'B102', '0102030402', 'Algorithmique', 'titulaire'),
(4, 'B103', '0102030403', 'Langages de programmation', 'titulaire'),
(5, 'C201', '0102030404', 'Web & Réseaux', 'contractuel'),
(6, 'C202', '0102030405', 'Théorie de l''information', 'titulaire'),
(7, 'D301', '0102030406', 'Génie Logiciel', 'titulaire'),
(8, 'D302', '0102030407', 'Systèmes d''exploitation', 'vacataire'),
(9, 'D303', '0102030408', 'Unix & Sécurité', 'titulaire'),
(10, 'E401', '0102030409', 'Analyse d''algorithmes', 'titulaire'),
(11, 'E402', '0102030410', 'Noyau & Open Source', 'contractuel');

-- Etudiants (pour les ID 12 à 41)
-- Numéros étudiants arbitraires mais uniques
INSERT INTO etudiant (utilisateur_id, numero_etudiant, niveau, date_inscription) VALUES
(12, '20250001', 'L3', '2024-09-01'), (13, '20250002', 'L3', '2024-09-01'), (14, '20250003', 'L3', '2024-09-02'),
(15, '20250004', 'L3', '2024-09-02'), (16, '20250005', 'M1', '2024-09-01'), (17, '20250006', 'M1', '2024-09-03'),
(18, '20250007', 'L2', '2024-09-04'), (19, '20250008', 'L2', '2024-09-04'), (20, '20250009', 'L1', '2024-09-05'),
(21, '20250010', 'L1', '2024-09-05'), (22, '20250011', 'L3', '2024-09-01'), (23, '20250012', 'M2', '2024-09-01'),
(24, '20250013', 'M2', '2024-09-02'), (25, '20250014', 'M1', '2024-09-02'), (26, '20250015', 'L3', '2024-09-06'),
(27, '20250016', 'L2', '2024-09-06'), (28, '20250017', 'L1', '2024-09-07'), (29, '20250018', 'M2', '2024-09-07'),
(30, '20250019', 'L3', '2024-09-08'), (31, '20250020', 'L3', '2024-09-08'), (32, '20250021', 'M1', '2024-09-09'),
(33, '20250022', 'L2', '2024-09-09'), (34, '20250023', 'L3', '2024-09-10'), (35, '20250024', 'L1', '2024-09-10'),
(36, '20250025', 'M2', '2024-09-01'), (37, '20250026', 'L3', '2024-09-01'), (38, '20250027', 'L2', '2024-09-02'),
(39, '20250028', 'M1', '2024-09-02'), (40, '20250029', 'L3', '2024-09-03'), (41, '20250030', 'L3', '2024-09-03');


-- ----------------------------------------------------------------------------
--                  CRÉATION DES COURS
-- ----------------------------------------------------------------------------
INSERT INTO cours (code, nom, credits, description, annee_universitaire) VALUES

-- L1
('INFO-L101', 'Introduction à l''Algorithmique', 6, 'Logique, pseudo-code, variables et boucles', '2025-2026'),
('INFO-L102', 'Web Statique : HTML & CSS', 4, 'Structure du DOM, sélecteurs CSS et responsive design', '2025-2026'),
('INFO-L103', 'Initiation JavaScript', 4, 'Syntaxe de base, manipulation simple du DOM', '2025-2026'),
('INFO-L104', 'Introduction aux Bases de Données', 3, 'Concept de données, introduction aux requêtes SQL simples', '2025-2026'),
('INFO-L105', 'Architecture des Ordinateurs', 3, 'Composants matériels, binaire, assembleur', '2025-2026'),
('INFO-L106', 'Mathématiques pour l''Informatique', 5, 'Logique booléenne, ensembles et matrices', '2025-2026'),
('INFO-L107', 'Système d''exploitation (Linux)', 4, 'Commandes shell, gestion de fichiers et droits', '2025-2026'),
('INFO-L108', 'Anglais Technique 1', 2, 'Vocabulaire de base de l''IT', '2025-2026'),
('INFO-L109', 'Outils Collaboratifs', 2, 'Suite bureautique, Trello, Slack', '2025-2026'),
('INFO-L110', 'Histoire de l''Informatique', 2, 'De Turing à Internet', '2025-2026'),
('INFO-L111', 'Expression et Communication', 2, 'Prise de parole et rédaction technique', '2025-2026'),
('INFO-L112', 'Projet Web Découverte', 3, 'Réalisation d''un site statique simple', '2025-2026'),

-- L2
('INFO-L201', 'Algorithmique Avancée & Structures de Données', 6, 'Piles, files, arbres, graphes et complexité', '2025-2026'),
('INFO-L202', 'JavaScript Avancé', 5, 'ES6+, Asynchrone (Promises, Async/Await), API Fetch', '2025-2026'),
('INFO-L203', 'PHP & Web Dynamique', 6, 'Traitement formulaire, sessions, interaction MySQL native', '2025-2026'),
('INFO-L204', 'Conception de BDD Relationnelles', 5, 'Modèle E/A, Normalisation (1NF, 2NF, 3NF), MCD/MLD', '2025-2026'),
('INFO-L205', 'Frameworks CSS', 3, 'Bootstrap, Tailwind CSS, SASS', '2025-2026'),
('INFO-L206', 'Programmation Orientée Objet (Java)', 6, 'Classes, héritage, polymorphisme, interfaces', '2025-2026'),
('INFO-L207', 'Réseaux & Protocoles', 4, 'Modèle OSI, TCP/IP, DNS, HTTP', '2025-2026'),
('INFO-L208', 'Anglais Technique 2', 2, 'Rédaction de documentation', '2025-2026'),
('INFO-L209', 'Versioning avec Git', 3, 'Commit, Branch, Merge, Pull Request', '2025-2026'),
('INFO-L210', 'Probabilités et Statistiques', 4, 'Analyse de données pour l''ingénieur', '2025-2026'),
('INFO-L211', 'Droit du Numérique', 2, 'RGPD, Propriété intellectuelle', '2025-2026'),
('INFO-L212', 'Projet Web Dynamique', 4, 'Site complet avec espace membre (PHP/SQL)', '2025-2026'),

-- L3
('INFO-L301', 'Frameworks JS Front-End', 6, 'Vue.js ou React : Composants, State Management', '2025-2026'),
('INFO-L302', 'Frameworks PHP (Symfony)', 6, 'MVC, ORM (Doctrine), Routing, Twig', '2025-2026'),
('INFO-L303', 'Bases de Données NoSQL', 4, 'MongoDB, Redis, Neo4j', '2025-2026'),
('INFO-L304', 'Développement Mobile Hybride', 4, 'React Native ou Flutter', '2025-2026'),
('INFO-L305', 'Sécurité des Applications Web', 4, 'OWASP Top 10, Injections SQL, XSS, CSRF', '2025-2026'),
('INFO-L306', 'Gestion de Projet Agile', 3, 'Scrum, Kanban, User Stories', '2025-2026'),
('INFO-L307', 'DevOps & Conteneurisation', 4, 'Docker, Docker-Compose, Introduction CI/CD', '2025-2026'),
('INFO-L308', 'UX/UI Design', 3, 'Maquettage (Figma), Ergonomie, Accessibilité', '2025-2026'),
('INFO-L309', 'Intelligence Artificielle (Introduction)', 4, 'Concepts de base, Python pour la data', '2025-2026'),
('INFO-L310', 'Anglais Professionnel', 2, 'Préparation au TOEIC', '2025-2026'),
('INFO-L311', 'Complexité Algorithmique', 3, 'P vs NP, Optimisation', '2025-2026'),
('INFO-L312', 'Projet de Fin de Licence', 8, 'Développement d''une application complète en équipe', '2025-2026'),

-- M1
('INFO-M101', 'Architecture Microservices', 6, 'API RESTful, GraphQL, Communication inter-services', '2025-2026'),
('INFO-M102', 'Fullstack JavaScript', 6, 'Node.js, Express/NestJS, SSR (Nuxt/Next)', '2025-2026'),
('INFO-M103', 'Big Data & Data Engineering', 5, 'Hadoop, Spark, Data Lakes', '2025-2026'),
('INFO-M104', 'Cloud Computing Avancé', 5, 'AWS/Azure, Serverless, Terraform', '2025-2026'),
('INFO-M105', 'Audit et Tests Logiciels', 4, 'Tests unitaires, E2E, TDD, Qualimétrie', '2025-2026'),
('INFO-M106', 'Cryptographie Appliquée', 4, 'Chiffrement symétrique/asymétrique, PKI, Blockchain', '2025-2026'),

-- M2
('INFO-M201', 'Deep Learning & Vision', 6, 'Réseaux de neurones, TensorFlow/PyTorch', '2025-2026'),
('INFO-M202', 'Blockchain & Smart Contracts', 5, 'Ethereum, Solidity, Web3', '2025-2026'),
('INFO-M203', 'Architectures Hautes Performances', 5, 'Calcul parallèle, Optimisation bas niveau', '2025-2026'),
('INFO-M204', 'Réalité Virtuelle et Augmentée', 4, 'Unity, WebXR, Three.js', '2025-2026'),
('INFO-M205', 'Gouvernance IT & Green IT', 4, 'Normes ISO, Numérique responsable', '2025-2026'),
('INFO-M206', 'Recherche & Innovation', 6, 'Méthodologie de recherche, veille technologique', '2025-2026');

-- ----------------------------------------------------------------------------
--                  CRÉATION DES PRÉREQUIS ENTRE lES COURS
-- ----------------------------------------------------------------------------
-- plutôt que d'associer deux cours en dur INSERT INTO prerequis VALUES (15, 4);
-- c'est plus robuste de faire une requête sur le code du cours : s'il change d'ID,
-- le lien créé n'est pas rompu.
INSERT INTO prerequis (cours_id, prerequis_cours_id) VALUES

-- == FILIÈRE ALGORITHMIQUE & PROGRAMMATION ==
-- Algo Avancé (L2) requiert Intro Algo (L1)
((SELECT id FROM cours WHERE code='INFO-L201'), (SELECT id FROM cours WHERE code='INFO-L101')),
-- Complexité (L3) requiert Algo Avancé (L2) et Maths (L1)
((SELECT id FROM cours WHERE code='INFO-L311'), (SELECT id FROM cours WHERE code='INFO-L201')),
((SELECT id FROM cours WHERE code='INFO-L311'), (SELECT id FROM cours WHERE code='INFO-L106')),
-- IA (L3) requiert Algo Avancé (L2) et Probas (L2)
((SELECT id FROM cours WHERE code='INFO-L309'), (SELECT id FROM cours WHERE code='INFO-L201')),
((SELECT id FROM cours WHERE code='INFO-L309'), (SELECT id FROM cours WHERE code='INFO-L210')),

-- == FILIÈRE DÉVELOPPEMENT WEB (JS & PHP) ==
-- JS Avancé (L2) requiert Initiation JS (L1)
((SELECT id FROM cours WHERE code='INFO-L202'), (SELECT id FROM cours WHERE code='INFO-L103')),
-- Web Dynamique PHP (L2) requiert Web Statique (L1) et Intro BDD (L1)
((SELECT id FROM cours WHERE code='INFO-L203'), (SELECT id FROM cours WHERE code='INFO-L102')),
((SELECT id FROM cours WHERE code='INFO-L203'), (SELECT id FROM cours WHERE code='INFO-L104')),
-- Frameworks JS (L3) requiert JS Avancé (L2)
((SELECT id FROM cours WHERE code='INFO-L301'), (SELECT id FROM cours WHERE code='INFO-L202')),
-- Frameworks PHP (L3) requiert Web Dynamique PHP (L2) et POO (L2)
((SELECT id FROM cours WHERE code='INFO-L302'), (SELECT id FROM cours WHERE code='INFO-L203')),
((SELECT id FROM cours WHERE code='INFO-L302'), (SELECT id FROM cours WHERE code='INFO-L206')),
-- Fullstack JS (M1) requiert Frameworks JS (L3)
((SELECT id FROM cours WHERE code='INFO-M102'), (SELECT id FROM cours WHERE code='INFO-L301')),

-- == FILIÈRE BASES DE DONNÉES ==
-- Conception BDD (L2) requiert Intro BDD (L1)
((SELECT id FROM cours WHERE code='INFO-L204'), (SELECT id FROM cours WHERE code='INFO-L104')),
-- NoSQL (L3) requiert Conception BDD (L2)
((SELECT id FROM cours WHERE code='INFO-L303'), (SELECT id FROM cours WHERE code='INFO-L204')),
-- Big Data (M1) requiert NoSQL (L3) et Probas (L2)
((SELECT id FROM cours WHERE code='INFO-M103'), (SELECT id FROM cours WHERE code='INFO-L303')),
((SELECT id FROM cours WHERE code='INFO-M103'), (SELECT id FROM cours WHERE code='INFO-L210')),

-- == FILIÈRE PROJETS & INTÉGRATION ==
-- Projet Web Dynamique (L2) requiert Git (L2) et PHP (L2)
((SELECT id FROM cours WHERE code='INFO-L212'), (SELECT id FROM cours WHERE code='INFO-L209')),
((SELECT id FROM cours WHERE code='INFO-L212'), (SELECT id FROM cours WHERE code='INFO-L203')),
-- Projet Fin Licence (L3) requiert Gestion Projet Agile (L3)
((SELECT id FROM cours WHERE code='INFO-L312'), (SELECT id FROM cours WHERE code='INFO-L306')),
-- Architecture Microservices (M1) requiert Frameworks PHP (L3) OU Fullstack JS (M1 - attention circularité, on prend Framework JS L3)
((SELECT id FROM cours WHERE code='INFO-M101'), (SELECT id FROM cours WHERE code='INFO-L302')),
((SELECT id FROM cours WHERE code='INFO-M101'), (SELECT id FROM cours WHERE code='INFO-L301'));