<?php
use M521\ForumVaudois\Entity\User;
use M521\ForumVaudois\Entity\Post;

// Simuler l'utilisateur connecté (normalement, vous récupéreriez cet utilisateur via une session ou une base de données)
$loggedUser = new User("JohnDoe", "johndoe@example.com", "hashed_password", "sample_token", 1);

// Simuler quelques posts pour cet utilisateur (normalement, récupérés depuis une base de données)
$posts = [
    new Post(1, "Premier post", "Ceci est le contenu du premier post.", 0, "Adresse", $loggedUser, new M521\ForumVaudois\Entity\City(), new M521\ForumVaudois\Entity\Category(), new DateTime(), new DateTime()),
    new Post(2, "Deuxième post", "Voici le contenu du deuxième post.", 0, "Adresse", $loggedUser, new M521\ForumVaudois\Entity\City(), new M521\ForumVaudois\Entity\Category(), new DateTime(), new DateTime()),
    new Post(3, "Troisième post", "Encore un autre post.", 0, "Adresse", $loggedUser, new M521\ForumVaudois\Entity\City(), new M521\ForumVaudois\Entity\Category(), new DateTime(), new DateTime()),
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Profil</title>
</head>

<body>
    <a href="/ForumVaudois/pages/logout.php" class="btn btn-logout">Logout</a>
    <main class="main-content">
        <!-- Profil de l'utilisateur -->
        <section class="profile">
            <div class="profile-header">
                <img src="../assets/images/user-avatar.png" alt="Avatar de l'utilisateur" class="profile-avatar">
                <div class="profile-info">
                    <h1><?= htmlspecialchars($loggedUser->getUsername()) ?></h1>
                    <p>Email : <?= htmlspecialchars($loggedUser->getEmail()) ?></p>
                </div>
            </div>
        </section>

        <!-- Historique des postes -->
        <section class="posts-history">
            <h2>Mes posts</h2>
            <?php if (!empty($posts)) : ?>
                <?php foreach ($posts as $post) : ?>
                    <div class="post">
                        <h3><?= htmlspecialchars($post->getTitle()) ?></h3>
                        <p><?= htmlspecialchars($post->getText()) ?></p>
                        <div class="post-actions">
                            <button class="btn-response">Ajouter une réponse</button>
                            <p>📍 <?= htmlspecialchars($post->getCity()->__toString() ?? "Ville inconnue") ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Aucun post trouvé.</p>
            <?php endif; ?>
        </section>
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
