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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.acteur : ~31 rows (environ)
INSERT INTO `acteur` (`id_acteur`, `id_personne`) VALUES
	(1, 1),
	(2, 3),
	(3, 4),
	(4, 5),
	(5, 6),
	(6, 7),
	(28, 8),
	(7, 9),
	(8, 10),
	(9, 11),
	(10, 12),
	(11, 13),
	(29, 14),
	(12, 15),
	(13, 16),
	(14, 17),
	(15, 18),
	(16, 19),
	(38, 20),
	(17, 21),
	(18, 22),
	(19, 23),
	(20, 24),
	(21, 25),
	(23, 27),
	(24, 28),
	(25, 29),
	(26, 30),
	(30, 31),
	(33, 32);

-- Listage de la structure de table cinema. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.categorie : ~6 rows (environ)
INSERT INTO `categorie` (`id_categorie`, `type`) VALUES
	(1, 'Fantastique'),
	(2, 'Super héros'),
	(3, 'Action'),
	(4, 'Sitcom'),
	(5, 'Humour'),
	(6, 'Horreur');

-- Listage de la structure de table cinema. categoriser
CREATE TABLE IF NOT EXISTS `categoriser` (
  `id_film` int NOT NULL AUTO_INCREMENT,
  `id_categorie` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_categorie`),
  KEY `id_categorie` (`id_categorie`),
  CONSTRAINT `categoriser_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id_film`),
  CONSTRAINT `categoriser_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.categoriser : ~19 rows (environ)
INSERT INTO `categoriser` (`id_film`, `id_categorie`) VALUES
	(1, 1),
	(3, 1),
	(4, 1),
	(5, 1),
	(6, 1),
	(11, 1),
	(19, 1),
	(1, 2),
	(5, 2),
	(6, 2),
	(11, 2),
	(1, 3),
	(3, 3),
	(4, 3),
	(5, 3),
	(6, 3),
	(11, 3),
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
  `note` decimal(6,2) NOT NULL,
  `affiche` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_realisateur` int NOT NULL,
  PRIMARY KEY (`id_film`),
  KEY `id_realisateur` (`id_realisateur`),
  CONSTRAINT `film_ibfk_1` FOREIGN KEY (`id_realisateur`) REFERENCES `realisateur` (`id_realisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.film : ~7 rows (environ)
INSERT INTO `film` (`id_film`, `titre`, `dateDeSortie`, `duree`, `synopsis`, `note`, `affiche`, `id_realisateur`) VALUES
	(1, 'Spider Man', '2002-06-12', 121, 'L\'histoire d\'un homme qui se fais piquer par une araignée et deviens un super héros', 4.00, 'public/img/afficheFilm/spiderman.webp', 1),
	(2, 'The Big Bang Theory', '2024-09-15', 165, 'Découvrez la vie et les galères de trois scientifiques et un ingénieur.', 5.00, 'public/img/afficheFilm/65e3bf184a38a1.50126003.webp', 2),
	(3, 'Avatar', '2009-12-16', 162, 'Sur le monde extraterrestre luxuriant de Pandora vivent les Na\'vi, des êtres qui semblent primitifs, mais qui sont très évolués.', 5.00, 'public/img/afficheFilm/avatar.webp', 3),
	(4, 'Barbie', '2023-07-19', 114, 'Barbie, qui vit à Barbie Land, est expulsée du pays pour être loin d\'être une poupée à l\'apparence parfaite; n\'ayant nulle part où aller, elle part pour le monde humain et cherche le vrai bonheur.', 5.00, 'public/img/afficheFilm/barbie.webp', 4),
	(5, 'Suicide Squad', '2016-08-03', 123, 'Face à une menace aussi énigmatique qu\'invincible, l\'agent secret Amanda Waller réunit une armada de crapules de la pire espèce. Armés jusqu\'aux dents par le gouvernement, ces super-méchants s\'embarquent alors pour une mission-suicide.', 3.00, 'public/img/afficheFilm/suicidesquad.webp', 5),
	(6, 'PHP - Le film', '2024-01-17', 167, 'Un film ou le héros sauve le monde avec une function PHP', 3.00, 'public/img/afficheFilm/phplefilm.webp', 3),
	(11, 'Kill Bill', '2003-11-26', 135, 'Au cours d&#39;une cérémonie de mariage en plein désert, un commando fait irruption dans la chapelle et tire sur les convives. Laissée pour morte, la mariée enceinte retrouve ses esprits après un coma de quatre ans.', 3.00, 'public/img/afficheFilm/65e4c4cb4c22a4.82673922.webp', 10),
	(19, 'Teste', '1444-12-14', 144, '1444', 1.00, './public/img/afficheFilm/65e5ca8253b686.69658823.webp', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.joue : ~29 rows (environ)
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
	(6, 9, 28),
	(2, 10, 10),
	(2, 11, 11),
	(3, 12, 12),
	(3, 13, 13),
	(3, 14, 14),
	(3, 15, 15),
	(3, 16, 16),
	(4, 17, 17),
	(5, 17, 26),
	(6, 17, 27),
	(4, 18, 18),
	(6, 18, 36),
	(4, 19, 19),
	(4, 20, 20),
	(4, 21, 21),
	(5, 23, 22),
	(6, 23, 37),
	(5, 24, 23),
	(5, 25, 24),
	(5, 26, 25),
	(11, 30, 29);

-- Listage de la structure de table cinema. personne
CREATE TABLE IF NOT EXISTS `personne` (
  `id_personne` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `sexe` varchar(50) NOT NULL,
  `dateDeNaissance` date NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.personne : ~32 rows (environ)
INSERT INTO `personne` (`id_personne`, `nom`, `prenom`, `sexe`, `dateDeNaissance`, `photo`) VALUES
	(1, 'Maguire', 'Tobey', 'M', '1975-06-27', 'public/img/personne/default.png'),
	(2, 'Raimi', 'Sam', 'M', '1959-10-23', 'public/img/personne/samraimi.jpg'),
	(3, 'Dunst', 'Kirsten', 'F', '1982-04-30', 'public/img/personne/65e47d9ee6fd26.74255631.jpg'),
	(4, 'James Dafoe', 'William', 'M', '1955-07-22', 'public/img/personne/default.png'),
	(5, 'Edward Franco', 'James', 'M', '1978-04-19', 'public/img/personne/default.png'),
	(6, 'Robertson', 'Cliff', 'M', '1923-09-09', 'public/img/personne/default.png'),
	(7, 'Ann Harris', 'Rosemary', 'F', '1927-09-19', 'public/img/personne/65e47f166c4f02.11407255.jpg'),
	(8, 'Lorre', 'Chuck', 'M', '1952-10-18', 'public/img/personne/chucklorre.jpg'),
	(9, 'Galecki', 'John Mark', 'M', '1975-04-30', 'public/img/personne/default.png'),
	(10, 'Parsons', 'James Joseph', 'M', '1973-03-24', 'public/img/personne/default.png'),
	(11, 'Cuoco', 'Kaley', 'F', '1985-11-30', 'public/img/personne/65e47ff6d9ed07.82609826.jpg'),
	(12, 'Helberg', 'Simon', 'M', '1980-12-09', 'public/img/personne/default.png'),
	(13, 'Nayyar', 'Kunal', 'M', '1981-04-30', 'public/img/personne/default.png'),
	(14, 'Cameron', 'James', 'M', '1954-08-16', 'public/img/personne/jamescameron.png'),
	(15, 'Worthington', 'Samuel', 'M', '1976-08-02', 'public/img/personne/default.png'),
	(16, 'Saldana', 'Zoe', 'F', '1978-06-19', 'public/img/personne/default.png'),
	(17, 'Weaver', 'Sigourney', 'F', '1949-10-08', 'public/img/personne/default.png'),
	(18, 'Lang', 'Stephen', 'M', '1952-07-11', 'public/img/personne/default.png'),
	(19, 'Rodriguez', 'Michelle', 'F', '1978-07-12', 'public/img/personne/default.png'),
	(20, 'Gerwing', 'Greta', 'F', '1983-08-04', 'public/img/personne/default.png'),
	(21, 'Robbie', 'Margot', 'F', '1990-07-02', 'public/img/personne/margotrobbie.jpg'),
	(22, 'Gosling', 'Ryan', 'M', '1980-11-12', 'public/img/personne/ryangosling.jpeg'),
	(23, 'Mackey', 'Emma', 'F', '1996-01-04', 'public/img/personne/default.png'),
	(24, 'Ferrera', 'America', 'F', '1984-04-18', 'public/img/personne/default.png'),
	(25, 'McKinnon', 'Kate', 'F', '1984-01-06', 'public/img/personne/default.png'),
	(26, 'Ayer', 'David', 'M', '1968-01-18', 'public/img/personne/default.png'),
	(27, 'Smith', 'Will', 'M', '1968-09-25', 'public/img/personne/willsmith.jpg'),
	(28, 'Leto', 'Jared', 'M', '1971-12-26', 'public/img/personne/default.png'),
	(29, 'Kinnaman', 'Joel', 'M', '1979-11-25', 'public/img/personne/default.png'),
	(30, 'Davis', 'Viola', 'F', '1965-08-11', 'public/img/personne/default.png'),
	(31, 'Pitt', 'Brad', 'M', '1963-12-18', 'public/img/personne/65ddaf358035a2.01262058.jpg'),
	(32, 'Tarantino', 'Quentin', 'M', '1963-03-27', 'public/img/personne/65ddb40bef79a8.44615046.jpg');

-- Listage de la structure de table cinema. realisateur
CREATE TABLE IF NOT EXISTS `realisateur` (
  `id_realisateur` int NOT NULL AUTO_INCREMENT,
  `id_personne` int NOT NULL,
  PRIMARY KEY (`id_realisateur`),
  UNIQUE KEY `id_personne` (`id_personne`),
  CONSTRAINT `realisateur_ibfk_1` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.realisateur : ~6 rows (environ)
INSERT INTO `realisateur` (`id_realisateur`, `id_personne`) VALUES
	(1, 2),
	(2, 8),
	(3, 14),
	(4, 20),
	(5, 26),
	(10, 32);

-- Listage de la structure de table cinema. role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table cinema.role : ~29 rows (environ)
INSERT INTO `role` (`id_role`, `nom`) VALUES
	(1, 'Peter Parker'),
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
	(26, 'Harley Queen'),
	(27, 'La chef'),
	(28, 'Le Maitre codeur'),
	(29, 'Le Méchant'),
	(36, 'Le traitre'),
	(37, 'Le sniper');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
