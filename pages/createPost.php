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

<!-- Footer -->
<footer>
    <div class="footer-content">
        <div class="info">
            <h3>Information</h3>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Support</a></li>
            </ul>
        </div>
        <div class="company">
            <h3>Company</h3>
            <p>Adresse : Av. des Sports 20, 1401 Yverdon-les-Bains</p>
            <p>Téléphone : <a href="tel:+41245577600">024 557 76 00</a></p>
        </div>
        <div class="contact-form">
            <form action="#" method="post">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" placeholder="Nom" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>

                <label for="phone">Téléphone</label>
                <input type="tel" id="phone" name="phone" placeholder="Téléphone" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Message" required></textarea>

                <button type="submit">Envoyer</button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        <p>Copyright 2024 HEIG-VD ProgServ2</p>
    </div>
</footer>
</body>
</html>