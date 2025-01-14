<?php

/**
 * Script permettant de modifier un post existant
 * Ce script récupère les informations du post à modifier, vérifie que l'utilisateur
 * est l'auteur de celui-ci et permet de mettre à jour le post dans la base de données.
 * 
 */

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
    header("Location: /ForumVaudois/pages/profil.php"); // Redirige vers le profil
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
    header("Location: /ForumVaudois/pages/login.php"); // Redirige vers la page de connexion
    exit;
}

// Modification du post

try {
    // Récupère les informations du post à modifier
    $post = $dbManager->getPostById($idPost);

    /**
     * Vérifie si le post existe et si l'utilisateur est l'auteur du post,
     * sinon redirige vers la page de profil.
     */
    if (!$post || $post->getAuthor() !== $_SESSION['id']) {
        header("Location: /ForumVaudois/pages/profil.php"); // Redirige si l'utilisateur n'est pas l'auteur du post
        exit;
    }

    // Traitement de la modification du post après soumission du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? null;
        $text = $_POST['post-content'] ?? null;
        $city = $_POST['city'] ?? null;
        $budget = $_POST['budget'] ?? null;
        $address = $_POST['addresse'] ?? "";
        $category = $_POST['category'] ?? null;

        // Vérification des champs obligatoires 
        if (!$title || !$text || !$city) {
            echo "Tous les champs obligatoires (titre, contenu, ville) doivent être remplis.";
        } else {
            // Mise à jour des données du post 
            $post->setTitle($title);
            $post->setText($text);
            $post->setCity((int)$city);
            $post->setBudget((float)$budget);
            $post->setAddress($address);
            $post->setCategory((int)$category);

            // Enregistrement des modifications dans la base de données
            if ($dbManager->updatePost($post)) {
                header("Location: /ForumVaudois/pages/profil.php"); // Redirige vers le profil après la mise à jour
                exit;
            } else {
                // En cas d'erreur lors de la modification dans la base de données
                echo "Échec de la modification du post.";
            }
        }
    }
} catch (Exception $e) {
    // En cas d'erreur lors de la récupération ou de la mise à jour du post
    echo "Erreur lors de la récupération ou la mise à jour du post : " . $e->getMessage();
    exit;
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
    <?php include '../components/header.php' ?>
    <main class="main-content-createPost">
        <div class="create-post-container">
            <div class="post-image">
                <img src="../assets/images/photoVaud1.jpg" alt="Photo du lac" class="preview-image">
            </div>
            <form class="create-post-form" action="updatePost.php?id_post=<?= $idPost ?>" method="POST">
                <h2 class="form-title">Modifier un post</h2>

                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select name="category" id="category" required>
                        <option value="2" <?= $post->getCategory() == 2 ? 'selected' : '' ?>>Activités</option>
                        <option value="1" <?= $post->getCategory() == 1 ? 'selected' : '' ?>>Nourriture</option>
                        <option value="4" <?= $post->getCategory() == 4 ? 'selected' : '' ?>>Culture</option>
                        <option value="3" <?= $post->getCategory() == 3 ? 'selected' : '' ?>>Nature</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">Ville</label>
                    <select name="city" id="city" required>
                        <option value="1" <?= $post->getCity() == 1 ? 'selected' : '' ?>>Lausanne</option>
                        <option value="3" <?= $post->getCity() == 3 ? 'selected' : '' ?>>Yverdon-les-Bains</option>
                        <option value="2" <?= $post->getCity() == 2 ? 'selected' : '' ?>>Montreux</option>
                        <option value="4" <?= $post->getCity() == 4 ? 'selected' : '' ?>>Vevey</option>
                        <option value="5" <?= $post->getCity() == 5 ? 'selected' : '' ?>>Nyon</option>
                        <option value="7" <?= $post->getCity() == 7 ? 'selected' : '' ?>>Renens</option>
                        <option value="6" <?= $post->getCity() == 6 ? 'selected' : '' ?>>Morges</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="addresse">Adresse (facultatif)</label>
                    <input type="text" id="addresse" name="addresse" placeholder="Adresse (facultatif)"
                        value="<?= htmlspecialchars($post->getAddress(), ENT_QUOTES) ?>">
                </div>

                <div class="form-group">
                    <label for="budget">Budget par personne</label>
                    <div class="budget-input">
                        <input type="number" id="budget" name="budget" placeholder="Budget" required
                            value="<?= htmlspecialchars($post->getBudget(), ENT_QUOTES) ?>">
                        <span>CHF</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Titre de la publication</label>
                    <input type="text" id="title" name="title" placeholder="Titre de la publication" required
                        value="<?= htmlspecialchars($post->getTitle(), ENT_QUOTES) ?>">
                </div>

                <div class="form-group">
                    <label for="post-content">Texte</label>
                    <textarea id="post-content" name="post-content" placeholder="Texte ..."
                        required><?= htmlspecialchars($post->getText(), ENT_QUOTES) ?></textarea>
                </div>

                <button type="submit" class="submit-btn">Modifier</button>
                
            </form>
        </div>
    </main>
</body>

</html>