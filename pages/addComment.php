<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Comment;

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
    $commentText = $_POST['comment'];
    $id_post = $_POST['id_post'];
    $id_user = $_SESSION["id"];

    try {
        // Créez un objet Comment
        $comment = new Comment($commentText, $id_user, $id_post);

        // Enregistrez le commentaire dans la base de données
        $dbManager = new DbManagerCRUD();
        if ($dbManager->createComment($comment)) {
            header("Location: postDetails.php?id_post=" . $id_post);
            exit;
        } else {
            echo "Erreur lors de l'ajout du commentaire.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
