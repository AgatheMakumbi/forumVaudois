<?php
require_once 'vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;

// Initialisation de la connexion avec la base de données
$db = new DbManagerCRUD();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css?v=1">
    <title>Forum Vaudois - Accueil</title>
</head>

<body>
    <!-- Inclusion de la barre de navigation -->
    <?php include './components/header.php' ?>

    <main class="main-content">
        <section class="welcome-section">
            <br>
            <br>
            <h1>Forum Vaudois</h1>
            <br>
            <p>
                Bienvenue sur le forum vaudois ! Un espace d’échange où vous pouvez discuter librement de tout ce qui touche au canton de Vaud et bien plus encore. 
                <br>#LibertéDExpression
            </p>
            <br>
        </section>

        <section class="thread-section">
            <a href="activite.php" class="thread-btn">🪂 Rubrique Activité 🪂</a>
            <a href="food.php" class="thread-btn">🍴 Rubrique Food 🍴</a>
            <a href="nature.php" class="thread-btn">🌿 Rubrique Nature 🌿</a>
            <a href="culture.php" class="thread-btn">🎥 Rubrique Culture 🎥</a>
        </section>
    </main>

    <!-- Inclusion du footer -->
    <?php include './components/footer.php' ?>
</body>

</html>
