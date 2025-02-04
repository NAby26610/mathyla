CREATE DATABASE mathyla;
USE mathyla;

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    roles ENUM('admin', 'employer') NOT NULL,
    codePin VARCHAR(10) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    adresse VARCHAR(255) DEFAULT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT DEFAULT NULL,
    modify_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    modify_by INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des menus
CREATE TABLE menu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  url VARCHAR(255) NOT NULL,
  icons VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_by INT DEFAULT NULL,
  modify_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  modify_by INT DEFAULT NULL,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des accès
CREATE TABLE acces (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_menu INT NOT NULL,
  id_utilisateurs INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  created_by INT DEFAULT NULL,
  modify_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  modify_by INT DEFAULT NULL,
  FOREIGN KEY (id_menu) REFERENCES menu(id) ON DELETE CASCADE,
  FOREIGN KEY (id_utilisateurs) REFERENCES Utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des devises
CREATE TABLE devise (
  id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(25) NOT NULL UNIQUE,
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  statut TINYINT(1) DEFAULT 1,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des zones
CREATE TABLE zones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_devise INT DEFAULT NULL,
  libelle VARCHAR(50) NOT NULL UNIQUE,
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_devise) REFERENCES devise(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des agences
CREATE TABLE agences (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_zone INT DEFAULT NULL,
  libelle VARCHAR(75) NOT NULL UNIQUE,
  telephone VARCHAR(20) NOT NULL UNIQUE,
  soldeInitial DECIMAL(15,2) NOT NULL DEFAULT 0,
  soldeMax DECIMAL(15,2) DEFAULT 0,
  seuil DECIMAL(15,2) DEFAULT 0,
  indicatif VARCHAR(10) DEFAULT '+224',
  adresse VARCHAR(255) DEFAULT NULL,
  heureOuverture TIME DEFAULT NULL,
  heureFermeture TIME DEFAULT NULL,
  descriptions VARCHAR(250) DEFAULT NULL,
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_zone) REFERENCES zones(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des affectations
CREATE TABLE affectations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_agence INT NOT NULL,
    id_utilisateur INT NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT DEFAULT NULL,
    modify_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    modify_by INT DEFAULT NULL,
    FOREIGN KEY (id_agence) REFERENCES agences(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des transactions
CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_agence INT NOT NULL,
  id_devise INT DEFAULT NULL,
  montant DECIMAL(15,2) NOT NULL CHECK (montant >= 0),
  typeTransaction ENUM('depot', 'retrait', 'virement') NOT NULL,
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_agence) REFERENCES agences(id) ON DELETE CASCADE,
  FOREIGN KEY (id_devise) REFERENCES devise(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des transferts
CREATE TABLE transfert (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_zone INT NOT NULL,
  nomEnvoyeur VARCHAR(50) NOT NULL,
  telEnvoyeur VARCHAR(50) NOT NULL,
  nomDestinataire VARCHAR(50) NOT NULL,
  telDestinataire VARCHAR(50) NOT NULL,
  piece ENUM('avec piece','sans piece') NOT NULL,
  montant DECIMAL(15,2) NOT NULL CHECK (montant>= 0),
  frais DECIMAL(15,2) NOT NULL CHECK (frais >= 0),
  codeTransfert VARCHAR(50) NOT NULL UNIQUE,
  statut ENUM('en attente','valider','annuler') NOT NULL,
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_zone) REFERENCES zones(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- Trigger pour générer un code de transfert unique
DELIMITER $$

CREATE TRIGGER before_insert_transfert
BEFORE INSERT ON transfert
FOR EACH ROW
BEGIN
    SET NEW.codeTransfert = CONCAT(
        UPPER(SUBSTRING(MD5(RAND()), 1, 4)),
        LPAD(FLOOR(RAND() * 10000), 4, '0'),
        UPPER(SUBSTRING(MD5(NOW()), 1, 2))
    );
END $$

DELIMITER ;
-- Table des retraits
CREATE TABLE retraits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_agence INT NOT NULL,
  id_transfert INT NOT NULL,
  statut ENUM('effectué', 'annulé') NOT NULL DEFAULT 'effectué',
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_transfert) REFERENCES transfert(id) ON DELETE CASCADE,
  FOREIGN KEY (id_agence) REFERENCES agences(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des dépenses
CREATE TABLE depenses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_agence INT NOT NULL,
  types VARCHAR(25) NOT NULL,
  montant DOUBLE NOT NULL CHECK (montant >= 0),
  motif VARCHAR(255) NOT NULL,
  statut INT NOT NULL DEFAULT 100,
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_agence) REFERENCES agences(id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des caisses
CREATE TABLE caisses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_transfert INT DEFAULT NULL,
  id_retrait INT DEFAULT NULL,
  id_agence INT NOT NULL,
  id_depense INT DEFAULT NULL,
  statut ENUM('entrer', 'sortie') NOT NULL DEFAULT 'entrer',
  montant DECIMAL(20, 2) NOT NULL CHECK (montant >= 0),
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_agence) REFERENCES agences(id) ON DELETE CASCADE,
  FOREIGN KEY (id_depense) REFERENCES depenses(id) ON DELETE SET NULL,
  FOREIGN KEY (id_transfert) REFERENCES transfert(id) ON DELETE SET NULL,
  FOREIGN KEY (id_retrait) REFERENCES retraits(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des transferts de fonds
CREATE TABLE transfert_fond (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_agenceSource INT NOT NULL,
  id_agenceDestinataire INT NOT NULL,
  montant DECIMAL(15,2) NOT NULL CHECK (montant >= 0),
  id_devise INT DEFAULT NULL,
  statut ENUM('en attente','valider'),
  commentaire VARCHAR(255) DEFAULT NULL,
  created_by INT DEFAULT NULL,
  modify_by INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_agenceSource) REFERENCES agences(id) ON DELETE CASCADE,
  FOREIGN KEY (id_agenceDestinataire) REFERENCES agences(id) ON DELETE CASCADE,
  FOREIGN KEY (id_devise) REFERENCES devise(id) ON DELETE SET NULL,
  FOREIGN KEY (created_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL,
  FOREIGN KEY (modify_by) REFERENCES Utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
