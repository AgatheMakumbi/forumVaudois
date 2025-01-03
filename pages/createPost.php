<?php
/**
 * Script de création d'un post.
 * Ce fichier gère à la fois le traitement du formulaire de création de post et l'affichage du formulaire.
 */

require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarre une session utilisateur
session_start();

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
<<<<<<< HEAD
    $authorId = 1; // ID de l'auteur (exemple, à récupérer de la session utilisateur)
    $category = $_POST['category'] ?? 1; // Catégorie par défaut
    $city = $_POST['city'];
=======
    $authorId = $_SESSION['id']; // Exemple : récupérez-le depuis la session utilisateur
    $category = 1; // Exemple : attribuez une catégorie par défaut
    $city = 2;
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f

    /**
     * Gestion des fichiers uploadés :
     * - Vérifie si un fichier image est envoyé et le déplace dans le dossier d'uploads.
     */
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/';
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
<<<<<<< HEAD
    <?php include '../components/header.php'; // Inclusion du header ?>

    <main class="main-content-createPost">
        <div class="create-post-container">
            <!-- Image pré-visualisation -->
=======
    <?php include '../components/header.php' ?>
    <main class="main-content-createPost">
        <div class="create-post-container">
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f
            <div class="post-image">
                <img src="../assets/images/photoVaud1.jpg" alt="Photo du lac" class="preview-image">
            </div>
            <form class="create-post-form" action="createPost.php" method="POST" enctype="multipart/form-data">
                <h2 class="form-title">Créer un post</h2>

<<<<<<< HEAD
            <!-- Formulaire de création de post -->
            <form class="create-post-form" action="createPost.php" method="POST" enctype="multipart/form-data">
                <h2 class="form-title">Créer un post</h2>

                <!-- Sélection de la catégorie -->
=======
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f
                <div class="form-group">
                    <label for="category">Catégorie</label>
                    <select name="category" id="category" required>
                        <option value="1">Activités</option>
                        <option value="2">Nourriture</option>
                        <option value="3">Culture</option>
                        <option value="4">Nature</option>
                    </select>
                </div>

<<<<<<< HEAD
                <!-- Sélection de la ville -->
=======
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f
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

<<<<<<< HEAD
                <!-- Adresse -->
=======
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f
                <div class="form-group">
                    <label for="addresse">Adresse (facultatif)</label>
                    <input type="text" id="addresse" name="addresse" placeholder="Adresse (facultatif)">
                </div>

<<<<<<< HEAD
                <!-- Budget -->
=======
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f
                <div class="form-group">
                    <label for="budget">Budget par personne</label>
                    <div class="budget-input">
                        <input type="number" id="budget" name="budget" placeholder="Budget" required>
                        <span>CHF</span>
                    </div>
                </div>

<<<<<<< HEAD
                <!-- Titre de la publication -->
=======
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f
                <div class="form-group">
                    <label for="title">Titre de la publication</label>
                    <input type="text" id="title" name="title" placeholder="Titre de la publication" required>
                </div>
<<<<<<< HEAD
=======

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
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f

                <!-- Texte de la publication -->
                <div class="form-group">
                    <label for="post-content">Texte</label>
                    <textarea id="post-content" name="post-content" placeholder="Texte ..." required></textarea>
                </div>

<<<<<<< HEAD
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
=======
    <!--<?php include '../components/footer.php'; ?>-->
</body>

</html>
>>>>>>> 0acd9bc37e0c1163bbb5f148d4da60ed4fea644f
