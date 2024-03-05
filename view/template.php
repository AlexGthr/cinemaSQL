<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Site d'information sur le cinéma">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="public/css/style.css">
    <title> <?= $titrePage; ?> </title>
</head>
<body>
    <header>

        <!-- Image MOVIE + titre -->
        <figure>
            <img src="public/img/Movies.png" title="Movies, nom du site">
            <figcaption>
                <a href="index.php"><h1>Movies</h1></a>
            </figcaption>
        </figure>

        <!-- Menu de navigation -->
        <nav>
            <ul>
                <li> <a href="index.php"> Acceuil </a></li>
                <li> 
                    <span class="listeDeroulante"> Liste &#x21b4; </span> <!-- Liste déroulante -->
                    <ul class="sous-nav"> 
                    <li> <a href="index.php?action=listFilm">Film</a></li>
                    <li> <a href="index.php?action=listActeur">Acteur</a></li>
                    <li> <a href="index.php?action=listReal">Réalisateur</a></li>
                    <li> <a href="index.php?action=listRole">Role</a></li>
                    <li> <a href="index.php?action=listCategorie">Catégorie</a></li>
                </ul>
                </li>
                <li> <a href="index.php?action=gestion"> Gestion </a></li>
            </ul>
            <div id="icons"></div> <!-- Icone menu Burger -->
                    <!-- Barre de recherche -->
            <div class="searchNav">

            <form action="index.php?action=research" method="post" enctype="multipart/form-data">
            <input type="search" id="research" name="research" placeholder="Recherche film, acteur ...">
            <button type="submit" name="submit" title="button"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
            </form>
        </nav>
        

    </header>

<main>

    <div class='backPage'>
        <?= $backLastPage ?>
    </div>

        <!-- Barre de recherche -->
    <div class="searchBar">

    <form action="index.php?action=research" method="post" enctype="multipart/form-data"> <!-- Formulaire pour envoyer un produit -->


    <input type="search" id="research" name="research" placeholder="Recherche film, acteur ...">
    <button type="submit" name="submit"><i class="fa-solid fa-magnifying-glass"></i></button>

</div>

</form>
    
    <?= $content; ?>


          <!--========== SCROLL UP ==========-->
        <a href="#" class="scrollup" id="scroll-up">
          <i class="fa-solid fa-arrow-up" title="scrollup"></i>
        </a>

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

    <script defer src="public/js/script.js"></script>
</body>
</html>