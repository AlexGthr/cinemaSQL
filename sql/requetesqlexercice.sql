-- a. Information d'un film (id_film) : titre, année, durée, (format HH:MM), et réalisateur

SELECT
	film.titre,
	film.dateDeSortie,
	time_format(SEC_TO_TIME(film.duree*60), '%H:%i') AS dureeFilm,
	personne.nom,
	personne.prenom
FROM film
INNER JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
INNER JOIN personne ON realisateur.id_realisateur = personne.id_personne
WHERE film.id_film = 1 -- Spider Man

-- b. Liste des films dont la durée excède 2h15 classés par durée (du + long au + court)

SELECT
	film.titre,
	time_format(SEC_TO_TIME(film.duree*60), '%H:%i') AS dureeFilm
FROM film
WHERE film.duree > 135
ORDER BY film.duree DESC

-- c. Liste des films d'un réalisateur (en précisant l'année de sortie)

SELECT
	personne.nom,
	personne.prenom,
	film.titre,
	YEAR(film.dateDeSortie)
FROM film
INNER JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
INNER JOIN personne ON realisateur.id_realisateur = personne.id_personne
WHERE realisateur.id_realisateur = 1

-- d. Nombre de films par genre (classés dans l'ordre décroissant)

SELECT
	categorie.type,
	COUNT(film.id_film) AS nbFilm
FROM categoriser
INNER JOIN categorie ON categoriser.id_categorie = categorie.id_categorie
INNER JOIN film ON categoriser.id_film = film.id_film
GROUP BY categorie.id_categorie
ORDER BY nbFilm DESC

-- e. Nombre de films par réalisateur (classés dans l'ordre décroissant)

SELECT
	CONCAT(personne.nom, ' ',personne.prenom) AS leRealisateur,
	COUNT(id_film) AS nbFilms
FROM film
INNER JOIN realisateur ON film.id_realisateur = realisateur.id_realisateur
INNER JOIN personne ON realisateur.id_realisateur = personne.id_personne
GROUP BY film.id_realisateur
ORDER BY nbFilms DESC

-- f. Casting d'un film en particulier (id_film) : nom, prénom des acteurs, et sexe.

SELECT
	film.titre, 
	personne.nom, 
	personne.prenom, 
	personne.sexe
FROM joue
INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
INNER JOIN personne ON acteur.id_personne = personne.id_personne
INNER JOIN film ON joue.id_film = film.id_film
INNER JOIN role ON joue.id_role = role.id_role

WHERE film.id_film = 2 -- Big bang theorie

-- g. Films tournés par un acteur en particulier (id_acteur) avec leur rôle et l'année de sortie (du film le plus récent au plus ancien)

SELECT 
	personne.nom, 
	personne.prenom, 
	film.titre, 
	role.nom AS role,
	film.dateDeSortie
FROM joue
INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
INNER JOIN personne ON acteur.id_personne = personne.id_personne
INNER JOIN film ON joue.id_film = film.id_film
INNER JOIN role ON joue.id_role = role.id_role
WHERE personne.id_personne = 21 -- Margot Robbie
ORDER BY film.dateDeSortie DESC

-- h. Liste des personnes qui sont à la fois acteurs et réalisateurs

SELECT *
FROM personne
WHERE personne.id_personne IN  
(
	SELECT acteur.id_personne
	FROM acteur
)
AND personne.id_personne IN 
(
	SELECT realisateur.id_personne
	FROM realisateur
)

-- i. Liste des films qui ont moins de 5 ans (classés du plus récent au plus ancien)

SELECT 
	film.titre,
	DATE_FORMAT(film.dateDeSortie, '%d %m %Y') AS dateDeSortie
FROM film
WHERE film.dateDeSortie >= DATE_SUB(NOW(), INTERVAL 5 YEAR)
ORDER BY film.dateDeSortie DESC

-- j. Nombre d'hommes et de femmes parmi les acteurs

-- affichage en deux colonne
SELECT
	SUM(if(personne.sexe='M',1,0)) AS Hommes,
	SUM(if(personne.sexe='F',1,0)) AS Femmes
FROM acteur
INNER JOIN personne ON acteur.id_acteur = personne.id_personne

-- affichage en une colonne
SELECT sexe, COUNT(*) AS nbActeurs
FROM acteur a
INNER JOIN personne p ON a.id_personne = p.id_personne
GROUP BY sexe

-- k. Liste des acteurs ayant plus de 50 ans (âge révolu et non révolu)

SELECT *
FROM personne
WHERE DATEDIFF(CURRENT_DATE, dateDeNaissance) / 365.25 > 50;

-- l. Acteur ayant joué dans 3 films ou plus 

SELECT 
	personne.nom, 
	personne.prenom, 
	COUNT(film.titre) AS nbFilm
FROM joue
INNER JOIN acteur ON joue.id_acteur = acteur.id_acteur
INNER JOIN personne ON acteur.id_personne = personne.id_personne
INNER JOIN film ON joue.id_film = film.id_film
GROUP BY personne.id_personne
HAVING nbFilm >= 3


	-- DATE_FORMAT(film.dateDeSortie, '%d/%m/%Y'),
	-- TIME_FORMAT(SEC_TO_TIME(film.duree*60), '%H:%i') AS dureeFilm
	-- CONCAT(personne.nom, ' ',personne.prenom) AS leRealisateur