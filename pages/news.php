<?php

/**
 * Ce script est pour afficher une liste de posts en fonction de la catégorie sélectionnée
 * et offre également un filtre par ville.
 */

// Inclut les dépendances nécessaires 
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\City;

try {
    /**
     * Récupère la langue depuis la requête GET ou utilise la langue par défaut (français)
     * 
     * @var string $lang La langue sélectionnée (ou la langue par défaut)
     * @var array $messages Tableau contenant les traductions pour la langue sélectionnée
     */
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    /**
     * En cas d'erreur lors du chargement des traductions, charge la langue par défaut (fr).
     * 
     * @var array $messages Tableau contenant les traductions en français
     */
    $messages = loadLanguage('fr'); // Charge par défaut le français en cas d'erreur
    error_log($e->getMessage()); // Log l'erreur pour débogage
}

/**
 * Récupération des paramètres GET pour la catégorie et la ville
 * 
 * @var string $categoryName Le nom de la catégorie sélectionnée
 * @var int $cityId L'ID de la ville sélectionnée (0 pour toutes les villes)
 */
$categoryName = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'all';
$cityId = isset($_GET['city']) ? (int)$_GET['city'] : 0; // 0 pour afficher tous les posts

// Tableau des catégories disponibles
/**
 * @var array $categories Tableau associatif des catégories disponibles et leurs ID
 */ $categories = [
    'food' => 1,
    'activity' => 2,
    'nature' => 3,
    'culture' => 4,
    'all' => 0, // Si "all", toutes les catégories seront affichées
];

// Vérification de la catégorie
if (!array_key_exists($categoryName, $categories)) {
    /**
     * Si la catégorie est invalide ou non spécifiée, le script s'arrête et affiche un message d'erreur.
     * 
     * @return void
     */
    die("Catégorie invalide ou non spécifiée.");
}

try {
    /**
     * @var DbManagerCRUD $dbManager Instance de la classe DbManagerCRUD pour gérer les interactions avec la base de données
     * @var array $posts Tableau destiné à contenir les posts récupérés de la base de données
     */
    $dbManager = new DbManagerCRUD();
    $posts = [];

    // Récupération des posts en fonction de la catégorie
    if ($categoryName == "all") {
        // Si la catégorie est "all" récupère tous les posts
        $posts = $dbManager->showPosts();
    } else {
        // Pour toutes les autres catégories, récupère les posts correspondants
        $posts = $dbManager->getPostsByCategory($categories[$categoryName]);
    }

    // Filtrage des posts par ville si une ville est spécifiée
    if ($cityId > 0) {
        $posts = array_filter($posts, function ($post) use ($cityId) {
            return $post->getCity() === $cityId;
        });
    }
} catch (Exception $e) {
    // En cas d'erreur lors de la récupération des posts 
    echo "Erreur lors de la récupération des posts : " . $e->getMessage();
    exit;
}

/**
 * Récupération de toutes les villes à partir des données statiques dans City::getCityById()
 * 
 * @var array $allCities Tableau des objets City contenant les informations sur les villes
 */
$allCities = [];
foreach ([1, 3, 2, 4, 5, 7, 6] as $id) {
    try {
        $city = City::getCityById($id);
        $allCities[] = $city;
    } catch (Exception $e) {
        continue; // Ignore les villes non valides
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
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
                <input type="hidden" name="category" value="<?= htmlspecialchars($categoryName); ?>">
                <label for="city-filter"><?= t('filter_by_city'); ?></label>
                <select name="city" id="city-filter" onchange="this.form.submit()">
                    <option value="0" <?= $cityId === 0 ? 'selected' : ''; ?>><?= t('all_cities'); ?></option>
                    <?php foreach ($allCities as $city): ?>
                        <option value="<?= $city->getId(); ?>" <?= $cityId === $city->getId() ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($city->getCityName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>


            <div class="posts-container">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post-card">
                            <div class="post-header">
                                <img src="../assets/images/user-avatar.png" alt="Auteur" class="post-avatar">
                                <a href="postDetails.php?id_post=<?= $post->getId(); ?>">
                                    <h2><?= htmlspecialchars($post->getTitle()); ?></h2>
                                </a>
                            </div>
                            <p class="post-content"><?= htmlspecialchars($post->getText()); ?></p>
                            <p class="post-budget">Budget : CHF <?= htmlspecialchars($post->getBudget()); ?></p>
                            <p class="post-location">
                                📍 <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()); ?>
                            </p>
                            <div class="post-footer">
                                <form method="post" action="likePost.php">
                                    <input type="hidden" name="id_post" value="<?= $post->getId() ?>">
                                    <button type="submit" class="like-button">👍 Liker</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?= t('news_no_posts'); ?></p>
                <?php endif; ?>
            </div>
        </main>

        <!-- Inclusion du footer -->
        <?php include '../components/footer.php'; ?>
    </div>
</body>

</html>