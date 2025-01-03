<?php
/**
 * Page de connexion utilisateur.
 * Cette page gère à la fois le traitement du formulaire de connexion et l'affichage du formulaire.
 */

// Inclusion des fichiers nécessaires
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;

try {
    /**
     * Gestion de la langue :
     * - Récupère la langue depuis la requête GET, la session, ou utilise 'fr' par défaut.
     */
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    $messages = loadLanguage('fr'); // Charge par défaut le français en cas d'erreur
    error_log($e->getMessage()); // Log l'erreur pour débogage
}

// Activation de l'affichage des erreurs (pour le développement uniquement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarre une session utilisateur
session_start();

// Initialisation des variables
$db = new DbManagerCRUD(); // Instance de gestion de la base de données
$erreurs = ['email' => '', 'password' => '']; // Tableau des erreurs
$email = ''; // Adresse email
$password = ''; // Mot de passe

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Récupération et validation des données utilisateur :
     * - Email : validation du format.
     * - Mot de passe : désinfection des caractères spéciaux.
     */
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    // Redirection si l'utilisateur est déjà connecté
    if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
        header('Location: /ForumVaudois/pages/news.php');
        exit();
    } else {
        // Vérification des champs requis
        if (empty($email) || empty($password)) {
            $erreurs['email'] = 'Veuillez renseigner votre adresse e-mail';
            $erreurs['password'] = 'Veuillez renseigner votre mot de passe';
        } else {
            // Tentative de connexion
            $userId = $db->loginUser($email, $password);

            if ($userId) {
                // Succès : création de la session utilisateur
                $_SESSION["isConnected"] = true;
                $_SESSION["id"] = $userId;
                header('Location: /ForumVaudois/pages/news.php');
                exit();
            } else {
                // Erreur : informations de connexion incorrectes
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
    <!-- Lien de retour à la page d'accueil -->
    <a href="../index.php" class="header-back">Retour à l'accueil</a>

    <main class="main-content-login">
        <div class="login-container">
            <!-- Section gauche : Logo et slogan -->
            <div class="left-side">
                <img src="../assets/images/logo1.png" alt="Forum Vaudois Logo" class="logo">
                <h1 class="slogan">Le forum de la région</h1>
            </div>

            <!-- Section droite : Formulaire de connexion -->
            <div class="right-side">
                <form class="login-form signup-form" action="login.php" method="POST">
                    <h2 class="form-title">Login</h2>

                    <!-- Affichage des messages d'erreur -->
                    <?php if (!empty($erreurs['email']) || !empty($erreurs['password'])) : ?>
                        <p class="error-message"><?= htmlspecialchars($erreurs['email']) ?></p>
                    <?php endif; ?>

                    <!-- Champ email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                        <p style="color:red;"><?= $erreurs['email']; ?></p>
                    </div>

                    <!-- Champ mot de passe -->
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                        <p style="color:red;"><?= $erreurs['password']; ?></p>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit" class="submit-btn">Connexion</button>
                </form>

                <br>
                <!-- Lien vers la création de compte -->
                <p class="register-link">
                    Vous n'avez pas encore de compte ? <a href="signUp.php">Créer un compte</a>
                </p>
            </div>
        </div>
    </main>
</body>

</html>
