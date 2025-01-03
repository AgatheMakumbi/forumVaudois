<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Post;

session_start();

$dbManager = new DbManagerCRUD();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id_post']) || empty($_GET['id_post'])) {
    header("Location: profil.php");
    exit;
}

$idPost = (int)$_GET['id_post'];

try {
    $post = $dbManager->getPostById($idPost);

    if (!$post) {
        echo "Post introuvable.";
        exit;
    }

    if ($post->getAuthor() !== $_SESSION['id']) {
        header("Location: profil.php");
        exit;
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération du post : " . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? null;
    $text = $_POST['post-content'] ?? null;
    $city = isset($_POST['city']) ? (int)$_POST['city'] : null;
    $budget = isset($_POST['budget']) ? (int)$_POST['budget'] : 0;
    $address = $_POST['addresse'] ?? "";
    $category = isset($_POST['category']) ? (int)$_POST['category'] : 1;

    if ($title === null || $text === null || $city === null) {
        echo "Tous les champs obligatoires (titre, contenu, ville) doivent être remplis.";
        exit;
    }

    try {
        $post->setTitle($title);
        $post->setText($text);
        $post->setBudget($budget);
        $post->setCity($city);
        $post->setCategory($category);
        $post->setAddress($address);
        $post->setUpdatedAt(new DateTime());

        if ($dbManager->updatePost($post)) {
            header("Location: profil.php");
            exit;
        } else {
            echo "Échec de la modification du post.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>Modifier un post</title>
</head>

<body>
    <?php include '../components/header.php'; ?>
    <main class="main-content-createPost">
        <div class="create-post-container">
            <form class="create-post-form" action="updatePost.php?id_post=<?= $idPost ?>" method="POST">
                <h2 class="form-title">Modifier un post</h2>

                <div class="form-group">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($post->getTitle()) ?>" required>
                </div>

                <div class="form-group">
                    <label for="post-content">Contenu</label>
                    <textarea id="post-content" name="post-content" required><?= htmlspecialchars($post->getText()) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="city">Ville</label>
                    <select name="city" id="city" required>
                        <option value="1" <?= $post->getCity() == 1 ? 'selected' : '' ?>>Lausanne</option>
                        <option value="3" <?= $post->getCity() == 3 ? 'selected' : '' ?>>Yverdon-les-Bains</option>
                        <option value="2" <?= $post->getCity() == 2 ? 'selected' : '' ?>>Montreux</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn">Modifier</button>
            </form>
        </div>
    </main>
</body>

</html>
