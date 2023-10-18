const compteDiv = document.querySelector('.compte');
const compteOptionsDiv = document.querySelector('.compte-options');
let isCompteOptionsVisible = false;

compteDiv.addEventListener('click', () => {
    if (!isCompteOptionsVisible) {
        compteOptionsDiv.style.display = 'block';
        isCompteOptionsVisible = true;
    } else {
        compteOptionsDiv.style.display = 'none';
        isCompteOptionsVisible = false;
    }
});
