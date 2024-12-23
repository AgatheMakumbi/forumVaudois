<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\Entity\Personne;
use M521\ForumVaudois\CRUDManager\DbManagerCRUD;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Initialisation
$db = new DbManagerCRUD();
$erreurs = ['email' => '', 'password' => ''];
$password = '';
$email = '';

// Traitement de la connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération des entrées utilisateur
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    //Vérification si l'utilisateur est déjà connecté
    if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
        header('Location: /ForumVaudois/index.php');
        exit(); 
    }else{
        var_dump($email, $password);
        // Vérification des champs
        if (empty($email) || empty($password)) {
            $erreurs['email'] = 'Veuillez renseigner votre adresse e-mail';
            $erreurs['password'] = 'Veuillez renseigner votre mot de passe';
        }else{
            // Tentative de connextion
            $userId = $db->loginUser($email,$password);
            var_dump($userId);
            if ($userId) {
                $_SESSION["isConnected"] = true;
                $_SESSION["id"] = $userId;
                header('Location: /ForumVaudois/index.php');
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
            <form class="login-form, signup-form"  action="login.php" method="POST">
                <h2 class="form-title">Login</h2>

                <?php if (!empty($erreurs['email']) || !empty($erreurs['password'])) : ?>
                    <p class="error-message"><?= htmlspecialchars($erreurs['email']) ?></p>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="yayan@durian.cc" required autocomplete="username" maxlength="50" value="<?php echo htmlspecialchars($email); ?>">
                    <p style="color:red;"><?php echo $erreurs['email']; ?></p>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********" minlength="8" maxlength="20" autocomplete="current-password" required value="<?php echo htmlspecialchars($password); ?>" required>
                    <p style="color:red;"><?php echo $erreurs['password']; ?></p>
                </div>

                <div class="form-links">
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="submit-btn">Login</button>

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
