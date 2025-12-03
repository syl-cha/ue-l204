-- -----------------------------------------------------------------
--                Base de données : universite
-- -----------------------------------------------------------------

-- TABLES

CREATE TABLE utilisateur (
  id INT PRIMARY KEY AUTO_INCREMENT,
  login VARCHAR(255) UNIQUE NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  role ENUM('enseignant', 'etudiant', 'admin') NOT NULL,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  actif BOOLEAN DEFAULT TRUE
);

CREATE TABLE enseignant (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT UNIQUE NOT NULL,
  bureau VARCHAR(50),
  telephone VARCHAR(20),
  specialite VARCHAR(255),
  statut ENUM('titulaire', 'vacataire', 'contractuel') DEFAULT 'titulaire',
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE etudiant (
  id INT PRIMARY KEY AUTO_INCREMENT,
  utilisateur_id INT UNIQUE NOT NULL,
  numero_etudiant VARCHAR(20) UNIQUE NOT NULL,
  niveau ENUM('L1', 'L2', 'L3', 'M1', 'M2') NOT NULL,
  date_inscription DATE NOT NULL,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id) ON DELETE CASCADE
);

CREATE TABLE cours (
  id INT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(20) UNIQUE NOT NULL,
  nom VARCHAR(255) NOT NULL,
  credits INT NOT NULL CHECK (credits > 0),
  description TEXT,
  capacite_max INT DEFAULT 30,
  annee_universitaire VARCHAR(9) NOT NULL,  -- "2025-2026"
  actif BOOLEAN DEFAULT TRUE
);

-- TABLES DE LIAISON

CREATE TABLE prerequis (
  cours_id INT NOT NULL,
  prerequis_cours_id INT NOT NULL,
  PRIMARY KEY (cours_id, prerequis_cours_id),
  FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE,
  FOREIGN KEY (prerequis_cours_id) REFERENCES cours(id) ON DELETE CASCADE,
  CHECK (cours_id != prerequis_cours_id)  -- pour prévenir l'autoréférence !!!
);

CREATE TABLE enseigne (
  id INT PRIMARY KEY AUTO_INCREMENT,
  enseignant_id INT NOT NULL,
  cours_id INT NOT NULL,
  annee_universitaire VARCHAR(9) NOT NULL,
  responsable BOOLEAN DEFAULT FALSE,
  UNIQUE KEY unique_enseignement (enseignant_id, cours_id, annee_universitaire),
  FOREIGN KEY (enseignant_id) REFERENCES enseignant(id) ON DELETE CASCADE,
  FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
);

CREATE TABLE inscription (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  cours_id INT NOT NULL,
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  statut ENUM('en_attente', 'valide', 'refuse', 'abandonne') DEFAULT 'en_attente',
  note DECIMAL(4,2) CHECK (note >= 0 AND note <= 20),
  valide BOOLEAN DEFAULT FALSE,
  UNIQUE KEY unique_inscription (etudiant_id, cours_id),
  FOREIGN KEY (etudiant_id) REFERENCES etudiant(id) ON DELETE CASCADE,
  FOREIGN KEY (cours_id) REFERENCES cours(id) ON DELETE CASCADE
);
