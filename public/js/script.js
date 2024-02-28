let icons = document.querySelector('#icons')
let nav = document.querySelector('nav')
let links = document.querySelectorAll('nav a')
let listeDeroulante = document.querySelector('.listeDeroulante')
let liste = document.querySelector('.sous-nav')

icons.addEventListener("click", () => {
    nav.classList.toggle("active");
})

listeDeroulante.addEventListener("click", () => {
    liste.classList.toggle("sous-nav");
})



function rediriger() {
    let choixFormulaire = document.getElementById("choixFormulaire");
    let formulaireValue = choixFormulaire.value;
    window.location.href = 'index.php?action=gestion' + formulaireValue;
}




// Je récupère mes éléments avec l'ID note
let noteElements = document.querySelectorAll("#note");

// Je fais un forEach de mes éléments pour afficher une étoile
noteElements.forEach(function(noteElement) {
    
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

document.addEventListener("DOMContentLoaded", function() {
    let formulaire = document.getElementById("personne");

    if (formulaire) {
        formulaire.addEventListener('submit', function () {
            let typeValue = formulaire.querySelector('[name="metier"]').value;
            formulaire.action += typeValue;
        });
    }
});