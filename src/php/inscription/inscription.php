<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="/src/styles/inscription.css" rel="stylesheet" type="text/css">
    <link href="/src/styles/styles.css" rel="stylesheet" type="text/css">
    <title>S'inscrire</title>
</head>
<body>
<div id="contenu">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/header.php'); ?>
    <h1> Inscription </h1>
    <form action="inscriptionValidation.php" enctype="multipart/form-data" method="POST" id="formulaire">
        <div>
            <div id="infosPerso">
                <!-- Nom -->
                <div class="labelGrp">
                    <label for="nom">Nom :</label>
                    <input id="nom" name="nom" required type="text">
                </div>

                <div class="labelGrp">
                    <!-- Prénom -->
                    <label for="prenom">Prénom :</label>
                    <input id="prenom" name="prenom" required type="text">
                </div>

                <div class="labelGrp">
                    <!-- Numéro de téléphone -->
                    <label for="num_tel">Numéro de téléphone :</label>
                    <input id="num_tel" name="num_tel" required type="tel" maxlength="14" placeholder="ex : 01 23 45 67 89">
                </div>

                <div id="civiliteDateNaissance">
                    <!-- Civilité -->
                    <div class="labelGrp">
                        <label for="civilite">Civilité :</label>
                        <select id="civilite" name="civilite" required>
                            <option value="M">Monsieur</option>
                            <option value="Mme">Madame</option>
                            <option value="Mme">Autre</option>
                        </select>
                    </div>

                    <div class="labelGrp">
                        <!-- Date de naissance -->
                        <label for="date_naissance">Date de naissance :</label>
                        <input id="date_naissance" name="date_naissance" required type="date" min="01-01-1901">
                    </div>
                </div>
                <div class="labelGrp">
                    <!-- Adresse -->
                    <label for="adresse">Adresse :</label>
                    <input id="adresse" name="adresse" required type="text" placeholder="ex : 27 avenue des Champs">
                </div>

                <div id="codePostalVille">
                    <div class="labelGrp">
                        <!-- Code postal -->
                        <label for="code_postal">Code postal :</label>
                        <input id="code_postal" name="code_postal" required type="text" maxlength="5">
                    </div>

                    <div class="labelGrp">
                        <!-- Ville -->
                        <label for="ville">Ville :</label>
                        <input id="ville" name="ville" required type="text">
                    </div>
                </div>
            </div>


            <div id="infosCompte">

                <div class="labelGrp">
                    <!-- Identifiant -->
                    <label for="identifiant">Identifiant :</label>
                    <input id="identifiant" name="identifiant" required type="text">
                </div>

                <div class="labelGrp">
                    <!-- Email -->
                    <label for="email">Email :</label>
                    <input id="email" name="email" required type="email">
                </div>


                <div class="labelGrp">
                    <!-- Mot de passe -->
                    <label for="mdp">Mot de passe :</label>
                    <input id="mdp" name="mdp" required type="password">
                </div>

                <div class="labelGrp">
                    <!-- Confirmer le mot de passe -->
                    <label for="confirmer_mdp">Confirmer le mot de passe :</label>
                    <input id="confirmer_mdp" name="confirmer_mdp" required type="password">
                </div>

                <h2>Les champs marqués de (?) sont optionnels, les autres sont obligatoires</h2>
                <div class="labelGrp">
                    <label for="photo_profil" id="photoProfil"> <img src="/public/icons/user.svg" alt="Illustration photo de profile"/> Choisir une photo de profil : (?)</label>
                    <input accept="image/*" id="photo_profil" name="photo_profil" type="file">
                </div>
            </div>
        </div>
        <div id="validation">
            <!-- Bouton de soumission -->
            <button class="boutton" type="submit" id="valider">Créer son compte</button>
            <div>
                <label for="accept_conditions">J'ai lu et j'accepte les Conditions Générales d'Utilisation, la Politique
                    des données personnelles et les Conditions Générales de Ventes d’Alhaiz Breizh</label>
                <input id="accept_conditions" name="accept_conditions" required type="checkbox">
            </div>
        </div>
    </form>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/src/php/footer.php'); ?>
</body>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="../../js/inscription.js"></script>
</html>