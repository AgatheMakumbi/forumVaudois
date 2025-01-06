<?php

// Inclut les dépendances nécessaires
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Post;

// Démarre la session 
session_start();

/**
 * @var DbManagerCRUD $dbManager Instance de la classe DbManagerCRUD pour gérer les interactions avec la base de données
 */
$dbManager = new DbManagerCRUD();

/**
 * Vérifie si l'identifiant du post est présent dans l'URL
 * Si non, redirige vers la page de profil
 */
if (!isset($_GET['id_post']) || empty($_GET['id_post'])) {
    header("Location: profil.php"); // Redirige vers le profil
    exit;
}

/**
 * Récupère l'identifiant du post dans l'URL
 * @var int $idPost Identifiant du post à modifier 
 */
$idPost = (int)$_GET['id_post'];

/**
 * Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion.
 */
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("Location: login.php"); // Redirige vers la page de connexion
    exit;
}

// Supprime le post

try {
    // Récupère les informations du post à modifier
    $post = $dbManager->getPostById($idPost);

    /**
     * Vérifie si le post existe et si l'utilisateur est l'auteur du post,
     * sinon redirige vers la page de profil.
     */
    if (!$post || $post->getAuthor() !== $_SESSION['id']) {
        header("Location: profil.php"); // Redirige si l'utilisateur n'est pas l'auteur du post
        exit;
    }

    //  supression
            $supressionOk = $dbManager->deletePost($idPost);
            // Enregistrement des modifications dans la base de données
            if ($supressionOk) {
                header("Location: profil.php"); // Redirige vers le profil après la mise à jour
                exit;
            } else {
                // En cas d'erreur lors de la supression dans la base de données
                echo "Échec de la supression du post.";
            }
        
    
} catch (Exception $e) {
    // En cas d'erreur lors de la récupération ou de la supression à jour du post
    echo "Erreur lors de la récupération ou supression du post : " . $e->getMessage();
    exit;
}
?>



