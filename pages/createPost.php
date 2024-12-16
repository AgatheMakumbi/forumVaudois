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
    //$city = $_POST['city'];
    $city = 2;
    $budget = $_POST['budget'];
    $address = $_POST['addresse'] ?? "";
    $authorId = 1; // Exemple : récupérez-le depuis la session utilisateur
    $category = 1; // Exemple : attribuez une catégorie par défaut

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
        $dbManager = new DbManagerCRUD(); // Assurez-vous que DbManager est initialisé correctement
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
    <title>Sign up</title>
</head>
<body>
<?php include '../components/header.php'?>
<main class="main-content">
        <form class="signup-form" action="createPost.php" method="POST" enctype="multipart/form-data">
            <h2 class="form-title">Créer un post</h2>
            
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="post-content">Que souhaite tu partager?</label>
                <input type="text" id="post-content" name="post-content" required>
            </div>

            <div class="form-group">
                <label for="city">Ville</label>
                <select name="city" id="city"  required>
                    <option value="Neuchatel">Neuchatel</option>
                    <option value="Vevey">Vevey</option>
                    <option value="Lausanne">Lausanne</option>
                </select>
            </div>

            <div class="form-group">
                <label for="budget">Quel budget prévoir ?</label>
                <input type="number" id="budget" name="budget" required>
            </div>

            <div class="form-group">
                <label for="addresse">Si applicable saisit l'adresse</label>
                <input type="text" id="addresse" name="addresse">
            </div>
            
            <div class="form-group">
                <label for="image">Ajoute une image (facultatif)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="submit-btn">Partager</button>
        </form>
    </main>
</body>
</html>