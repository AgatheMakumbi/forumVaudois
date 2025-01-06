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

// Démarrer la session utilisateur
session_start();

// Vérifier si l'identifiant du post est fourni
if (empty($_GET['id_post'])) {
    echo "Identifiant du post non fourni.";
    exit;
}

$idPost = (int)$_GET['id_post']; // Sécuriser l'identifiant du post

try {
    // Initialiser la connexion à la base de données
    $dbManager = new DbManagerCRUD();

    // Récupérer les données du post
    $post = $dbManager->getPostById($idPost);
    if (!$post) {
        echo "Post introuvable.";
        exit;
    }

    $medias = $dbManager->getMediasByPostId($idPost);
    $likes = $dbManager->getLikesById($idPost);
    $comments = $dbManager->getCommentsById($idPost);
    $authorUser = $dbManager->getUserById($post->getAuthor())->getUsername();
} catch (Exception $e) {
    echo "Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage());
    exit;
}

$userLiked = false;
if (!empty($_SESSION["id"])) {
    foreach ($likes as $like) {
        if ($like->getAuthor() === $_SESSION['id']) {
            $userLiked = true;
            break;
        }
    }
}

$totalLikes = count($likes);
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
    <?php include '../components/header.php'; ?>

    <main class="main-content-post-detail">
        <a href="<?= htmlspecialchars($previousPage) ?>" class="return-link">&larr; Retour</a>

        <div class="post-detail-container">
            <h1><?= htmlspecialchars($post->getTitle()) ?></h1>
            <br>
            <p><?= nl2br(htmlspecialchars($post->getText())) ?></p><br>
            <p><strong>Budget :</strong> <?= htmlspecialchars($post->getBudget()) ?> CHF</p><br>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($post->getAddress()) ?></p><br>
            <p><strong>Auteur :</strong> <?= htmlspecialchars($authorUser) ?></p><br>
            <p><strong>Ville :</strong> <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()) ?></p><br>
            <p><strong>Catégorie :</strong> <?= htmlspecialchars(Category::getCategoryById($post->getCategory())->getCategoryName()) ?></p><br>

            <?php if (!empty($medias)): ?>
                <h2>Images associées :</h2>
                <br>
                <div class="media-container">
                    <?php foreach ($medias as $media): ?>
                        <img src="/uploads/<?= htmlspecialchars($media->getFilePath()) ?>" alt="Image associée au post"><br><br>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aucune image associée à ce post.</p><br>
            <?php endif; ?>

            <h2>Interactions</h2>
            <br>
            <p><strong>Nombre de likes :</strong> <?= $totalLikes ?></p><br>
            <form method="post" action="likePost.php">
                <input type="hidden" name="id_post" value="<?= $idPost ?>">
                <button type="submit" class="like-button" <?= $userLiked ? 'disabled' : '' ?>>👍 Liker</button>
            </form><br><br>

            <h2>Commentaires :</h2>
            <br>
            <?php if (!empty($comments)): ?>
                <ul>
                    <?php foreach ($comments as $comment): ?>
                        <li>
                            <p><strong>Auteur :</strong> <?= htmlspecialchars($dbManager->getUserById($comment->getAuthor())->getUsername()) ?></p><br>
                            <p><?= nl2br(htmlspecialchars($comment->getText())) ?></p><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun commentaire pour ce post.</p><br>
            <?php endif; ?>

            <h2>Ajouter un commentaire</h2>
            <br>
            <form method="post" action="addComment.php">
                <input type="hidden" name="id_post" value="<?= $idPost ?>">
                <textarea name="comment" rows="4" placeholder="Ajoutez votre commentaire ici..." required></textarea><br><br>
                <button type="submit">Envoyer</button>
            </form>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>
</body>

</html>
