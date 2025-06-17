-- Script SQL pour créer les tables de demandes d'actes avec la structure complète
-- Base de données: projet_pct001

USE projet_pct001;

-- Table pour les demandes d'actes de naissance
CREATE TABLE IF NOT EXISTS demandes_naissance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenoms VARCHAR(255) NOT NULL,
    date_naissance DATE NOT NULL,
    lieu_naissance VARCHAR(255) NOT NULL,
    nom_pere VARCHAR(255),
    prenoms_pere VARCHAR(255),
    nom_mere VARCHAR(255) NOT NULL,
    prenoms_mere VARCHAR(255) NOT NULL,
    nombre_copies INT DEFAULT 1,
    motif TEXT NOT NULL,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'En attente',
    email_demandeur VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table pour les demandes d'actes de mariage
CREATE TABLE IF NOT EXISTS demandes_mariage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_epoux VARCHAR(255) NOT NULL,
    prenom_epoux VARCHAR(255) NOT NULL,
    nom_epouse VARCHAR(255) NOT NULL,
    prenom_epouse VARCHAR(255) NOT NULL,
    date_mariage DATE NOT NULL,
    lieu_mariage VARCHAR(255) NOT NULL,
    motif TEXT NOT NULL,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'En attente',
    email_demandeur VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table pour les demandes d'actes de décès
CREATE TABLE IF NOT EXISTS demandes_deces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_defunt VARCHAR(255) NOT NULL,
    prenoms_defunt VARCHAR(255) NOT NULL,
    date_deces DATE NOT NULL,
    lieu_deces VARCHAR(255) NOT NULL,
    date_naissance_defunt DATE,
    lieu_naissance_defunt VARCHAR(255),
    nom_pere_defunt VARCHAR(255),
    prenoms_pere_defunt VARCHAR(255),
    nom_mere_defunt VARCHAR(255),
    prenoms_mere_defunt VARCHAR(255),
    nom_declarant VARCHAR(255) NOT NULL,
    prenoms_declarant VARCHAR(255) NOT NULL,
    lien_parente VARCHAR(255),
    adresse_declarant TEXT,
    nombre_copies INT DEFAULT 1,
    motif TEXT NOT NULL,
    date_demande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'En attente',
    email_demandeur VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Ajouter des index pour améliorer les performances
CREATE INDEX idx_statut_naissance ON demandes_naissance(statut);
CREATE INDEX idx_date_demande_naissance ON demandes_naissance(date_demande);

CREATE INDEX idx_statut_mariage ON demandes_mariage(statut);
CREATE INDEX idx_date_demande_mariage ON demandes_mariage(date_demande);

CREATE INDEX idx_statut_deces ON demandes_deces(statut);
CREATE INDEX idx_date_demande_deces ON demandes_deces(date_demande);

-- Insérer quelques données de test (optionnel)
INSERT INTO demandes_naissance (nom, prenoms, date_naissance, lieu_naissance, nom_mere, prenoms_mere, motif) 
VALUES 
('KOUAME', 'Jean Baptiste', '1990-05-15', 'Botro', 'KONE', 'Marie', 'Demande pour passeport'),
('TRAORE', 'Aminata', '1985-12-03', 'Botro', 'DIALLO', 'Fatoumata', 'Demande pour carte d\'identité');

INSERT INTO demandes_mariage (nom_epoux, prenom_epoux, nom_epouse, prenom_epouse, date_mariage, lieu_mariage, motif)
VALUES 
('BAMBA', 'Sekou', 'KONE', 'Adjoa', '2020-07-25', 'Botro', 'Demande pour visa'),
('OUATTARA', 'Ibrahim', 'YAPI', 'Akissi', '2019-11-15', 'Botro', 'Demande administrative');

INSERT INTO demandes_deces (nom_defunt, prenoms_defunt, date_deces, lieu_deces, nom_declarant, prenoms_declarant, motif)
VALUES 
('DIABATE', 'Mamadou', '2023-01-10', 'Botro', 'DIABATE', 'Moussa', 'Succession'),
('KOFFI', 'Akoto', '2023-03-22', 'Botro', 'KOFFI', 'Yao', 'Assurance décès');
