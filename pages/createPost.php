<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $text = $_POST['post-content'];
    $city = $_POST['city'];
    $budget = $_POST['budget'];
    $address = $_POST['addresse'] ?? "";
    $authorId = 1; // Exemple : récupérez-le depuis la session utilisateur
    $category = 1; // Exemple : attribuez une catégorie par défaut
    $city = 2;

    // Gestion des fichiers
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            die("Échec du téléchargement de l'image");
        }
    }

    try {
        // Créer l'objet Post
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
        // Insérer le post dans la base de données
        $dbManager = new DbManagerCRUD();
        if ($dbManager->createPost($post)) {
            echo "Post créé avec succès !";
        } else {
            echo "Échec de la création du post.";
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Créer un post</title>
</head>
<body>
<?php include '../components/header.php' ?>
<main class="main-content">
    <div class="create-post-container">
        <div class="post-image">
            <p>Photo</p>
        </div>
        <form class="create-post-form" action="createPost.php" method="POST" enctype="multipart/form-data">
            <h2 class="form-title">Créer un post</h2>

            <div class="form-group">
                <label for="category">Catégorie</label>
                <select name="category" id="category" required>
                    <option value="1">Activités</option>
                    <option value="2">Nourriture</option>
                    <option value="3">Culture</option>
                    <option value="4">Nature</option>
                </select>
            </div>

            <div class="form-group">
                <label for="city">Ville</label>
                <select name="city" id="city" required>
                    <option value="Lausanne">Lausanne</option>
                    <option value="Yverdon-les-Bains">Yverdon-les-Bains</option>
                    <option value="Montreux">Montreux</option>
                    <option value="Vevey">Vevey</option>
                    <option value="Nyon">Nyon</option>
                    <option value="Renens">Renens</option>
                    <option value="Morges">Morges</option>
                </select>
            </div>

            <div class="form-group">
                <label for="addresse">Adresse (facultatif)</label>
                <input type="text" id="addresse" name="addresse" placeholder="Adresse (facultatif)">
            </div>

            <div class="form-group">
                <label for="budget">Budget par personne</label>
                <div class="budget-input">
                    <input type="number" id="budget" name="budget" placeholder="Budget" required>
                    <span>CHF</span>
                </div>
            </div>

            <div class="form-group">
                <label for="title">Titre du texte</label>
                <input type="text" id="title" name="title" placeholder="Titre du texte" required>
            </div>

            <div class="form-group">
                <label for="post-content">Texte</label>
                <textarea id="post-content" name="post-content" placeholder="Texte ..." required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Ajouter une image (facultatif)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="submit-btn">Publier</button>
        </form>
    </div>
</main>

<!-- Footer Inclusion -->
<?php include '../components/footer.php'; ?>
</body>
</html>
