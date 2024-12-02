<?php
require_once 'vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Personne;

$personne = new Personne("agathe", "makumbi", "makagathe7@gmail.com", "0779553315", "shitIsReal");
echo $personne;
echo "<br>";

$db = new DbManagerCRUD();
echo $db;
echo "<br>";
echo $db->showCategories();

$dbPath = '/Applications/MAMP/htdocs/ForumVaudois/db/forumvaudois.db';
if (!file_exists($dbPath)) {
    die("Database file not found: " . $dbPath);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Accueil</title>
</head>

<body>
    <?php include './components/header.php'?>

    <main class="main-content">
        <h1 class="welcome-text">Bienvenue sur Secret News</h1>
        <p class="subtitle">Découvrez notre plateforme et rejoignez notre communauté grandissante. Connectez-vous ou inscrivez-vous pour accéder à tout le contenu exclusif.</p>
        
    </main>
</body>

</html>