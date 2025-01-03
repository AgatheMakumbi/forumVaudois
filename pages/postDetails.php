<?php

/**
 * Détails d'un post
 * Cette page récupère et affiche les informations détaillées d'un post,
 * y compris les médias associés, les likes et les commentaires.
 */

require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Category;

// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrage de la session utilisateur
session_start();

// Vérification que l'identifiant du post est fourni dans l'URL
if (!isset($_GET['id_post']) || empty($_GET['id_post'])) {
    echo "Identifiant du post non fourni.";
    exit;
}

// Récupération sécurisée de l'identifiant du post
$idPost = (int)$_GET['id_post'];

try {
    /**
     * Instance pour gérer la base de données.
     *
     * @var DbManagerCRUD $dbManager
     */
    $dbManager = new DbManagerCRUD();

    /**
     * Récupère le post par son identifiant.
     *
     * @var Post|null $post
     */
    $post = $dbManager->getPostById($idPost);

    /**
     * Liste des médias associés au post.
     *
     * @var array $medias
     */
    $medias = $dbManager->getMediasByPostId($idPost);

    /**
     * Liste des likes associés au post.
     *
     * @var array $likes
     */
    $likes = $dbManager->getLikesById($idPost);

    /**
     * Liste des commentaires associés au post.
     *
     * @var array $comments
     */
    $comments = $dbManager->getCommentsById($idPost);

} catch (Exception $e) {
    // Gestion des erreurs de récupération des données
    echo "Erreur lors de la récupération du post : " . $e->getMessage();
    exit;
}

// Vérification que le post existe
if (!$post) {
    echo "Post introuvable.";
    exit;
}

// Nombre total de likes
$totalLikes = count($likes);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>Détails du Post</title>
</head>

<body>
    <!-- Inclusion du header -->
    <?php include '../components/header.php'; ?>

    <main class="main-content-post-detail">
        <!-- Ligne de retour -->
        <a href="../pages/news.php" class="return-link">← Retour à tous les posts</a>

        <!-- Conteneur principal -->
        <div class="post-detail-container">
            <!-- En-tête du post -->
            <div class="post-header">
                <img src="../assets/images/user-avatar.png" alt="Avatar de l'auteur" class="post-avatar">
                <h1 class="post-title"><?= htmlspecialchars($post->getTitle()) ?></h1>
            </div>

            <!-- Description du post -->
            <p class="post-description"><?= nl2br(htmlspecialchars($post->getText())) ?></p>
<br>
            
            <!-- Médias associés -->
            <?php if (!empty($medias)): ?>
                <div class="media-container">
                    <?php foreach ($medias as $media): ?>
                        <img src="../uploads/<?= htmlspecialchars($media->getFilePath()) ?>" alt="Image associée au post" class="post-media">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <br>
            <br>
            <!-- Détails additionnels -->
            <p><strong>Budget :</strong> <?= htmlspecialchars($post->getBudget()) ?> €</p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($post->getAddress()) ?></p>
            <p><strong>Ville :</strong> <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()) ?></p>
            <p><strong>Catégorie :</strong> <?= htmlspecialchars(Category::getCategoryById($post->getCategory())->getCategoryName()) ?></p>
<br>

            <!-- Section des likes -->
            
            <form method="post" action="likePost.php">
                <input type="hidden" name="id_post" value="<?= $idPost ?>">
                <button type="submit" class="like-button">👍 Liker</button>
            </form>
            <br>
            <p><strong>Nombre de 👍 :</strong> <?= $totalLikes ?></p>

            <!-- Section des commentaires -->
            <div class="comment-section">
                <h2>Commentaires :</h2>
                <?php if (!empty($comments)): ?>
                    <ul class="comment-list">
                        <?php foreach ($comments as $comment): ?>
                            <li class="comment-item">
                                <p><strong>Auteur :</strong> <?= htmlspecialchars($comment->getAuthor()) ?></p>
                                <p><?= nl2br(htmlspecialchars($comment->getText())) ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Aucun commentaire pour ce post.</p>
                <?php endif; ?>

                <!-- Formulaire pour ajouter un commentaire -->
                <form method="post" action="addComment.php" class="add-comment-form">
                    <textarea name="comment" rows="4" placeholder="Ajoutez votre commentaire ici..." required></textarea>
                    <button type="submit" class="submit-comment-button">Envoyer</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Inclusion du footer -->
    <?php include '../components/footer.php'; ?>
</body>

</html>
