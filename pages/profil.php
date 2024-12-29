<?php
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;
use M521\ForumVaudois\Entity\City;

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
