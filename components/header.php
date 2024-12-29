<?php
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

// Vérifie si une session est déjà active avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas encore active
}

try {
    // Récupère la langue depuis la requête GET ou la session, ou utilise 'fr' par défaut
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    $messages = loadLanguage('fr'); // Fallback en cas d'erreur
    error_log($e->getMessage());
}
?>

<header class="header">
    <div class="logo">
        <a href="/ForumVaudois/index.php">
            <img src="/ForumVaudois/assets/images/logo1.png" alt="<?php echo isset($messages['header_logo_alt']) ? $messages['header_logo_alt'] : 'Forum Vaudois Logo'; ?>" class="logo-img">
        </a>
    </div>
    <nav class="nav-buttons">
        <a href="/ForumVaudois/pages/news.php" class="btn btn-news"><?php echo isset($messages['nav_all']) ? $messages['nav_all'] : 'Tout explorer'; ?></a>
        <a href="/ForumVaudois/pages/news.php?category=activity" class="btn btn-news"><?php echo isset($messages['nav_activities']) ? $messages['nav_activities'] : 'Activité'; ?></a>
        <a href="/ForumVaudois/pages/news.php?category=food" class="btn btn-news"><?php echo isset($messages['nav_food']) ? $messages['nav_food'] : 'Food'; ?></a>
        <a href="/ForumVaudois/pages/news.php?category=nature" class="btn btn-news"><?php echo isset($messages['nav_nature']) ? $messages['nav_nature'] : 'Nature'; ?></a>
        <a href="/ForumVaudois/pages/news.php?category=culture" class="btn btn-news"><?php echo isset($messages['nav_culture']) ? $messages['nav_culture'] : 'Culture'; ?></a>
        <a href="/ForumVaudois/pages/about.php" class="btn btn-news"><?php echo isset($messages['nav_about']) ? $messages['nav_about'] : 'À propos'; ?></a>
        <?php 
        if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
            // Menu pour utilisateur connecté
            echo '<a href="/ForumVaudois/pages/profil.php" class="btn btn-login">' . 
                 (isset($messages['nav_profile']) ? $messages['nav_profile'] : 'Profil') . 
                 '</a>';
            echo '<a href="/ForumVaudois/pages/createPost.php" class="btn btn-login">' . 
                 (isset($messages['nav_post']) ? $messages['nav_post'] : 'Post it!') . 
                 '</a>';
        } else {
            // Menu pour utilisateur non connecté
            echo '<a href="/ForumVaudois/pages/login.php" class="btn btn-login">' . 
                 (isset($messages['nav_login']) ? $messages['nav_login'] : 'Créer un compte / Se connecter') . 
                 '</a>';
        }
        ?>
    </nav>
</header>
