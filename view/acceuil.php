<?php 
    ob_start();
?>


<div class="swiper mySwiper">
    <div class="swiper-wrapper">

      <div class="swiper-slide">

          <img src="./public/img/bobmarley.jpg" alt="carousselbobmarley">

        <div class="swiperCadreText">
            <h2>Bob Marley review</h2>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. </p>
        </div>
      </div>
      
      <div class="swiper-slide">
        <img src="./public/img/bobmarley.jpg" alt="carousselbobmarley">
        <div class="swiperCadreText">
            <h2>Bob Marley review</h2>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. </p>
        </div>
      </div>

      <div class="swiper-slide">
        <img src="./public/img/bobmarley.jpg" alt="carousselbobmarley">
        <div class="swiperCadreText">
            <h2>Bob Marley review</h2>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. </p>
        </div>
      </div>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
  </div>

    <h1 id="titleSection" class="SectionTitle">A l'affiche !</h1>

<div class="afficherTitreFilm">

        <!-- Affiche les affiches d'un film et le titre -->
    <?php 
        foreach($requete->fetchAll() as $film) { ?>
        <div class="film">
            
        <a href="index.php?action=detFilm&id=<?= $film["id_film"] ?>">
            <img class="afficheFilm" src='<?= $film["affiche"] ?>' title='<?= $film["titre"] ?>'>
        </a>
            <a href="index.php?action=detFilm&id=<?= $film["id_film"] ?>"><?= $film["titre"] ?></a>
        
        </div>
        <?php } ?>

</div>

<div class="home__buttons">
        <a href='index.php?action=listFilm' class="button viewAll">
            <span>
                <i class="fa-solid fa-arrow-right"></i>
            </span>
                View all
        </a>
</div>

    <h1 id="titleSection" class="SectionTitle">Acteur star !</h1>

<div class="afficherActeurs">

        <!-- Affiche la photo d'un acteur et son nom/prenom -->
    <?php 
        foreach($requeteA->fetchAll() as $acteur) { ?>
        <div class="acceuil">
            
        <a href='index.php?action=detPersonne&id=<?= $acteur['id_personne']?>'>
            <img class="afficheActeur" src='<?= $acteur["photo"] ?>' title='<?= $acteur["acteur"] ?>'>
        </a>
            <a href='index.php?action=detPersonne&id=<?= $acteur['id_personne']?>'><?= $acteur["acteur"] ?></a></br>
        
        </div>
        <?php } ?>

</div>

<div class="home__buttons">
        <a href='index.php?action=listActeur' class="button viewAll">
            <span>
                <i class="fa-solid fa-arrow-right"></i>
            </span>
                View all
        </a>
</div>

    <h1 id="titleSection">Réalisateur du moment !</h1>

<div class="afficherRealisateurs">

            <!-- Affiche la photo d'un réalisateur et son nom/prenom -->
    <?php 
        foreach($requeteR->fetchAll() as $realisateur) { ?>
        <div class="acceuil">

        <a href='index.php?action=detPersonne&id=<?= $realisateur['id_personne']?>'>
            <img class="afficheReal" src='<?= $realisateur["photo"] ?>' title='<?= $realisateur["realisateur"] ?>'>
        </a>
            <a href='index.php?action=detPersonne&id=<?= $realisateur['id_personne']?>'><?= $realisateur["realisateur"] ?></a></br>
        
        </div>
        <?php } ?>

</div>
        <div class="home__buttons">
                <a href='index.php?action=listReal' class="button viewAll">
                        <span>
                        <i class="fa-solid fa-arrow-right"></i>
                        </span>
                        View all
                     </a>
        </div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
        var swiper = new Swiper(".mySwiper", {
        navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
        },
        pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
        // autoplay: {
        //     delay: 5000,
        //     },
  });
</script>

<?php

    $titrePage = "Movies - Acceuil";
    $backLastPage = "";
    $content = ob_get_clean();

    require_once "view/template.php"; 
?>