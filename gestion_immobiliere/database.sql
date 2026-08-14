-- Base de données
CREATE DATABASE IF NOT EXISTS gestion_immobiliere CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_immobiliere;

-- Table utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'client') DEFAULT 'client',
    telephone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table biens immobiliers
CREATE TABLE biens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    type ENUM('appartement', 'maison', 'villa', 'bureau', 'terrain') NOT NULL,
    statut ENUM('disponible', 'loue', 'vendu') DEFAULT 'disponible',
    prix DECIMAL(10,2) NOT NULL,
    surface DECIMAL(8,2),
    nb_pieces INT,
    adresse VARCHAR(255),
    ville VARCHAR(100),
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table locataires
CREATE TABLE locataires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    telephone VARCHAR(20),
    bien_id INT,
    date_debut DATE,
    date_fin DATE,
    FOREIGN KEY (bien_id) REFERENCES biens(id) ON DELETE SET NULL
);

-- Table paiements
CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    locataire_id INT NOT NULL,
    bien_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    date_paiement DATE NOT NULL,
    statut ENUM('payé', 'en attente', 'retard') DEFAULT 'en attente',
    mois VARCHAR(20),
    FOREIGN KEY (locataire_id) REFERENCES locataires(id) ON DELETE CASCADE,
    FOREIGN KEY (bien_id) REFERENCES biens(id) ON DELETE CASCADE
);

-- Admin par défaut (mot de passe: admin123)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role)
VALUES ('Admin', 'Super', 'admin@immo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
