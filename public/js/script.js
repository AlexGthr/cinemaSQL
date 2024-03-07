let icons = document.querySelector('#icons'); // Recupère l'icone de la navBar (Menu burger)
let nav = document.querySelector('nav'); // Recupère la balise nav
let links = document.querySelectorAll('nav a');  // Récupère la balise A dans la nav
let listeDeroulante = document.querySelector('.listeDeroulante'); // Récupère la classe .listeDeroulante
let liste = document.querySelector('.sous-nav'); // Recupère la classe .sous-nav

// Si je clic sur l'icone de ma nav bar, j'ajoute ou supprime la classe Active de ma navbar
icons.addEventListener("click", () => {
    nav.classList.toggle("active");
});

// Si je clique sur ma liste déroulante, j'ajoute ou supprime la classe Active de ma liste (.sous-nav)
listeDeroulante.addEventListener("click", () => {
    liste.classList.toggle("active");
});


// Function qui gère l'url de redirection après un choix dans le formulaire de gestion d'ajout.
function rediriger() {
    let choixFormulaire = document.getElementById("choixFormulaire");
    let formulaireValue = choixFormulaire.value;
    window.location.href = 'index.php?action=gestion' + formulaireValue;
};

function redirigerTrieFilm() {
    let choixFormulaire = document.getElementById("orderby");
    let formulaireValue = choixFormulaire.value;
    window.location.href = 'index.php?action=listFilm&order=' + formulaireValue;
};

function redirigerTrieActeur() {
    let choixFormulaire = document.getElementById("orderby");
    let formulaireValue = choixFormulaire.value;
    window.location.href = 'index.php?action=listActeur&order=' + formulaireValue;
};



// Je récupère mes éléments avec l'ID note
let noteElements = document.querySelectorAll(".note");

// Je fais un forEach de mes éléments pour afficher une étoile
noteElements.forEach((noteElement) => {

    // Je passe ma value en INT
    let noteValue = parseInt(noteElement.textContent);
    
    // Je crée une variable qui va contenir les informations et le début de la div
    let starsHTML = "<div class='star'>";
    
    for (let i = 0; i < 5; i++) {
        if (i < noteValue) {
            starsHTML += "<i class='fa-solid fa-star yellow'></i>";
        } else {
            starsHTML += "<i class='fa-solid fa-star'></i>";
        }
    }
    
    starsHTML += "</div>";
    // Et je remplacer le contenu de l'élément span par les étoiles
    noteElement.innerHTML = starsHTML;
});

// Gestion de l'ajout d'une personne en Acteur/Réalisateur/les deux
document.addEventListener("DOMContentLoaded", function() {
    let formulaire = document.getElementById("personne");

    if (formulaire) {
        formulaire.addEventListener('submit', function () {
            let typeValue = formulaire.querySelector('[name="metier"]').value;
            formulaire.action += typeValue;
        });
    }
});

document.addEventListener("DOMContentLoaded", function() {
    let alertOn = document.getElementById("alertOn");
    let alertBox = document.querySelector(".alert");

    alertOn.addEventListener("click", function() {
    alertBox.classList.toggle('alertOnOff');
});

});

/*=============== SHOW SCROLL UP ===============*/ 
const scrollUp = () => {
    const scrollUp = document.getElementById('scroll-up')

    window.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
                        : scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)