-- Création de la base de données
CREATE DATABASE entreprise;

-- Utilisation de la base de données
USE entreprise;

-- Création de la table employes
CREATE TABLE employes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    departement VARCHAR(50) NOT NULL,
    poste VARCHAR(50) NOT NULL
);

-- Création de la table projets
CREATE TABLE projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_projet VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    responsable INT,
    FOREIGN KEY (responsable) REFERENCES employes(id)
);

-- Insertion de données dans la table employes
INSERT INTO employes (nom, prenom, email, departement, poste) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 'Informatique', 'Développeur'),
('Martin', 'Claire', 'claire.martin@example.com', 'Marketing', 'Chef de projet'),
('Durand', 'Paul', 'paul.durand@example.com', 'Finance', 'Analyste');

-- Insertion de données dans la table projets
INSERT INTO projets (nom_projet, description, responsable) VALUES
('Site Web', 'Développement d’un site web pour l’entreprise', 1),
('Campagne Marketing', 'Lancement d’une campagne publicitaire', 2),
('Analyse Financière', 'Analyse des résultats financiers annuels', 3);

GRANT ALL PRIVILEGES ON entreprise.* TO 'root'@'%' IDENTIFIED BY 'root';
FLUSH PRIVILEGES;
