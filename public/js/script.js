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