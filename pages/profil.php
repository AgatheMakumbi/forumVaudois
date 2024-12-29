<?php
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction


use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\City;

try {
    // Récupère la langue depuis la requête GET ou utilise la langue par défaut (français)
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    $messages = loadLanguage('fr'); // Charge par défaut le français en cas d'erreur
    error_log($e->getMessage()); // Log l'erreur pour débogage
}

require_once '../vendor/autoload.php';
session_start();
// Simuler l'utilisateur connecté
$loggedUser = new User("JohnDoe", "johndoe@example.com", "hashed_password", "sample_token", 1);

// Simuler quelques posts pour cet utilisateur
$posts = [
    new Post(
        "Premier post",
        "Ceci est le contenu du premier post.",
        100,
        $loggedUser->getId(),
        1, // ID de la ville
        1, // ID de la catégorie
        new DateTime(),
        new DateTime(),
        1,
        "Adresse 1"
    ),
    new Post(
        "Deuxième post",
        "Voici le contenu du deuxième post.",
        200,
        $loggedUser->getId(),
        2, // ID de la ville
        2,
        new DateTime(),
        new DateTime(),
        2,
        "Adresse 2"
    ),
    new Post(
        "Troisième post",
        "Encore un autre post.",
        300,
        $loggedUser->getId(),
        3, // ID de la ville
        3,
        new DateTime(),
        new DateTime(),
        3,
        "Adresse 3"
    ),
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>Profil</title>
</head>

<body>
    <?php include '../components/header.php'; ?>
    <main class="profile-main">
        <!-- Profil de l'utilisateur -->
        <section class="profile-section">
            <div class="profile-header">
                <img src="../assets/images/user-avatar.png" alt="Avatar de l'utilisateur" class="profile-avatar">
                <div class="profile-info">
                    <h1><?= htmlspecialchars($loggedUser->getUsername()) ?></h1>
                    <p>Email : <?= htmlspecialchars($loggedUser->getEmail()) ?></p>
                </div>
            </div>
        </section>

        <!-- Historique des posts -->
        <section class="profile-posts">
            <h2>Mes posts</h2>
            <div class="posts-container">
                <?php if (!empty($posts)) : ?>
                    <?php foreach ($posts as $post) : ?>
                        <div class="post-card">
                            <div class="post-header">
                                <h2><?= htmlspecialchars($post->getTitle()) ?></h2>
                            </div>
                            <p><?= htmlspecialchars($post->getText()) ?></p>
                            <div class="post-footer">
                                <button class="btn-response">Ajouter une réponse</button>
                                <p class="post-location">
                                    📍 <?= htmlspecialchars(City::getCityById($post->getCity())->getCityName()) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Aucun post trouvé.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include '../components/footer.php'; ?>
</body>

</html>
