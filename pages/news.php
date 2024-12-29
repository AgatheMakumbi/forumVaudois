<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\City;

// Posts fictifs pour chaque catégorie 
$posts = [
    new Post(
        "Visiter le Château de Chillon",
        "Le Château de Chillon est un site emblématique du canton de Vaud.",
        20,
        1,
        3,
        1,
        new DateTime(),
        new DateTime(),
        1,
        "Avenue de Chillon, 1820 Veytaux"
    ),
    new Post(
        "Randonnée à la Dent de Jaman",
        "Une randonnée idéale pour admirer les Alpes vaudoises.",
        0,
        2,
        3,
        2,
        new DateTime(),
        new DateTime(),
        2,
        "Dent de Jaman, 1824 Caux"
    ),
    new Post(
        "Dégustation de vins à Lavaux",
        "Découvrez les vins de Lavaux, classé au patrimoine mondial de UNESCO.",
        50,
        3,
        4,
        3,
        new DateTime(),
        new DateTime(),
        3,
        "Chemin de la Vignette, 1091 Grandvaux"
    ),
    new Post(
        "Découvrir Lausanne",
        "Promenez-vous dans la vieille ville et visitez le musée olympique.",
        30,
        4,
        1,
        4,
        new DateTime(),
        new DateTime(),
        4,
        "Place de la Palud, 1003 Lausanne"
    ),
    new Post(
        "Relaxation aux bains de Lavey",
        "Profitez de la journée de détente aux bains thermaux.",
        80,
        5,
        3,
        5,
        new DateTime(),
        new DateTime(),
        5,
        "Route des Bains, 1892 Lavey-les-Bains"
    ),
];

// Récupération de la catégorie via l'URL
$categoryName = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'all';

// Tableau de catégories fictives pour valider l'URL
$categories = [
    'food' => 1,
    'activity' => 2,
    'nature' => 3,
    'culture' => 4,
    'all' => 0, // Si "all", toutes les catégories seront affichées
];

// Vérification de la catégorie
if (!array_key_exists($categoryName, $categories)) {
    die("Catégorie invalide ou non spécifiée.");
}

// Filtrage des posts en fonction de la catégorie
$filteredPosts = $categoryName === 'all' ? $posts : array_filter($posts, function ($post) use ($categories, $categoryName) {
    return $post->getCategory() === $categories[$categoryName];
});
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
    <!-- Inclusion du header -->
    <?php include '../components/header.php'; ?>
    <main class="news-feed">
        <h1>Catégorie : <?= ucfirst($categoryName); ?></h1>
        <div class="posts-container">
            <?php if (!empty($filteredPosts)) : ?>
                <?php foreach ($filteredPosts as $post) : ?>
                    <div class="post-card">
                        <div class="post-header">
                            <img src="../assets/images/user-avatar.png" alt="Auteur" class="post-avatar">
                            <h2><?= htmlspecialchars($post->getTitle()); ?></h2>
                        </div>
                        <p class="post-content"><?= htmlspecialchars($post->getText()); ?></p>
                        <p class="post-budget">Budget : CHF <?= htmlspecialchars($post->getBudget()); ?></p>
                        <p class="post-address">Adresse : <?= htmlspecialchars($post->getAddress()); ?></p>
                        <p class="post-location">📍 <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()); ?></p>
                        
                        <div class="post-footer">
                            <a href="postDetails.php?id=<?= $post->getId(); ?>" class="btn-view-details">Voir les détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun post trouvé pour cette catégorie.</p>
            <?php endif; ?>
        </div>
    </main>
    <!-- Inclusion du footer -->
    <?php include '../components/footer.php'; ?>
</body>

</html>
