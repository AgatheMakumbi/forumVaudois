<?php 
session_start();
$_SESSION["isConnected"] = true; // Simuler un utilisateur connecté pour ce header
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
        <a href="/ForumVaudois/">
            <img src="/ForumVaudois/assets/images/logo.png" alt="Forum Vaudois Logo" class="logo-img">
        </a>
    </div>
    <nav class="nav-buttons">
        <a href="/ForumVaudois/pages/news.php" class="btn btn-news">Tout explorer</a>
        <a href="/ForumVaudois/pages/news.php?category='activity'" class="btn btn-news">Activité 🪂</a>
        <a href="/ForumVaudois/pages/news.php?category='food'" class="btn btn-news">Food 🍴</a>
        <a href="/ForumVaudois/pages/news.php?category='nature'" class="btn btn-news">Nature 🌿</a>
        <a href="/ForumVaudois/pages/news.php?category='culture'" class="btn btn-news">Culture 🎥</a>
        <a href="/ForumVaudois/pages/about.php" class="btn btn-about">À propos</a>
        
        <!-- Boutons spécifiques pour les utilisateurs connectés avec icônes -->
        <a href="/ForumVaudois/pages/createPost.php" class="btn btn-create">Créer un post</a>
        <a href="/ForumVaudois/pages/profil.php" class="btn btn-profile">
            <img src="/ForumVaudois/assets/images/profil.png" alt="Profil" class="icon">
        </a>
        <a href="/ForumVaudois/pages/logout.php" class="btn btn-logout">
            <img src="/ForumVaudois/assets/images/logout.png" alt="Déconnexion" class="icon">
        </a>
    </nav>
</header>
