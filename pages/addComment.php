<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\Comment;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $id_post = $_POST['id_post'];
    $id_user = 1;

    try {
        // Créer l'objet Post
        $comment = new Comment(
            $comment,
            $id_user,
            $id_post
        );
        // Insérer le post dans la base de données
        $dbManager = new DbManagerCRUD();
        if ($dbManager->createComment($comment)) {
            echo "Commentaire ajouté avec succès !";
        } else {
            echo "Échec de l'ajout du commentaire.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>