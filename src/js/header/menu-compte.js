/**************************************************************************/
/*Les options du menu prennent la taille du menu en-lui même*/

let compte = document.querySelector('#header .compte');
let options = document.querySelector('#header .compte-options');

if(options !== null) {
    options.style.width = compte.offsetWidth + 'px';
}


window.addEventListener('resize', function(){
    if(options !== undefined) {
    options.style.width = compte.offsetWidth + 'px'; }
});