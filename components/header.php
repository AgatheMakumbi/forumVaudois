<?php 
session_start();
$_SESSION["isConnected"] = false;
?>

<header class="header">
    <div class="logo">
        <a href="/ForumVaudois/">
            <img src="/ForumVaudois/assets/images/logo.png" alt="Forum Vaudois Logo" class="logo-img">
        </a>
    </div>
    <!-- ./ = la racine du dossier forumvaudois-->
    <nav class="nav-buttons">
        <a href="/ForumVaudois/pages/news.php" class="btn btn-news">Tout explorer</a>
        <a href="/ForumVaudois/pages/news.php?category='activity'" class="btn btn-news">Activité 🪂</a>
        <a href="/ForumVaudois/pages/news.php?category='food'" class="btn btn-news">Food​ 🍴</a>
        <a href="/ForumVaudois/pages/news.php?category='nature'" class="btn btn-news">Nature 🌿​</a>
        <a href="/ForumVaudois/pages/news.php?category='culture'" class="btn btn-news">Culture ​​🎥​</a>
        <a href="/ForumVaudois/pages/about.php" class="btn btn-news">À propos</a>
        <?php 
        if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
            // Menu pour utilisateur connecté
            echo <<<HEREDOC
                <a href="/ForumVaudois/pages/profil.php" class="btn btn-login">Profil</a>
                <a href="/ForumVaudois/pages/createPost.php" class="btn btn-login">Post it!</a>
            HEREDOC;
        } else {
            // Menu pour utilisateur non connecté
            echo <<<HEREDOC
                <a href="/ForumVaudois/pages/login.php" class="btn btn-login">Crée un compte / Se connecter</a>
            HEREDOC;
        }
        ?>
    </nav>
</header>
