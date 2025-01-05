<?php

// Inclut les dépendances nécessaires
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\Media;

//Démarre la session 
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

// Traitement de la création d'un post via la méthode POST

/**
 * @var string $successMessage Message de succès ou non
 */
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * @var string $title Le titre du post
     * @var string $text Le texte du post
     * @var int $city L'identifiant de la ville du post
     * @var int $budget Le budget défini dans le post
     * @var string $address L'adresse définie dans le post
     * @var int $authorId L'identifiant de l'utilisateur auteur du post
     * @var int $category L'identifiant de la catégorie du post 
     */
    $title = $_POST['title'];
    $text = $_POST['post-content'];
    $city = $_POST['city'];
    $budget = $_POST['budget'];
    $address = $_POST['addresse'] ?? "";
    $authorId = $_SESSION["id"];
    $category = $_POST['category'] ?? 1;

    /**
     * Gestion des images liées au post 
     * @var string $imagePath Le chemin du fichier image
     * @var string $imageName Le nom du fichier image 
     * @var string $uploadDir Le dossier dans lequel les images doivent être enregistrées
     */
    $imagePath = null;
    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/';
        $imageName = $_FILES['image']['name'];
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            die("Échec du téléchargement de l'image");
        }
    }

    try {
        // Création d'un objet Post avec les information récupérées
        $post = new Post(
            $title,
            $text,
            $budget,
            $authorId,
            $city,
            $category,
            new DateTime(),
            new DateTime(),
            0,
            $address
        );

        /** 
         * @var DbManagerCRUD $dbManager Instance du gestionnaire de base de données
         */
        $dbManager = new DbManagerCRUD();

        // Enregistrement du post dans la base de données 
        if ($dbManager->createPost($post)) {
            if ($imageName != null) {
                $postId = $dbManager->getLastPostId();
                $media = new Media(
                    $imageName,
                    new DateTime(),
                    $postId
                );
                $dbManager->createMedia($media);
            }

            $successMessage = "Post créé avec succès ! Vous allez être redirigé.";
            header("refresh:3;url=../index.php"); // Redirection après 3 secondes
        } else {
            // Si l'ajout du commentaire a échoué 
            $successMessage = "Échec de la création du post.";
        }
    } catch (Exception $e) {
        // Capture les erreurs
        $successMessage = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>Créer un post</title>
</head>

<body>
    <?php include '../components/header.php'; ?>

    <!-- Message conditionnel -->
    <?php if (!empty($successMessage)): ?>
        <div class="success-message">
            <p><?= htmlspecialchars($successMessage); ?></p>
        </div>
    <?php endif; ?>

    <main class="main-content-createPost">
        <div class="create-post-container">
            <div class="post-image">
                <img src="../assets/images/photoVaud1.jpg" alt="Photo du lac" class="preview-image">
            </div>

            <form class="create-post-form" action="createPost.php" method="POST" enctype="multipart/form-data">
                <h2 class="form-title"><?= t('create_post_title'); ?></h2>

                <!-- Catégorie -->
                <div class="form-group">
                    <label for="category"><?= t('create_post_category'); ?></label>
                    <select name="category" id="category" required>
                        <option value="2"><?= t('category_names')['activity']; ?></option>
                        <option value="1"><?= t('category_names')['food']; ?></option>
                        <option value="4"><?= t('category_names')['culture']; ?></option>
                        <option value="3"><?= t('category_names')['nature']; ?></option>
                    </select>
                </div>

                <!-- Ville -->
                <div class="form-group">
                    <label for="city"><?= t('create_post_city'); ?></label>
                    <select name="city" id="city" required>
                        <option value="1">Lausanne</option>
                        <option value="3">Yverdon-les-Bains</option>
                        <option value="2">Montreux</option>
                        <option value="4">Vevey</option>
                        <option value="5">Nyon</option>
                        <option value="7">Renens</option>
                        <option value="6">Morges</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="addresse"><?= t('create_post_address'); ?></label>
                    <input type="text" id="addresse" name="addresse" placeholder="<?= t('create_post_address_placeholder'); ?>">
                </div>

                <div class="form-group">
                    <label for="budget"><?= t('create_post_budget'); ?></label>
                    <div class="budget-input">
                        <input type="number" id="budget" name="budget" placeholder="<?= t('create_post_budget_placeholder'); ?>" required>
                        <span>CHF</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title"><?= t('create_post_title_label'); ?></label>
                    <input type="text" id="title" name="title" placeholder="<?= t('create_post_title_placeholder'); ?>" required>
                </div>

                <div class="form-group">
                    <label for="post-content"><?= t('create_post_text'); ?></label>
                    <textarea id="post-content" name="post-content" placeholder="<?= t('create_post_text_placeholder'); ?>" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image"><?= t('create_post_image'); ?></label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <button type="submit" class="submit-btn"><?= t('create_post_submit'); ?></button>
            </form>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>
</body>

</html>