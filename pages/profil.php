<?php

/**
 * Script de gestion du profil utilisateur.
 * Cette page affiche les informations du profil utilisateur ainsi que la liste des posts créés par l'utilisateur.
 */

// Inclut les dépendances nécessaires 
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\City;

try {
    /**
     * @var string $lang Langue utilisée pour afficher les messages (par défaut : français)
     * @var array $messages Contient les traductions pour la langue sélectionnée
     */
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    // En cas d'erreur, charge la langue par défaut (français)
    $messages = loadLanguage('fr');
    error_log($e->getMessage()); // Log l'erreur pour débogage
}

// Démarrer la session 
session_start();

// Initialisation de la connexion avec la base de données
$db = new DbManagerCRUD();

/**
 * @var int $loggedUserID Identifiant de l'utilisateur connecté
 */
$loggedUserID = $_SESSION["id"];

/**
 * @var User $loggedUser Objet représentant l'utilisateur connecté
 */
$loggedUser = $db->getUserById($loggedUserID);

/**
 * @var Post[] $posts Liste des posts créés par l'utilisateur connecté
 */
$posts = $db->getPostsByUser($loggedUserID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title><?= t('profile_title'); ?></title>
</head>

<body>
    <div class="wrapper">
        <?php include '../components/header.php'; ?>

        <main class="profile-main">
            <!-- Profil de l'utilisateur -->
            <section class="profile-section">
                <div class="profile-header">
                    <img src="../assets/images/user-avatar.png" alt="Avatar de l'utilisateur" class="profile-avatar">
                    <div class="profile-info">
                        <h1><?= htmlspecialchars($loggedUser->getUsername()) ?></h1>
                        <p><?= t('profile_email'); ?> <?= htmlspecialchars($loggedUser->getEmail()) ?></p>
                    </div>
                </div>
            </section>

            <!-- Historique des posts -->
            <section class="profile-posts">
                <h2><?= t('profile_my_posts'); ?></h2>
                <div class="posts-container">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="post-card">
                                <div class="post-header">
                                    <h2>
                                        <!-- Lien vers les détails du post -->
                                        <a href="postDetails.php?id_post=<?= htmlspecialchars($post->getId()) ?>" class="post-title-link">
                                            <?= htmlspecialchars($post->getTitle()) ?>
                                        </a>
                                    </h2>
                                    <!-- Icône de modification -->
                                    <a href="updatePost.php?id_post=<?= htmlspecialchars($post->getId()) ?>" class="edit-icon" title="Modifier la publication">
                                        <img src="../assets/images/update.png" alt="Modifier" style="width: 30px; height: 30px;">
                                    </a>
                                </div>

                                <p><?= htmlspecialchars($post->getText()) ?></p>
                                <div class="post-footer">
                                    <p class="post-location">
                                        📍 <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p><?= t('profile_no_posts'); ?></p>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <?php include '../components/footer.php'; ?>
    </div>
</body>

</html>