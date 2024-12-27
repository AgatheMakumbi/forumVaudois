<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


// Initialisation
$db = new DbManagerCRUD();
$erreurs = ['email' => '', 'password' => ''];
$email = '';
$password = '';

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
        header('Location: /ForumVaudois/news.php');
        exit(); 
    }else{
        //var_dump($email, $password);
        // Vérification des champs
        if (empty($email) || empty($password)) {
            $erreurs['email'] = 'Veuillez renseigner votre adresse e-mail';
            $erreurs['password'] = 'Veuillez renseigner votre mot de passe';
        }else{
            // Tentative de connextion
            $userId = $db->loginUser($email,$password);
            
            if ($userId) {
                $_SESSION["isConnected"] = true;
                $_SESSION["id"] = $userId;
                header('Location: /ForumVaudois/news.php');
                exit(); 
            }else{
                $erreurs['email'] = 'Adresse e-mail ou mot de passe incorrect';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>Login</title>
</head>
<body>
    <a href="../index.php" class="header-back">Retour à l'accueil</a>
<main class="main-content-login">
    <div class="login-container">
        <div class="left-side">
            <img src="../assets/images/logo1.png" alt="Forum Vaudois Logo" class="logo">
            <h1 class="slogan">Le forum de la région</h1>
        </div>
        <div class="right-side">
            <form class="login-form, signup-form"  action="login.php" method="POST">
                <h2 class="form-title">Login</h2>

                <?php if (!empty($erreurs['email']) || !empty($erreurs['password'])) : ?>
                    <p class="error-message"><?= htmlspecialchars($erreurs['email']) ?></p>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                    <p style="color:red;"><?= $erreurs['email']; ?></p>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                    <p style="color:red;"><?= $erreurs['password']; ?></p>
                </div>
                <button type="submit" class="submit-btn">Connexion</button>
            </form>
            <br>
            <p class="register-link">
                Vous n'avez pas encore de compte ? <a href="signUp.php">Créer un compte</a>
            </p>
        </div>
    </div>
</main>
</body>
</html>
