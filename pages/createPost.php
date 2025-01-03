<?php
/**
 * Script de création d'un post.
 * Ce fichier gère à la fois le traitement du formulaire de création de post et l'affichage du formulaire.
 */

require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\Media;

// Activer l'affichage des erreurs pour le débogage
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

// Démarre une session utilisateur
session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    // Redirige vers la page de connexion
    header("Location: login.php");
    exit; // Arrête l'exécution pour éviter de charger le reste de la page
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Variables pour le post :
     * - Titre, texte, ville, budget, adresse, auteur et catégorie.
     */
    $title = $_POST['title'];
    $text = $_POST['post-content'];
    $city = $_POST['city'];
    $budget = $_POST['budget'];
    $address = $_POST['addresse'] ?? "";
    $authorId = $_SESSION["id"]; // ID de l'auteur (exemple, à récupérer de la session utilisateur)
    $category = $_POST['category'] ?? 1; // Catégorie par défaut
    $city = $_POST['city'];

    /**
     * Gestion des fichiers uploadés :
     * - Vérifie si un fichier image est envoyé et le déplace dans le dossier d'uploads.
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
        // Création d'un nouvel objet Post
        $post = new Post(
            $title,
            $text,
            $budget,
            $authorId,
            $city,
            $category,
            new DateTime(), // Date de création
            new DateTime(), // Date de mise à jour
            0,              // Nombre de vues par défaut
            $address
        );

        // Enregistrement du post dans la base de données
        $dbManager = new DbManagerCRUD();
        if ($dbManager->createPost($post)) {
            echo "Post créé avec succès !";

            if($imageName != null) {
                $postId = $dbManager->getLastPostId();
                $media = new Media(
                    $imageName,
                    new DateTime(),
                    $postId
                );
                $dbManager->createMedia($media);
            }
        } else {
            echo "Échec de la création du post.";
        }
    } catch (Exception $e) {
        // Gère les erreurs liées à la création du post
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
    <title>Créer un post</title>
</head>

<body>
    <?php include '../components/header.php'; // Inclusion du header ?>

    <main class="main-content-createPost">
        <div class="create-post-container">
            <!-- Image pré-visualisation -->
            <div class="post-image">
                <img src="../assets/images/photoVaud1.jpg" alt="Photo du lac" class="preview-image">
            </div>

            <!-- Formulaire de création de post -->
            <form class="create-post-form" action="createPost.php" method="POST" enctype="multipart/form-data">
                <h2 class="form-title">Créer un post</h2>

                <!-- Sélection de la catégorie -->
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select name="category" id="category" required>
                        <option value="2">Activités</option>
                        <option value="1">Nourriture</option>
                        <option value="4">Culture</option>
                        <option value="3">Nature</option>
                    </select>
                </div>

                <!-- Sélection de la ville -->
                <div class="form-group">
                    <label for="city">Ville</label>
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

                <!-- Adresse -->
                <div class="form-group">
                    <label for="addresse">Adresse (facultatif)</label>
                    <input type="text" id="addresse" name="addresse" placeholder="Adresse (facultatif)">
                </div>

                <!-- Budget -->
                <div class="form-group">
                    <label for="budget">Budget par personne</label>
                    <div class="budget-input">
                        <input type="number" id="budget" name="budget" placeholder="Budget" required>
                        <span>CHF</span>
                    </div>
                </div>

                <!-- Titre de la publication -->
                <div class="form-group">
                    <label for="title">Titre de la publication</label>
                    <input type="text" id="title" name="title" placeholder="Titre de la publication" required>
                </div>

                <!-- Texte de la publication -->
                <div class="form-group">
                    <label for="post-content">Texte</label>
                    <textarea id="post-content" name="post-content" placeholder="Texte ..." required></textarea>
                </div>

                <!-- Téléchargement d'image -->
                <div class="form-group">
                    <label for="image">Ajouter une image (facultatif)</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" class="submit-btn">Publier</button>
            </form>
        </div>
    </main>

    <!-- Footer -->
     <?php include '../components/footer.php'; ?> 
</body>

</html>
