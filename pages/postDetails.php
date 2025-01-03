<?php

/**
 * Page de détail d'un post.
 * Cette page récupère et affiche les informations détaillées d'un post,
 * y compris les médias associés, les likes et les commentaires.
 */

require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Category;

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session utilisateur
session_start();

// Vérifier si l'identifiant du post est fourni
if (!isset($_GET['id_post']) || empty($_GET['id_post'])) {
    echo "Identifiant du post non fourni.";
    exit;
}

$idPost = (int) $_GET['id_post']; // Sécuriser l'identifiant du post

try {
    // Initialiser la connexion à la base de données
    $dbManager = new DbManagerCRUD();

    // Récupérer les données du post
    $post = $dbManager->getPostById($idPost);
    $medias = $dbManager->getMediasByPostId($idPost);
    $likes = $dbManager->getLikesById($idPost);
    $comments = $dbManager->getCommentsById($idPost);
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . $e->getMessage();
    exit;
}

// Vérifier si le post existe
if (!$post) {
    echo "Post introuvable.";
    exit;
}

// Nombre total de likes
$totalLikes = count($likes);

// URL de la page précédente (fallback vers index.php si non disponible)
$previousPage = $_SERVER['HTTP_REFERER'] ?? '../index.php';

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
       <!-- Bouton de retour -->
<a href="../pages/news.php" class="return-link">← Retour à tous les posts</a>

    

        <!-- Conteneur principal -->
        <div class="post-detail-container">
            <!-- En-tête du post -->
            <div class="post-header">
                <img src="../assets/images/user-avatar.png" alt="Avatar de l'auteur" class="post-avatar">
                <h1 class="post-title"><?= htmlspecialchars($post->getTitle()) ?></h1>
            </div>
            <br>

            <!-- Description et détails supplémentaires -->
            <p class="post-description"><?= nl2br(htmlspecialchars($post->getText())) ?></p>
            <br>
            <p><strong>Budget :</strong> <?= htmlspecialchars($post->getBudget()) ?> €</p>
            <br>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($post->getAddress()) ?></p>
            <br>
            <p><strong>Ville :</strong> <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()) ?></p>
            <br>
            <p><strong>Catégorie :</strong> <?= htmlspecialchars(Category::getCategoryById($post->getCategory())->getCategoryName()) ?></p>
            <br>

            <!-- Médias associés -->
            <?php if (!empty($medias)): ?>
                <h2>Images associées :</h2>
                <br>
                <div class="media-container">
                    <?php foreach ($medias as $media): ?>
                        <img src="../uploads/<?= htmlspecialchars($media->getFilePath()) ?>" alt="Image associée au post" class="post-media">
                    <?php endforeach; ?>
                </div>
                <br>
            <?php else: ?>
                <p>Aucune image associée à ce post.</p>
                <br>
            <?php endif; ?>

            <!-- Section des likes -->
            <h2>Interactions</h2>
            <br>
            
            <form method="post" action="likePost.php">
                <input type="hidden" name="id_post" value="<?= $idPost ?>">
                <button type="submit" class="like-button">👍 Liker</button>
            </form>
            <br>
            <p><strong>Nombre de likes :</strong> <?= $totalLikes ?></p>
            <br>

            <!-- Section des commentaires -->
            <div class="comment-section">
                <h2>Commentaires :</h2>
                <br>
                <?php if (!empty($comments)): ?>
                    <ul class="comment-list">
                        <?php foreach ($comments as $comment): ?>
                            <li class="comment-item">
                                <p><strong>Auteur :</strong> <?= htmlspecialchars($comment->getAuthor()) ?></p>
                                <br>
                                <p><?= nl2br(htmlspecialchars($comment->getText())) ?></p>
                                <br>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Aucun commentaire pour ce post.</p>
                    <br>
                <?php endif; ?>

                <!-- Formulaire pour ajouter un commentaire -->
                <!-- Formulaire pour ajouter un commentaire -->
<h2>Ajouter un commentaire</h2>
<br>
<form method="post" action="addComment.php" class="add-comment-form">
    <!-- Champ hidden pour transmettre l'identifiant du post -->
    <input type="hidden" name="id_post" value="<?= $idPost ?>">
    
    <textarea name="comment" rows="4" placeholder="Ajoutez votre commentaire ici..." required></textarea>
    <br>
    <button type="submit" class="submit-comment-button">Envoyer</button>
</form>

            </div>
        </div>
    </main>

    <!-- Inclusion du footer -->
    <?php include '../components/footer.php'; ?>
</body>

</html>