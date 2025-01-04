<?php
/**
 * Script d'inscription pour les nouveaux utilisateurs.
 * Ce script gère la validation des données utilisateur, la création d'un compte,
 * et l'envoi d'un email de confirmation.
 */

session_start();
$_SESSION["isConnected"] = false;

// Inclusion des dépendances nécessaires
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Fonction pour récupérer les traductions
function t($key, $default = '') {
    global $messages;
    return isset($messages[$key]) ? $messages[$key] : $default;
}

try {
    // Gestion de la langue
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    $messages = loadLanguage('fr'); // Par défaut en français en cas d'erreur
    error_log($e->getMessage());
}

// Initialisation des variables
$dbManager = new DbManagerCRUD();
$erreurs = ['username' => '', 'email' => '', 'password' => ''];
$validationMessage = '';
$username = '';
$password = '';
$email = '';

// ============================
// TRAITEMENT DU FORMULAIRE
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation du nom d'utilisateur
    if (!filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[A-Za-z0-9_]{1,20}$/"]])) {
        $erreurs['username'] = t('signup_error_username', 'is invalid (max 20 characters).');
    } elseif ($dbManager->existsUsername($username)) {
        $erreurs['username'] = t('signup_error_username_taken', 'This username is already taken.');
    }

    // Validation de l'email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!empty($email) && $email) {
        $emailPattern = "/^(?=.*[A-Za-z])([a-zA-Z0-9!#$%&'*+=?^_`{|}~.-]+)@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";
        if (!preg_match($emailPattern, $email)) {
            $erreurs['email'] = t('signup_error_email_invalid', 'The email address must be valid.');
        } elseif ($dbManager->existsEmail($email)) {
            $erreurs['email'] = t('signup_error_email_taken', 'An account already exists with this email address.');
        }
    } else {
        $erreurs['email'] = t('signup_error_email_empty', 'Please enter a valid email address.');
    }

    // Validation du mot de passe
    if (!filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,20}$/"]])) {
        $erreurs['password'] = t('signup_error_password', 'The password must be at least 8 characters long and include one uppercase letter, one lowercase letter, and one number.');
    }

    // Si aucune erreur, on procède à la création du compte
    if (empty($erreurs['username']) && empty($erreurs['email']) && empty($erreurs['password'])) {
        try {
            $UserToken = $dbManager->generateToken();
            $newUser = new User($username, $email, $password, $UserToken);

            if ($dbManager->createUser($newUser)) {
                $confirmationLink = "http://localhost/ForumVaudois/pages/confirmation.php?token=" . urlencode($UserToken);

                $transport = Transport::fromDsn('smtp://localhost:1025');
                $mailer = new Mailer($transport);
                $confirmationEmail = (new Email())
                    ->from('inscription@forumvaudois.ch')
                    ->to($email)
                    ->subject(t('signup_email_subject', 'Confirm your registration'))
                    ->html("<p>" . t('signup_email_body', 'Please confirm your email by clicking the following link') . ":</p><a href='{$confirmationLink}'>" . t('signup_email_link', 'Confirm my registration') . "</a>");

                $mailer->send($confirmationEmail);
                $validationMessage = "<a href='http://localhost:8025'><p style='color: green;'>" . t('signup_success', 'Registration successful! A confirmation email has been sent.') . "</p></a>";
            } else {
                $validationMessage = "<p style='color: red;'>" . t('signup_error_general', 'An error occurred. Please try again.') . "</p>";
            }
        } catch (Exception $e) {
            $validationMessage = "<p style='color: red;'>" . t('signup_error_exception', 'Error:') . " " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title><?= t('signup_title', 'Join the region’s forum!'); ?></title>
</head>

<body>
    <a href="../index.php" class="header-back"><?= t('signup_back_to_home', 'Back to home'); ?></a>

    <main class="main-content-signup">
        <div class="signup-container">
            <div class="left-side">
                <img src="../assets/images/logo1.png" alt="Forum Vaudois Logo" class="logo">
                <h1 class="slogan"><?= t('signup_title', 'Join the region’s forum!'); ?></h1>
            </div>
            <div class="right-side">
                <div class="language-selector">
                    <form method="GET">
                        <label for="language"><?= t('signup_change_language', 'Change language:'); ?></label>
                        <select name="lang" id="language" onchange="this.form.submit()">
                            <option value="fr" <?= $lang == 'fr' ? 'selected' : ''; ?>>Français</option>
                            <option value="en" <?= $lang == 'en' ? 'selected' : ''; ?>>English</option>
                            <option value="de" <?= $lang == 'de' ? 'selected' : ''; ?>>Deutsch</option>
                            <option value="it" <?= $lang == 'it' ? 'selected' : ''; ?>>Italiano</option>
                        </select>
                    </form>
                </div>

                <form class="signup-form" action="signup.php" method="POST">
                    <h2 class="form-title"><?= t('signup_form_title', 'Create an account'); ?></h2>

                    <div class="form-group">
                        <label for="username"><?= t('signup_form_username', 'Username'); ?></label>
                        <input type="text" id="username" name="username" maxlength="20" required
                            value="<?= htmlspecialchars($username); ?>">
                        <p style="color:red;"><?= $erreurs['username']; ?></p>
                    </div>

                    <div class="form-group">
                        <label for="email"><?= t('signup_form_email', 'Email'); ?></label>
                        <input type="email" id="email" name="email" maxlength="50" required
                            value="<?= htmlspecialchars($email); ?>">
                        <p style="color:red;"><?= $erreurs['email']; ?></p>
                    </div>

                    <div class="form-group">
                        <label for="password"><?= t('signup_form_password', 'Password'); ?></label>
                        <input type="password" id="password" name="password" minlength="8" maxlength="20" required>
                        <p style="color:red;"><?= $erreurs['password']; ?></p>
                    </div>

                    <button type="submit" class="submit-btn"><?= t('signup_form_button', 'Sign up'); ?></button>
                    <?= $validationMessage; ?>
                </form>
                <div class="login-link">
                    <p><?= t('signup_text_already_account', 'Already have an account?'); ?> <a href="login.php"><?= t('signup_link_login', 'Log in'); ?></a></p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
