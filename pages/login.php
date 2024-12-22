<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Post;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialisation
$db = new DbManagerCRUD();
$erreurs = ['email' => '', 'password' => ''];
$password = '';
$email = '';

try {
    $posts = $db->showPosts();
    if (empty($posts)) {
        // Créer un post par défaut si aucun post n'est présent
        $randomPost = new Post(
            "Post par défaut", // Titre
            "Ceci est un contenu d'exemple car aucun post n'est encore disponible.", // Contenu
            0, // Budget
            1, // ID de l'auteur par défaut
            1, // ID de la ville par défaut
            1, // ID de la catégorie par défaut
            new DateTime(), // Date de création
            new DateTime(), // Date de mise à jour
            0, // ID
            "Adresse par défaut" // Adresse par défaut
        );
    } else {
        $randomPost = $posts[array_rand($posts)];
    }
} catch (Exception $e) {
    die("Erreur lors de la récupération des posts : " . $e->getMessage());
}

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
        header('Location: index.php');
        exit;
    } else {
        if (empty($email) || empty($password)) {
            $erreurs['email'] = 'Veuillez renseigner votre adresse e-mail';
            $erreurs['password'] = 'Veuillez renseigner votre mot de passe';
        } else {
            $userId = $db->loginUser($email, $password);
            if ($userId) {
                $_SESSION["isConnected"] = true;
                $_SESSION["id"] = $userId;
                header('Location: index.php');
                exit;
            } else {
                $erreurs['password'] = 'Identifiants incorrects';
            }
        }
    }
}

// Rafraîchissement de la page pour changer le post
if (isset($_GET['refresh']) && $_GET['refresh'] === 'true') {
    $randomPost = empty($posts) ? new Post(
        "Post par défaut",
        "Ceci est un contenu d'exemple car aucun post n'est encore disponible.",
        0,
        1,
        1,
        1,
        new DateTime(),
        new DateTime(),
        0,
        "Adresse par défaut"
    ) : $posts[array_rand($posts)];

    echo json_encode([
        'author' => htmlspecialchars($randomPost->getAuthor()),
        'text' => htmlspecialchars(substr($randomPost->getText(), 0, 100)),
        'city' => htmlspecialchars($randomPost->getCity()),
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css?v=<?= time(); ?>">

    <title>Login</title>
</head>
<body>

<main class="main-content">
<div class="back-arrow">
        <a href="javascript:history.back();" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Retour
        </a>
    </div>
    <div class="login-container">
        <div class="left-side">
            <img src="../assets/images/logo.png" alt="Forum Vaudois Logo" class="logo">
            <h1 class="slogan">Le forum de la région</h1>
            <div class="example-post">
                <div class="post-card">
                    <div class="post-avatar">
                        <img src="../assets/images/avatar-example.png" alt="Avatar Example">
                    </div>
                    <div class="post-content">
                        <p><strong id="post-author"><?= htmlspecialchars($randomPost->getAuthor()) ?></strong></p>
                        <p id="post-text"><?= htmlspecialchars(substr($randomPost->getText(), 0, 100)) ?>...</p>
                        <div class="post-meta">
                            <button class="btn-response">Add response</button>
                            <p id="post-city">📍 <?= htmlspecialchars($randomPost->getCity()) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side">
            <form class="login-form signup-form" action="login.php" method="POST">
                <h2 class="form-title">Login</h2>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="email@example.com" required autocomplete="username" maxlength="50" value="<?= htmlspecialchars($email); ?>">
                    <p style="color:red;"> <?= $erreurs['email']; ?> </p>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="********" minlength="8" maxlength="20" autocomplete="current-password" required>
                    <p style="color:red;"> <?= $erreurs['password']; ?> </p>
                </div>

                <div class="form-links">
                    <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="submit-btn">Connexion</button>

                <p class="signup-text">Pas encore de compte ? <a href="/ForumVaudois/pages/signUp.php">Créer un compte</a></p>
            </form>
        </div>
    </div>
</main>

<footer class="login-footer">
    <p>Privacy Policy • Terms of Service</p>
</footer>

<script>
    // Rafraîchit le post toutes les 60 secondes
    setInterval(() => {
        fetch('login.php?refresh=true')
            .then(response => response.json())
            .then(data => {
                document.getElementById('post-author').textContent = data.author;
                document.getElementById('post-text').textContent = data.text + "...";
                document.getElementById('post-city').textContent = `📍 ${data.city}`;
            })
            .catch(error => console.error('Erreur:', error));
    }, 60000); // 60 secondes
</script>
</body>
</html>
