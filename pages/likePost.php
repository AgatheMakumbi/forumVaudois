<?php

/**
 * Script qui permet de traiter l'ajout d'un like depuis la page postDetails
 */

// Inclut les dépendances nécessaires 
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\Like;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

// Démarre la session
session_start();

// Vérification de la session utilisateur
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    /**
     * Redirige l'utilisateur vers la page de connexion si l'utilisateur n'est pas connecté.
     * 
     * @return void
     */
    header("Location: login.php");
    exit; // Arrête l'exécution pour éviter de charger le reste de la page
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Traitement de l'ajout d'un like via la méthode POST.
     * 
     * @var int $id_post L'ID du post auquel le like est attribué
     * @var int $id_user L'ID de l'utilisateur qui aime le post
     */
    $id_post = $_POST['id_post'];
    $id_user = $_SESSION["id"];

    try {
        /**
         * Création d'un objet Like représentant l'action de l'utilisateur sur le post.
         * 
         * @var Like $like Objet représentant le like
         */
        $like = new Like(
            $id_user,
            $id_post
        );

        // Enregistrement du like dans la base de données

        /** @var DbManagerCRUD $dbManager Instance du gestionnaire de base de données*/
        $dbManager = new DbManagerCRUD();

        if ($dbManager->createLike($like)) {
            /**
             * Redirige vers la page des détails du post après l'ajout du like.
             * 
             * @return void
             */
            header("Location: postDetails.php?id_post=" . $id_post);
            exit;
        } else {
            /**
             * Redirige vers la page des détails du post même en cas d'échec de l'ajout du like.
             * 
             * @return void
             */
            header("Location: postDetails.php?id_post=" . $id_post);
            exit;
        }
    } catch (Exception $e) {
        // Capture les erreurs et affiche un message d'erreur
        echo "Erreur : " . $e->getMessage();
    }
}
