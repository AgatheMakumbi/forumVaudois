<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\Entity\Personne;
use M521\ForumVaudois\CRUDManager\DbManagerCRUD;

// Exemple de données simulées pour le test
$personne = new Personne("agathe", "makumbi", "makagathe7@gmail.com", "0779553315", "examplePassword");
$db = new DbManagerCRUD();

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Exemple de validation (vous pouvez connecter à une base de données pour authentification)
    if ($email === $personne->getEmail() && $password === $personne->getPassword()) {
        echo "Connexion réussie";
        // Redirection ou chargement de la session utilisateur
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>
</head>
<body>
<!-- svp ne pas enlever le inlude pour la nav bar svp (Agathe) -->
<?php include '../components/header.php'?>

<main class="main-content">
    
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
                        <p><strong>Ebayyou Anggoro</strong></p>
                        <p>The three main languages you need to know well are HTML, CSS, and JavaScript...</p>
                        <div class="post-meta">
                            <button class="btn-response">Add response</button>
                            <p>📍 Lausanne</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side">
            <form class="login-form" action="login.php" method="POST">
                <h2 class="form-title">Login</h2>

                <?php if (!empty($error)) : ?>
                    <p class="error-message"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="yayan@durian.cc" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********" required>
                </div>

                <div class="form-links">
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-submit">Login</button>

                <p class="signup-text">Don't have an account? <a href="/ForumVaudois/pages/signUp.php">Create one!</a></p>
            </form>
        </div>
    </div>
</main>
<footer class="login-footer">
    <p>Privacy Policy • Terms of Service</p>
</footer>
</body>
</html>
