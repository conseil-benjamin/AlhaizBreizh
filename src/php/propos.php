<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos</title>
    <link rel="icon" href="/public/logos/logo-black.svg">
    <link rel="stylesheet" type="text/css" href="/src/styles/styles.css">
    <link rel="stylesheet" type="text/css" href="/src/styles/stylePageMentionAPropos.css">
</head>
<body>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/src/php/header.php'); ?>
    <video id="background" autoplay loop muted>
            <source src="/public/videos/video-bretagne.mp4" type="video/mp4">
        </video>
    <main>
        <section class="quiNOus">
            <h1>Qui sommes nous ?</h2>
        </section>
        <section class="association">
            <div>
                <h2>À propos de l'association</h2>
                <p>Notre plateforme fait le lien entre les propriétaires de logements en Bretagne et les voyageurs à la recherche d'une expérience authentique. Conçue et développée de manière indépendante, la PLS offre des fonctionnalités spécifiques à la location saisonnière, tout en respectant les normes de sécurité et de confidentialité des données.</p>
            </div>
            <img src="/public/logos/logo-white.svg" alt="Logo Association">
        </section>
        <section class="secteur">
            
            <div>
                <h2>Notre Secteur</h2>
                <p>ALHaIZ Breizh est une association à but non lucratif, dont l'objectif est de promouvoir le tourisme en Bretagne et de soutenir les propriétaires de logements locatifs dans la région. Nous sommes fiers de contribuer à l'économie locale et de participer au rayonnement de la Bretagne à l'international.</p>
            </div>
        </section>
        <section class="eco">
            <h2>Engagement Éco-Responsable</h2>
            <p>ALHaIZ Breizh a instauré une Charte d'éthique et d'éco-responsabilité, encourageant la mise à disposition de logements non exclusivement dédiés à la location. Nous sommes fiers de contribuer à la préservation de l'environnement et à la promotion d'un tourisme durable en Bretagne.</p>
        </section>
        <section class="secteur">
            <h2>Notre Équipe</h2>
            <p>Notre association est composée d'une équipe dévouée de membres et de bénévoles, tous passionnés par la promotion de notre belle région et la satisfaction des voyageurs.</p>
        </section>
        <section class="eco">
            <h2>Contact</h2>
            <p>Pour toute question, suggestion ou demande d'information, n'hésitez pas à nous contacter à l'adresse suivante : <a href="mailto:contact@alhaiz-breizh.fr">contact@alhaiz-breizh.fr</a>.</p>
        </section>
        <p id="fin"><br></p>
</main>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/src/php/footer.php';?>
</body>
</html>
