/**************************************************************************/
/*Les options du menu prennent la taille du menu en-lui même*/

let compte = document.querySelector('#header .compte');
let options = document.querySelector('#header .compte-options');

options.style.width = compte.offsetWidth + 'px';

window.addEventListener('resize', function(){
    options.style.width = compte.offsetWidth + 'px';
    console.log('resize');
});