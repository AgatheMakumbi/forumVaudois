<?php

/**
 * Script qui permet de traiter l'ajout d'un commentaire depuis la page postDetails
 */

// Inclut les dépendances nécessaires 
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Comment;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

// Démarre la session
session_start();

// Vérifie la session utilisateur 
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    /**
     * Redirige l'utilisateur vers la page de connexion si l'utilisateur n'est pas connecté
     * 
     * @return void
     */
    header("Location: login.php");
    exit; // Arrête l'exécution du script 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Traitement de l'ajout d'un commentaire via la méthode POST
     * 
     * @var string $commentText Le texte du commentaire soumis par l'utilisateur
     * @var int $id_post L'ID du post auquel le commentaire appartient
     * @var int $id_user L'ID de l'utilisateur connecté
     */
    $commentText = $_POST['comment'];
    $id_post = $_POST['id_post'];
    $id_user = $_SESSION["id"];

    try {
        /**
         * Création d'un objet Comment avec les informations soumises
         * 
         * @var Comment $comment Objet représentant le commentaire
         */
        $comment = new Comment($commentText, $id_user, $id_post);

        // Enregistrement du commentaire dans la base de données

        /** @var DbManagerCRUD $dbManager Instance du gestionnaire de base de données*/
        $dbManager = new DbManagerCRUD();

        if ($dbManager->createComment($comment)) {
            /**
             * Redirige vers la page des détails du post après l'ajout du commentaire.
             * 
             * @return void
             */
            header("Location: postDetails.php?id_post=" . $id_post);
            exit;
        } else {
            // Affiche un message d'erreur si l'ajout du commentaire a échoué
            echo "Erreur lors de l'ajout du commentaire.";
        }
    } catch (Exception $e) {
        // Capture les erreurs et affiche un message d'erreur
        echo "Erreur : " . $e->getMessage();
    }
}
