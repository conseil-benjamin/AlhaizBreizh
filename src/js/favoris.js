async function addFavoris(nb) {
    let rep =  await fetch('/src/php/chargerFavoris.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            json: 1,
            num: nb
        })
    });
    console.log("test01");
}

async function delFavoris(nb) {
    let rep =  await fetch('/src/php/chargerFavoris.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            json: 2,
            num: nb
        })
    });
    console.log("test");
}

function fav() {
    let likes = document.getElementsByClassName('like');
    Array.from(likes).forEach(elem => {
        elem.addEventListener('click', function () {
            if (this.childNodes[0].src.includes("heart_white.svg")) {
                this.childNodes[0].src = "/public/icons/heart_fill.svg";
                let idNumber = parseInt(this.parentNode.parentNode.id.split('logement')[1]);
                addFavoris(idNumber);
            }
            else {
                this.childNodes[0].src = "/public/icons/heart_white.svg";
                let idNumber = parseInt(this.parentNode.parentNode.id.split('logement')[1]);
                delFavoris(idNumber);
            }
        });
    });
}
fav();