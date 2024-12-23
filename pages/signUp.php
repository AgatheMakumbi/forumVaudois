<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Initialisation
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

    // Récupération des entrées utilisateur
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    
    // Validation du username (max 20 caractères, commence par majuscule, suit des minuscules)
    if (!filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP, ["options" =>["regexp" => "/^[A-Za-z0-9_]{1,20}$/"]])) {
        $erreurs['username'] = "Veuillez saisir un nom d'utilisateur de max 20 caractères.";
    } elseif ($dbManager->existsUsername($username)) {
        $erreurs['username'] = "Ce nom d'utilisateur est déjà pris.";
    }

    // Validation de l'email
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if (!empty($email) && $email) {
        // regex pour refuser les e-mails sans lettre et avec plus de 4 caractères spéciaux consécutifs
        $emailPattern = "/^(?=.*[A-Za-z])([a-zA-Z0-9!#$%&'*+=?^_`{|}~.-]+)@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";

        if (!preg_match($emailPattern, $email)) {
            $erreurs['email'] = "L'adresse e-mail doit contenir au moins une lettre et ne pas avoir plus de 4 caractères spéciaux consécutifs.";
        } else {
            // Vérifiez si l'e-mail existe déjà dans la base de données
            if (!empty($dbManager->existsEmail($email))) {
                $erreurs['email'] = "Un compte existe déjà avec cette adresse e-mail.";
            }
        }
    } else {
        $erreurs['email'] = "Veuillez saisir une adresse e-mail valide.";
    }

    // Validation du mot de passe (au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre)
    
    if (!filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, ["options" =>["regexp" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,20}$/"]])) {
        $erreurs['password'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre.";
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
                    ->subject('Confirmation de votre inscription au Forum Vaudois')
                    ->html("<p>Veuillez confirmer votre email en cliquant sur le lien suivant :</p><a href='{$confirmationLink}'>Confirmer mon inscription</a>");

                $mailer->send($confirmationEmail);
                $validationMessage = "<a href='http://localhost:8025'><p style='color: green;'>Inscription réussie ! Un mail de confirmation a été envoyé à votre adresse. </p></a>";
            } else {
                $validationMessage = "<p style='color: red;'>Une erreur est survenue. Veuillez réessayer.</p>";
            }
        } catch (Exception $e) {
            $validationMessage = "Erreur lors de l'envoi de l'e-mail.</p>";
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
    <title>Sign up</title>
</head>

<body>
    <?php echo $validationMessage; ?>
    <?php include '../components/header.php' ?>
    <main class="main-content">
        <form class="signup-form" action="signup.php" method="POST">

            <h2 class="form-title">Créer un compte</h2>

            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="luca1014" maxlength="20" required value="<?php echo htmlspecialchars($username); ?>">
                <p style="color:red;"><?php echo $erreurs['username']; ?></p>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="lucamartin@gmail.com" autocomplete="username" maxlength="50" required value="<?php echo htmlspecialchars($email); ?>">
                <p style="color:red;"><?php echo $erreurs['email']; ?></p>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" minlength="8" maxlength="20" autocomplete="current-password" required value="<?php echo htmlspecialchars($password); ?>">
                <p style="color:red;"><?php echo $erreurs['password']; ?></p>
            </div>

            <button type="submit" class="submit-btn">S'inscrire</button>
        </form>

    </main>
</body>