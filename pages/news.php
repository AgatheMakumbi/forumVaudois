<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Personne;

$personne = new Personne("agathe", "makumbi", "makagathe7@gmail.com", "0779553315", "shitIsReal");
echo $personne->__toString2();  
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>NewsFeed</title>
</head>

<body>
    <?php include '../components/header.php' ?>
    <main>
        <!-- IL faut récupérer la valeur de category en GET et display seulement les postes de cettes catégory pour ça il faut créer une methode qui prend en paramètre la catégory et qui select que les postes de cette catégory et return un tableau de post de cette catégory. Après ici on peut boucler sur ce tableau et afficher les postes. Si rien n'est envoyé en paramètre donc qu'il n'y a pas de catégory on display tous les postes toutes catégories confuse.-->
        <h1>C'est bien ici</h1>
    </main>

</body>

</html>