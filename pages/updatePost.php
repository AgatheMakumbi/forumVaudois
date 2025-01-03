<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();

$dbManager = new DbManagerCRUD();
$_SESSION['id'] = 1;

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
    $authorId = $_SESSION['id']; // ID de l'auteur (exemple, à récupérer de la session utilisateur)
    $category = $_POST['category'] ?? 1; // Catégorie par défaut
    $city = $_POST['city'];

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
        if ($dbManager->updatePost($post)) {
            echo "Post modifié avec succès !";
        } else {
            echo "Échec de la modification du post.";
        }
    } catch (Exception $e) {
        // Gère les erreurs liées à la création du post
        echo "Erreur : " . $e->getMessage();
    }
}

// Vérifier si un identifiant est fourni dans l'URL
if (!isset($_GET['id_post']) || empty($_GET['id_post'])) {
    echo "Identifiant du post non fourni.";
    exit;
}

$idPost = (int)$_GET['id_post']; // Récupérer et sécuriser l'identifiant

// Initialiser la connexion à la base de données et la classe DbManagerCRUD
try {
    $post = $dbManager->getPostById($idPost);
    $author = $post->getAuthor();
    
    if($author !== $_SESSION['id']) {
        // Redirige vers la page de connexion
        header("Location: news.php");
        exit; // Arrête l'exécution pour éviter de charger le reste de la page
    }

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
        <form class="create-post-form" action="updatePost.php?id_post=<?php echo $idPost ?>" method="POST" enctype="multipart/form-data">
            <h2 class="form-title">Créer un post</h2>

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
                <textarea id="post-content" name="post-content" placeholder="Texte ..." required><?= htmlspecialchars($post->getText(), ENT_QUOTES) ?></textarea>
            </div>

            <button type="submit" class="submit-btn">Modifier</button>
        </form>
    </div>
</main>

<!--<?php include '../components/footer.php'; ?>-->
</body>
</html>
