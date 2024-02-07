const compteDiv = document.querySelector('.compte');
const compteOptionsDiv = document.querySelector('.compte-options');

const dropDownTelDiv = document.querySelector('.dropdown-tel')
const dropdownButton = document.getElementById('dropdownButton')


let isCompteOptionsVisible = false;
let isdropDownTelDivVisible = false

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


dropdownButton?.addEventListener('click', ()=> {
    if (!isdropDownTelDivVisible) {
        dropDownTelDiv.style.display = 'block';
        isdropDownTelDivVisible = !isdropDownTelDivVisible
    } else {
        dropDownTelDiv.style.display = 'none';
        isdropDownTelDivVisible = !isdropDownTelDivVisible
    }
})