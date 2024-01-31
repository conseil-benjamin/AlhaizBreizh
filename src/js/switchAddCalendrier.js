document.addEventListener("DOMContentLoaded", function () {
  var select = document.getElementById("select-form");
  var forms = document.getElementById("forms").children;

  // Initialisation : Afficher le premier formulaire et masquer les autres
  for (var i = 2; i < forms.length; i++) {
    forms[i].style.display = "none";
  }

  // Fonction pour basculer entre les formulaires en fonction de la sélection
  select.addEventListener("change", function () {
    for (var i = 0; i < forms.length; i++) {
      if (forms[i].id === select.value) {
        forms[i].style.display = "block";
      } else if (forms[i].className != "select") {
        forms[i].style.display = "none";
      }
    }
  });
});
