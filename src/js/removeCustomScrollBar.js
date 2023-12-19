// Retire la custom scrollbar lorsqu'on est sur mobile
function removeCustomScrollBar() {
    let userAgent = navigator.userAgent || window.opera;
    let mobileRegExp = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i;

    if (mobileRegExp.test(userAgent)) {
        //Retirer le ::-webkit-scrollbar
        let scrollbar = document.querySelector('::-webkit-scrollbar');
        scrollbar.style.display = 'none';
    }
}