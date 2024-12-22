<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>À Propos</title>
</head>

<body>
    <?php include '../components/header.php'; ?>
    <main class="main-content-about">
    <section class="introduction">
        <div class="content">
            <h2>Introduction</h2>
            <p>Bienvenue sur le Forum Vaudois, une plateforme dédiée aux échanges et aux discussions pour les habitants et les visiteurs du canton de Vaud. Notre objectif est de faciliter la communication, de promouvoir les activités locales, et de renforcer les liens entre les membres de la communauté. Que vous soyez à la recherche d'événements, de bons plans, ou simplement d'un espace pour partager vos idées, le Forum Vaudois est là pour vous.</p>
        </div>
        <div class="objectives">
            <h2>Objectifs et mission</h2>
            <ul>
                <li>Promouvoir les lieux et activités locales : Offrir une visibilité aux événements culturels, aux entreprises locales et aux initiatives communautaires.</li>
                <li>Faciliter les échanges : Proposer un espace de discussion convivial et inclusif où chacun peut contribuer.</li>
                <li>Renforcer la communauté : Créer un sentiment d'appartenance en favorisant la collaboration et le partage d'expériences.</li>
            </ul>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team">
        <h2>L'équipe</h2>
        <div class="team-members">
            <div class="member">
                <div class="icon">AJ</div>
                <p>Abessolo Joshua</p>
                <p class="role">Développeur Frontend</p>
            </div>
            <div class="member">
                <div class="icon">DM</div>
                <p>Desgalier Michael</p>
                <p class="role">Développeur Backend</p>
            </div>
            <div class="member">
                <div class="icon">MJ</div>
                <p>Jaccard Mathilde</p>
                <p class="role">Cheffe du Projet</p>
            </div>
            <div class="member">
                <div class="icon">MA</div>
                <p>Makumbi Agathe</p>
                <p class="role">Développeuse Backend</p>
            </div>
            <div class="member">
                <div class="icon">MJ</div>
                <p>Mayor Joanah</p>
                <p class="role">Experte en Base de Données</p>
            </div>
        </div>
    </section>
</main>
<!-- Footer Inclusion -->
<?php include '../components/footer.php'; ?>
</body>

</html>
