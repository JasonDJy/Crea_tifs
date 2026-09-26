-- ============================================================================
-- Base de données de l'examen Scripts Serveurs - gestion des projets.
-- Ce fichier crée la base, les tables et quelques données de test.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS examen_projects
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE examen_projects;

DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS creatifs;

CREATE TABLE creatifs (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(45) NOT NULL,
    bio TEXT,
    image VARCHAR(45),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE projects (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    texte TEXT,
    dateCreation DATETIME NOT NULL,
    image VARCHAR(45),
    creatif INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    KEY fk_projects_creatifs_idx (creatif),
    CONSTRAINT fk_projects_creatifs
        FOREIGN KEY (creatif) REFERENCES creatifs(id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO creatifs (pseudo, bio, image) VALUES
('Alice', 'Graphiste et créative.', 'person_1.jpg'),
('Bob', 'Designer spécialisé dans les projets numériques.', 'person_2.jpg'),
('Charlie', 'Créatif indépendant.', 'person_3.jpg');

INSERT INTO projects (titre, texte, dateCreation, image, creatif) VALUES
('Premier projet', 'Ceci est le texte de présentation du premier projet. Il sert de donnée de test pour la liste, le détail et la pagination.', NOW(), 'image_1.jpg', 1),
('Projet web', 'Un deuxième projet de démonstration avec un texte suffisamment long pour tester la fonction truncate demandée dans les consignes.', NOW(), 'image_2.jpg', 2),
('Application mobile', 'Projet de conception d une application mobile réalisée pendant la formation.', NOW(), 'image_3.jpg', 3);
