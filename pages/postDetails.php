<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Personne;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\City;
use M521\ForumVaudois\Entity\Category;
use M521\ForumVaudois\Entity\Like;
use M521\ForumVaudois\Entity\Comment;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Vérifier si un identifiant est fourni dans l'URL
if (!isset($_GET['id_post']) || empty($_GET['id_post'])) {
    echo "Identifiant du post non fourni.";
    exit;
}

$idPost = (int) $_GET['id_post']; // Récupérer et sécuriser l'identifiant

// Initialiser la connexion à la base de données et la classe DbManagerCRUD
try {
    $dbManager = new DbManagerCRUD();
    $post = $dbManager->getPostById($idPost); // Fonction à créer dans DbManagerCRUD
    $medias = $dbManager->getMediasByPostId($idPost);
    $likes = $dbManager->getLikesById($idPost);
    $comments = $dbManager->getCommentsById($idPost);

} catch (Exception $e) {
    echo "Erreur lors de la récupération du post : " . $e->getMessage();
    exit;
}

// Vérifier si le post existe
if (!$post) {
    echo "Post introuvable.";
    exit;
}

// Revient d'une page en arrière
$previousPage = $_SERVER['HTTP_REFERER'] ?? 'index.php'; // Fallback to default.php if HTTP_REFERER is not set

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Post</title>
</head>

<body>
    <h1>Détails du Post</h1>
    <p><strong>Titre :</strong> <?= htmlspecialchars($post->getTitle()) ?></p>
    <p><strong>Texte :</strong> <?= nl2br(htmlspecialchars($post->getText())) ?></p>
    <p><strong>Budget :</strong> <?= htmlspecialchars($post->getBudget()) ?> €</p>
    <p><strong>Adresse :</strong> <?= htmlspecialchars($post->getAddress()) ?></p>
    <p><strong>Auteur :</strong> <?= htmlspecialchars($post->getAuthor()) ?></p>
    <p><strong>Ville :</strong> <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()) ?></p>
    <p><strong>Catégorie :</strong>
        <?= htmlspecialchars(Category::getCategoryById($post->getCategory())->getCategoryName()) ?></p>

    <!-- Affichage des images -->
    <h2>Images associées :</h2>
    <?php if (!empty($medias)): ?>
        <div>
            <?php foreach ($medias as $media): ?>
                <?php
                // Récupération du chemin du fichier
                $filePath = htmlspecialchars($media->getFilePath());
                ?>
                <img src="../uploads/<?= $filePath ?>" alt="Image associée au post"
                    style="max-width: 300px; max-height: 300px; margin: 10px;">
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune image associée à ce post.</p>
    <?php endif; ?>

    <!-- Affichage du nombre de likes -->
    <p><strong>Nombre de likes :</strong> <?= count($likes) ?></p>

    <!-- Bouton pour liker -->
    <form method="post" action="likePost.php">
        <input type="hidden" name="id_post" value="<?= $idPost ?>">
        <button type="submit">👍 Liker</button>
    </form>

    <!-- Affichage des commentaires -->
    <h2>Commentaires :</h2>
    <?php if (!empty($comments)): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($comment->getAuthor()) ?></p>
                    <p><strong>Commentaire :</strong> <?= nl2br(htmlspecialchars($comment->getText())) ?></p>
                    <p><strong>Publié le :</strong> <?= htmlspecialchars($comment->getCreatedAt()->format('d/m/Y H:i:s')) ?></p>
                    <hr>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun commentaire pour ce post.</p>
    <?php endif; ?>

    <!-- Formulaire pour ajouter un commentaire -->
    <h2>Ajouter un commentaire</h2>
    <form method="post" action="addComment.php">
        <input type="hidden" name="id_post" value="<?= $idPost ?>">
        <label for="comment">Votre commentaire :</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br>
        <button type="submit">Ajouter le commentaire</button>
    </form>

    <!-- Bouton de retour -->
    
    <a href="<?php echo htmlspecialchars($previousPage); ?>">Retour à la liste des posts</a>
</body>

</html>