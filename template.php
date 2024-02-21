<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title> <?= $titrePage; ?> </title>
</head>
<body>
    <header>
        <label>
            <input type="checkbox">
            <span class="menu"><span class="hamburger"></span> </span>
            <ul>
                <li> <a href="index.php"> Acceuil </a></li>
                <li> <a href="listeFilm.php"> Liste Film </a></li>
                <li> <a href="recap.php"> Infos </a></li>
            </ul>
        </label>

        <figure>
            <img src="img/Movies.png" title="Movies, nom du site">
        </figure>

    </header>

    <?= $content; ?>
</body>
</html>