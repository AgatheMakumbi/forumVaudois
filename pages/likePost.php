<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\Like;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    // Redirige vers la page de connexion
    header("Location: login.php");
    exit; // Arrête l'exécution pour éviter de charger le reste de la page
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = $_POST['id_post'];
    $id_user = $_SESSION["id"];

    try {
        // Créer l'objet Post
        $like = new Like(
            $id_user,
            $id_post
        );
        // Insérer le post dans la base de données
        $dbManager = new DbManagerCRUD();

        if ($dbManager->createLike($like)) {
            header("Location: postDetails.php?id_post=" . $id_post);
            exit;
            //echo "Like ajouté avec succès !";
        } else {
            header("Location: postDetails.php?id_post=" . $id_post);
            exit;
            //echo "Échec de l'ajout du Like.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>