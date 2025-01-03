<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Comment;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentText = $_POST['comment'] ?? null;
    $id_post = $_POST['id_post'] ?? null;
    $id_user = 1; // Exemple : vous pouvez récupérer l'utilisateur connecté via $_SESSION

    if (empty($commentText) || empty($id_post)) {
        echo "Le commentaire ou l'identifiant du post est manquant.";
        exit;
    }

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
?>
