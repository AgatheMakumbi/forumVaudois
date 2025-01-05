<?php
/**
 * Script pour afficher une liste de posts en fonction de la catégorie sélectionnée
 * et offre également un filtre par ville.
 */

require_once '../vendor/autoload.php';
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\City;

try {
    // Gestion de la langue : récupère la langue depuis la requête GET ou utilise la langue par défaut (français)
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions correspondantes
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    // En cas d'erreur, on charge par défaut le français
    $messages = loadLanguage('fr');
    error_log($e->getMessage()); // Log l'erreur pour débogage
}

// Récupération des paramètres GET pour la catégorie et la ville
$categoryName = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'all'; // Catégorie sélectionnée ou 'all'
$cityId = isset($_GET['city']) ? (int)$_GET['city'] : 0; // Ville sélectionnée ou toutes les villes (0)

// Tableau des catégories disponibles avec leurs IDs
$categories = [
    'food' => 1,
    'activity' => 2,
    'nature' => 3,
    'culture' => 4,
    'all' => 0, // Si 'all', toutes les catégories seront affichées
];

// Validation de la catégorie
if (!array_key_exists($categoryName, $categories)) {
    die("Catégorie invalide ou non spécifiée."); // Arrête l'exécution si la catégorie est invalide
}

try {
    $dbManager = new DbManagerCRUD(); // Instancie un gestionnaire de base de données
    $posts = []; // Initialisation du tableau des posts

    // Si 'all', récupère tous les posts ; sinon, récupère les posts par catégorie
    if ($categoryName == "all") {
        $posts = $dbManager->showPosts();
    } else {
        $posts = $dbManager->getPostsByCategory($categories[$categoryName]);
    }

    // Filtre les posts par ville si une ville est spécifiée
    if ($cityId > 0) {
        $posts = array_filter($posts, function ($post) use ($cityId) {
            return (int) $post->getCity() === $cityId; // Compare l'ID de la ville du post à celui sélectionné
        });
    }
} catch (Exception $e) {
    // Affiche une erreur si la récupération des posts échoue
    echo "Erreur lors de la récupération des posts : " . $e->getMessage();
    exit;
}

// Récupération de toutes les villes utilisées dans les posts
$allCities = [];
$cityIdsUsedInPosts = array_unique(array_map(fn($post) => (int)$post->getCity(), $posts)); // Extrait les IDs de villes des posts

// Parcourt les IDs pour récupérer les objets City correspondants
foreach ($cityIdsUsedInPosts as $id) {
    try {
        $city = City::getCityById($id);
        $allCities[] = $city; // Ajoute la ville au tableau des villes
    } catch (Exception $e) {
        // Log une erreur si la ville n'est pas trouvée
        error_log("Ville avec l'ID $id non trouvée.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>"> <!-- Recharge les styles CSS -->
    <title>Catégorie : <?= ucfirst($categoryName); ?></title>
</head>

<body>
    <div class="wrapper">
        <!-- Inclusion du header -->
        <?php include '../components/header.php'; ?>

        <main class="news-feed">
            <h1><?= t('category_title'); ?> : <?= ucfirst($messages['category_names'][$categoryName] ?? $categoryName); ?></h1>

            <!-- Formulaire de filtre par ville -->
            <form method="get" action="news.php" class="filter-form">
                <input type="hidden" name="category" value="<?= htmlspecialchars($categoryName); ?>"> <!-- Garde la catégorie sélectionnée -->
                <label for="city-filter"><?= t('filter_by_city'); ?></label>
                <select name="city" id="city-filter" onchange="this.form.submit()"> <!-- Filtre par ville -->
                    <option value="0" <?= $cityId === 0 ? 'selected' : ''; ?>><?= t('all_cities'); ?></option>
                    <?php foreach ($allCities as $city): ?>
                        <option value="<?= $city->getId(); ?>" <?= $cityId === $city->getId() ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($city->getCityName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <!-- Conteneur des posts -->
            <div class="posts-container">
                <?php if (!empty($posts)): ?> <!-- Vérifie si des posts existent -->
                    <?php foreach ($posts as $post): ?> <!-- Parcourt et affiche chaque post -->
                        <div class="post-card">
                            <div class="post-header">
                                <img src="../assets/images/user-avatar.png" alt="Auteur" class="post-avatar"> <!-- Avatar de l'auteur -->
                                <a href="postDetails.php?id_post=<?= $post->getId(); ?>"> <!-- Lien vers les détails du post -->
                                    <h2><?= htmlspecialchars($post->getTitle()); ?></h2>
                                </a>
                            </div>
                            <p class="post-content"><?= htmlspecialchars($post->getText()); ?></p> <!-- Contenu du post -->
                            <p class="post-budget">Budget : CHF <?= htmlspecialchars($post->getBudget()); ?></p> <!-- Budget -->
                            <p class="post-location">
                                    📍 <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()) ?>
                                </p>
                            <div class="post-footer">
                                <form method="post" action="likePost.php"> <!-- Bouton de like -->
                                    <input type="hidden" name="id_post" value="<?= $post->getId() ?>">
                                    <button type="submit" class="like-button">👍 Liker</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?= t('news_no_posts'); ?></p> <!-- Message si aucun post trouvé -->
                <?php endif; ?>
            </div>
        </main>

        <!-- Inclusion du footer -->
        <?php include '../components/footer.php'; ?>
    </div>
</body>

</html>
