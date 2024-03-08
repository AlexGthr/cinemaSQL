<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Site d'information sur le cinéma">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">

    <title> <?= $titrePage; ?> </title>
</head>
<body>
    <header>

            <div class="header__bg">
                <a href="index.php"><h1>Movies</h1></a>
            </div>

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
            <input type="search" class="research" name="research" placeholder="Recherche film, acteur ...">
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


    <input type="search" class="research" name="research" placeholder="Recherche film, acteur ...">
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