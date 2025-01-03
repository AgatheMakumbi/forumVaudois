<?php
/**
 * Script d'inscription pour les nouveaux utilisateurs.
 * Ce script gère la validation des données utilisateur, la création d'un compte, 
 * et l'envoi d'un email de confirmation.
 */

session_start(); // Démarre la session utilisateur
$_SESSION["isConnected"] = false; // Initialise l'état de connexion à false

// Inclusion des dépendances nécessaires
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Initialisation des variables
$dbManager = new DbManagerCRUD(); // Gestionnaire CRUD pour la base de données
$erreurs = ['username' => '', 'email' => '', 'password' => '']; // Tableau pour stocker les erreurs de validation
$validationMessage = ''; // Message de validation pour l'utilisateur
$username = ''; // Nom d'utilisateur
$password = ''; // Mot de passe
$email = ''; // Adresse email

// ============================
// TRAITEMENT DU FORMULAIRE
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des entrées utilisateur
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation du nom d'utilisateur
    if (!filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^[A-Za-z0-9_]{1,20}$/"]])) {
        $erreurs['username'] = "Veuillez saisir un nom d'utilisateur valide (max 20 caractères).";
    } elseif ($dbManager->existsUsername($username)) {
        $erreurs['username'] = "Ce nom d'utilisateur est déjà pris.";
    }

    // Validation de l'email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!empty($email) && $email) {
        $emailPattern = "/^(?=.*[A-Za-z])([a-zA-Z0-9!#$%&'*+=?^_`{|}~.-]+)@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";
        if (!preg_match($emailPattern, $email)) {
            $erreurs['email'] = "L'adresse e-mail doit être valide et contenir au moins une lettre.";
        } elseif ($dbManager->existsEmail($email)) {
            $erreurs['email'] = "Un compte existe déjà avec cette adresse e-mail.";
        }
    } else {
        $erreurs['email'] = "Veuillez saisir une adresse e-mail valide.";
    }

    // Validation du mot de passe
    if (!filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, ["options" => ["regexp" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,20}$/"]])) {
        $erreurs['password'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
    }

    // Si aucune erreur, on procède à la création du compte
    if (empty($erreurs['username']) && empty($erreurs['email']) && empty($erreurs['password'])) {
        try {
            // Génération d'un token unique pour l'utilisateur
            $UserToken = $dbManager->generateToken();
            $newUser = new User($username, $email, $password, $UserToken);

            // Création du compte utilisateur
            if ($dbManager->createUser($newUser)) {
                $confirmationLink = "http://localhost/ForumVaudois/pages/confirmation.php?token=" . urlencode($UserToken);

                // Préparation et envoi de l'email de confirmation
                $transport = Transport::fromDsn('smtp://localhost:1025');
                $mailer = new Mailer($transport);
                $confirmationEmail = (new Email())
                    ->from('inscription@forumvaudois.ch')
                    ->to($email)
                    ->subject('Confirmation de votre inscription au Forum Vaudois')
                    ->html("<p>Veuillez confirmer votre email en cliquant sur le lien suivant :</p><a href='{$confirmationLink}'>Confirmer mon inscription</a>");

                $mailer->send($confirmationEmail);
                $validationMessage = "<a href='http://localhost:8025'><p style='color: green;'>Inscription réussie ! Un mail de confirmation a été envoyé.</p></a>";
            } else {
                $validationMessage = "<p style='color: red;'>Une erreur est survenue. Veuillez réessayer.</p>";
            }
        } catch (Exception $e) {
            $validationMessage = "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
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
    <title>Sign up</title>
</head>

<body>
    <a href="../index.php" class="header-back">Retour à l'accueil</a>

    <main class="main-content-signup">
        <div class="signup-container">
            <div class="left-side">
                <img src="../assets/images/logo1.png" alt="Forum Vaudois Logo" class="logo">
                <h1 class="slogan">Rejoignez le forum de la région !</h1>
            </div>
            <div class="right-side">
                <form class="signup-form" action="signup.php" method="POST">
                    <h2 class="form-title">Créer un compte</h2>

                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" maxlength="20" required
                            value="<?php echo htmlspecialchars($username); ?>">
                        <p style="color:red;"><?php echo $erreurs['username']; ?></p>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" maxlength="50" required
                            value="<?php echo htmlspecialchars($email); ?>">
                        <p style="color:red;"><?php echo $erreurs['email']; ?></p>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" minlength="8" maxlength="20" required
                            value="<?php echo htmlspecialchars($password); ?>">
                        <p style="color:red;"><?php echo $erreurs['password']; ?></p>
                    </div>

                    <button type="submit" class="submit-btn">S'inscrire</button>
                    <?php echo $validationMessage; ?>
                </form>
                <div class="login-link">
                    <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a></p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>