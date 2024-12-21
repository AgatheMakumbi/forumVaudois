<?php 
session_start();
$_SESSION["isConnected"] = true; // Simuler un utilisateur connecté pour ce header
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
