let likes=document.getElementsByClassName('like')
console.log(likes)
Array.from(likes).forEach(elem => {
    elem.addEventListener('click', function() {
        console.log(this.childNodes[0].src)
        if (this.childNodes[0].src.includes("heart_white.svg")) {
            this.childNodes[0].src="/public/icons/heart_fill.svg"
        }
        else{
            this.childNodes[0].src="/public/icons/heart_white.svg"
        }
    });
});