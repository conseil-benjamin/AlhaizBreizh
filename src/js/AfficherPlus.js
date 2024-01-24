function AfficherPlus() {
    console.log("test");
    var moreText = document.getElementById("plus");
    var btnText = document.getElementById("myBtn");
  
    if (moreText.style.display != "none") {
      btnText.innerHTML = "Afficher plus";
      moreText.style.display = "none";
      console.log("test");
    } else {
      btnText.innerHTML = "Afficher moins";
      moreText.style.display = "inline";
    }
  } 