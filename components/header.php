<?php

/**
 * Script du header avec gestion de la langue et affichage du menu
 * 
 * Ce fichier gère la langue de l'interface utilisateur et affiche 
 * le menu de navigation en fonction de l'état de connexion de l'utilisateur.
 */

// Inclusion des dépendances nécessaires
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

// Vérifie si une session est déjà active avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas encore active
}

try {
    /**
     * @var string $lang Langue courante de l'utilisateur (récupérée depuis GET ou la session).
     */
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');

    /**
     * @var array $messages Tableau contenant les messages traduits pour l'interface utilisateur.
     */
    $messages = loadLanguage($lang); // Charge les traductions

    // Stocke la langue actuelle dans la session
    $_SESSION['LANG'] = $lang;
} catch (Exception $e) {
    // En cas d'erreur, charge les messages en français par défaut
    $messages = loadLanguage('fr'); // Fallback en cas d'erreur
    error_log($e->getMessage()); // Enregistre l'erreur dans le journal
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ForumVaudois/assets/css/style.css?v=<?= time(); ?>">
    <!-- Favicon au format .png -->
    <link rel="icon" href="/ForumVaudois/assets/images/favicon.png" type="image/png">
    <title>Forum Vaudois</title>
</head>

<body>
    <header class="header">
        <div class="logo">
            <a href="/ForumVaudois/index.php">
                <img src="/ForumVaudois/assets/images/logo1.png"
                    alt="<?php echo isset($messages['header_logo_alt']) ? $messages['header_logo_alt'] : 'Forum Vaudois Logo'; ?>"
                    class="logo-img">
            </a>
        </div>
        <nav class="nav-buttons">
            <!-- Liens de navigation généraux -->
            <a href="/ForumVaudois/pages/news.php" class="btn btn-news"><?php echo $messages['nav_all'] ?? 'Tout explorer'; ?></a>
            <a href="/ForumVaudois/pages/news.php?category=activity" class="btn btn-news"><?php echo $messages['nav_activities'] ?? 'Activité'; ?></a>
            <a href="/ForumVaudois/pages/news.php?category=food" class="btn btn-news"><?php echo $messages['nav_food'] ?? 'Food'; ?></a>
            <a href="/ForumVaudois/pages/news.php?category=nature" class="btn btn-news"><?php echo $messages['nav_nature'] ?? 'Nature'; ?></a>
            <a href="/ForumVaudois/pages/news.php?category=culture" class="btn btn-news"><?php echo $messages['nav_culture'] ?? 'Culture'; ?></a>
            <a href="/ForumVaudois/pages/about.php" class="btn btn-news"><?php echo $messages['nav_about'] ?? 'À propos'; ?></a>

            <?php
            if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
                // Menu pour utilisateur connecté
                echo '<a href="/ForumVaudois/pages/profil.php">
                    <img src="/ForumVaudois/assets/images/profil.png" alt="Profil" class="icon-profile">
                  </a>';
                echo '<a href="/ForumVaudois/pages/createPost.php" class="btn btn-login">Post it!</a>';
                echo '<a href="/ForumVaudois/pages/logout.php">
                    <img src="/ForumVaudois/assets/images/logout.png" alt="Logout" class="icon-logout">
                  </a>';
            } else {
                // Menu pour utilisateur non connecté
                echo '<a href="/ForumVaudois/pages/login.php" class="btn btn-login">' .
                    ($messages['nav_button_login'] ?? 'Créer un compte / Se connecter') .
                    '</a>';
            }
            ?>
        </nav>
    </header>
</body>

</html>