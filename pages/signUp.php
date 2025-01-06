<?php

/**
 * Script d'inscription pour les nouveaux utilisateurs.
 * Ce script gère la validation des données utilisateur, la création d'un compte,
 * et l'envoi d'un email de confirmation.
 */

// Inclut les dépendances nécessaires
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

//Démarre la session 
session_start();
$_SESSION["isConnected"] = false;

/**
 * Fonction pour récupérer les traductions
 * 
 * @param string $key La clé de la traduction à récupérer
 * @param string $default La valeur par défaut à retourner si la traduction n'existe pas
 * @return string La traduction ou la valeur par défaut
 */
function t($key, $default = '')
{
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

/**
 * Traite le formulaire d'inscription.
 * Valide les données utilisateur et procède à la création du compte si valide.
 */
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
                $confirmationLink = "https://forumvaudois.makumbi.ch/pages/confirmation.php?token=" . urlencode($UserToken);

                // Préparation et envoi de l'email de confirmation
                //$transport = Transport::fromDsn('smtp://localhost:1025');
                $transport = Transport::fromDsn('smtp://agathe@makumbi.ch:KINSHASA4everbaby!@ariel.kreativmedia.ch:465');
                $mailer = new Mailer($transport);
                $confirmationEmail = (new Email())
                    ->from('agathe@makumbi.ch')
                    ->to($email)
                    ->subject(t('signup_email_subject', 'Confirm your registration'))
                    ->html("<p>" . t('signup_email_body', 'Please confirm your email by clicking the following link') . ":</p><a href='{$confirmationLink}'>" . t('signup_email_link', 'Confirm my registration') . "</a>");

                $mailer->send($confirmationEmail);
                $validationMessage = "<p style='color: green;'>Inscription réussie ! Un mail de confirmation a été envoyé.</p>";
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
                <form class="signup-form" action="signUp.php" method="POST">
                    <h2 class="form-title">Créer un compte</h2>

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