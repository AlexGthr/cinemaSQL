<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <title> <?= $titrePage; ?> </title>
</head>
<body>
    <header>

        <figure>
            <img src="img/Movies.png" title="Movies, nom du site">
            <figcaption>
                <h1>Movies</h1>
            </figcaption>
        </figure>

        <nav>
            <ul>
                <li> <a href="index.php"> Acceuil </a></li>
                <li> <a href="listeFilm.php"> Liste Film </a></li>
                <li> <a href="recap.php"> Infos </a></li>
            </ul>
            <div id="icons"></div>
        </nav>
        

    </header>

<main>
    <div class="searchBar">
        <input type="search" id="research" name="research" placeholder="&nbsp;&nbsp;&nbsp;Recherche ...">
        <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
    </div>
    
    <?= $content; ?>

</main>

    <footer>
        <h2>Contact</h2>
        <p>monmail@monmail.fr <i class="fa-regular fa-envelope"></i></p>

        <div class="reseau">
            <i class="fa-brands fa-facebook"></i>
            <i class="fa-brands fa-linkedin"></i>
        </div>

        <p id="copyright">Copyright Alex -&nbsp;<a href="#">Mentions légal </a></p>
    </footer>

    <script defer src="js/script.js"></script>
</body>
</html>