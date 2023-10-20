const compteDiv = document.querySelector('.compte');
const compteOptionsDiv = document.querySelector('.compte-options');
let isCompteOptionsVisible = false;

compteDiv.addEventListener('click', () => {
    if (!isCompteOptionsVisible) {
        compteOptionsDiv.style.display = 'block';
        compteDiv.classList.add('shown');
        isCompteOptionsVisible = true;
    } else {
        compteOptionsDiv.style.display = 'none';
        compteDiv.classList.remove('shown');
        isCompteOptionsVisible = false;
    }
});
