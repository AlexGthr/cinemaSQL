-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour cinema
CREATE DATABASE IF NOT EXISTS `cinema` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cinema`;

-- Listage de la structure de table cinema. acteur
CREATE TABLE IF NOT EXISTS `acteur` (
  `id_acteur` int NOT NULL AUTO_INCREMENT,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id_acteur`),
  UNIQUE KEY `id_personne` (`id_personne`),
  CONSTRAINT `acteur_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.acteur : ~25 rows (environ)
INSERT INTO `acteur` (`id_acteur`, `id_personne`) VALUES
	(1, 1),
	(2, 3),
	(3, 4),
	(4, 5),
	(5, 6),
	(6, 7),
	(7, 9),
	(8, 10),
	(9, 11),
	(10, 12),
	(11, 13),
	(12, 15),
	(13, 16),
	(14, 17),
	(15, 18),
	(16, 19),
	(17, 21),
	(18, 22),
	(19, 23),
	(20, 24),
	(21, 25),
	(23, 27),
	(24, 28),
	(25, 29),
	(26, 30);

-- Listage de la structure de table cinema. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.categorie : ~5 rows (environ)
INSERT INTO `categorie` (`id_categorie`, `type`) VALUES
	(1, 'Fantastique'),
	(2, 'Super héros'),
	(3, 'Action'),
	(4, 'sitcom'),
	(5, 'humour');

-- Listage de la structure de table cinema. categoriser
CREATE TABLE IF NOT EXISTS `categoriser` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `id_categorie` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_categorie`),
  KEY `id_categorie` (`id_categorie`),
  CONSTRAINT `categoriser_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`),
  CONSTRAINT `categoriser_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.categoriser : ~10 rows (environ)
INSERT INTO `categoriser` (`id_film`, `id_categorie`) VALUES
	(1, 1),
	(3, 1),
	(4, 1),
	(1, 2),
	(1, 3),
	(3, 3),
	(4, 3),
	(2, 4),
	(2, 5),
	(4, 5);

-- Listage de la structure de table cinema. film
CREATE TABLE IF NOT EXISTS `film` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `dateDeSortie` date NOT NULL,
  `duree` int NOT NULL,
  `synopsis` text,
  `note` decimal(6,2) DEFAULT NULL,
  `affiche` varchar(255) DEFAULT NULL,
  `id_realisateur` int NOT NULL,
  PRIMARY KEY (`id_film`),
  KEY `id_realisateur` (`id_realisateur`),
  CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_realisateur`) REFERENCES `realisateur` (`id_realisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.film : ~4 rows (environ)
INSERT INTO `film` (`id_film`, `titre`, `dateDeSortie`, `duree`, `synopsis`, `note`, `affiche`, `id_realisateur`) VALUES
	(1, 'Spider Man', '2002-06-12', 121, 'L\'histoire d\'un homme qui se fais piquer par une araignée et deviens un super héros', 4.00, 'indisponible.png', 1),
	(2, 'The Big Bang Theory - Le film', '2024-09-15', 155, 'Découvrez la vie et les galères de trois scientifiques et un ingénieur.', 5.00, 'indisponible.png', 2),
	(3, 'Avatar', '2009-12-16', 162, 'Sur le monde extraterrestre luxuriant de Pandora vivent les Na\'vi, des êtres qui semblent primitifs, mais qui sont très évolués.', 5.00, 'indisponible.png', 3),
	(4, 'Barbie', '2023-07-19', 114, 'Barbie, qui vit à Barbie Land, est expulsée du pays pour être loin d\'être une poupée à l\'apparence parfaite; n\'ayant nulle part où aller, elle part pour le monde humain et cherche le vrai bonheur.', 5.00, 'indisponible.png', 4),
	(5, 'Suicide Squad', '2016-08-03', 123, 'Face à une menace aussi énigmatique qu\'invincible, l\'agent secret Amanda Waller réunit une armada de crapules de la pire espèce. Armés jusqu\'aux dents par le gouvernement, ces super-méchants s\'embarquent alors pour une mission-suicide.', 3.00, 'indisponible.png', 5);

-- Listage de la structure de table cinema. joue
CREATE TABLE IF NOT EXISTS `joue` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `id_acteur` int NOT NULL,
  `id_role` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_acteur`,`id_role`),
  KEY `id_acteur` (`id_acteur`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `joue_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`),
  CONSTRAINT `joue_ibfk_2` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id_acteur`),
  CONSTRAINT `joue_ibfk_3` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.joue : ~21 rows (environ)
INSERT INTO `joue` (`id_film`, `id_acteur`, `id_role`) VALUES
	(1, 1, 1),
	(1, 2, 3),
	(1, 3, 2),
	(1, 4, 4),
	(1, 5, 5),
	(1, 6, 6),
	(2, 7, 7),
	(2, 8, 8),
	(2, 9, 9),
	(2, 10, 10),
	(2, 11, 11),
	(3, 12, 12),
	(3, 13, 13),
	(3, 14, 14),
	(3, 15, 15),
	(3, 16, 16),
	(4, 17, 17),
	(5, 17, 26),
	(4, 18, 18),
	(4, 19, 19),
	(4, 20, 20),
	(4, 21, 21),
	(5, 23, 22),
	(5, 24, 23),
	(5, 25, 24),
	(5, 26, 25);

-- Listage de la structure de table cinema. personne
CREATE TABLE IF NOT EXISTS `personne` (
  `id_personne` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `sexe` varchar(50) NOT NULL,
  `dateDeNaissance` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.personne : ~30 rows (environ)
INSERT INTO `personne` (`id_personne`, `nom`, `prenom`, `sexe`, `dateDeNaissance`, `photo`) VALUES
	(1, 'Maguire', 'Tobey', 'M', '1975-06-27', NULL),
	(2, 'Raimi', 'Sam', 'M', '1959-10-23', NULL),
	(3, 'Dunst', 'Kirsten', 'F', '1982-04-30', NULL),
	(4, 'James Dafoe', 'William', 'M', '1955-07-22', NULL),
	(5, 'Edward Franco', 'James', 'M', '1978-04-19', NULL),
	(6, 'Robertson', 'Cliff', 'M', '1923-09-09', NULL),
	(7, 'Ann Harris', 'Rosemary', 'F', '1927-09-19', NULL),
	(8, 'Lorre', 'Chuck', 'M', '1952-10-18', NULL),
	(9, 'Galecki', 'John Mark', 'M', '1975-04-30', NULL),
	(10, 'Parsons', 'James Joseph', 'M', '1973-03-24', NULL),
	(11, 'Cuoco', 'Kaley', 'F', '1985-11-30', NULL),
	(12, 'Helberg', 'Simon', 'H', '1980-12-09', NULL),
	(13, 'Nayyar', 'Kunal', 'H', '1981-04-30', NULL),
	(14, 'Cameron', 'James', 'H', '1954-08-16', NULL),
	(15, 'Worthington', 'Samuel', 'H', '1976-08-02', NULL),
	(16, 'Saldana', 'Zoe', 'F', '1978-06-19', NULL),
	(17, 'Weaver', 'Sigourney', 'F', '1949-10-08', NULL),
	(18, 'Lang', 'Stephen', 'H', '1952-07-11', NULL),
	(19, 'Rodriguez', 'Michelle', 'F', '1978-07-12', NULL),
	(20, 'Gerwing', 'Greta', 'F', '1983-08-04', NULL),
	(21, 'Robbie', 'Margot', 'F', '1990-07-02', NULL),
	(22, 'Gosling', 'Ryan', 'H', '1980-11-12', NULL),
	(23, 'Mackey', 'Emma', 'F', '1996-01-04', NULL),
	(24, 'Ferrera', 'America', 'F', '1984-04-18', NULL),
	(25, 'McKinnon', 'Kate', 'F', '1984-01-06', NULL),
	(26, 'Ayer', 'David', 'H', '1968-01-18', NULL),
	(27, 'Smith', 'Will', 'H', '1968-09-25', NULL),
	(28, 'Leto', 'Jared', 'H', '1971-12-26', NULL),
	(29, 'Kinnaman', 'Joel', 'H', '1979-11-25', NULL),
	(30, 'Davis', 'Viola', 'F', '1965-08-11', NULL);

-- Listage de la structure de table cinema. realisateur
CREATE TABLE IF NOT EXISTS `realisateur` (
  `id_realisateur` int NOT NULL AUTO_INCREMENT,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id_realisateur`),
  UNIQUE KEY `id_personne` (`id_personne`),
  CONSTRAINT `realisateur_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.realisateur : ~5 rows (environ)
INSERT INTO `realisateur` (`id_realisateur`, `id_personne`) VALUES
	(1, 2),
	(2, 8),
	(3, 14),
	(4, 20),
	(5, 26);

-- Listage de la structure de table cinema. role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.role : ~26 rows (environ)
INSERT INTO `role` (`id_role`, `nom`) VALUES
	(1, 'Spider Man'),
	(2, 'Le Bouffon Vert'),
	(3, 'Mary Jane Watson'),
	(4, 'Harry Osborn'),
	(5, 'Ben Parker'),
	(6, 'May Parker'),
	(7, 'Leonard Hofstadter'),
	(8, 'Sheldon Cooper'),
	(9, 'Penny'),
	(10, 'Howard Wolowitz'),
	(11, 'Rajesh Koothrappali'),
	(12, 'Jake Sully'),
	(13, 'Neytiri'),
	(14, 'Docteur Grace Augustine'),
	(15, 'Colonel Miles Quaritch'),
	(16, 'Trudy Chacon'),
	(17, 'Barbie'),
	(18, 'Ken'),
	(19, 'Barbie Prix Nobel de Physique'),
	(20, 'Gloria'),
	(21, 'Barbie bizarre'),
	(22, 'Deadshot'),
	(23, 'Le Joker'),
	(24, 'Colonel Rick Flag'),
	(25, 'Amanda Waller'),
	(26, 'Harley Queen');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;



SELECT film.titre, personne.nom, personne.prenom, role.nom
FROM joue
INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
INNER JOIN personne ON acteur.id_personne = personne.id_personne
INNER JOIN film ON joue.id_film = film.id_film
INNER JOIN role ON joue.id_role = role.id_role