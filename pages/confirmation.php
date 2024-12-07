<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;

$dbManager = new DbManagerCRUD();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>confirmation</title>
</head>

<body>
    <?php include '../components/header.php' ?>
    <?php
    // Token validation
    if (isset($_GET['token']) && !empty($_GET['token'])) {
    $UserToken = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
    }
    if ($UserToken && preg_match("/^[a-zA-Z0-9_-]+$/", $UserToken)) {
        // Find corresonding User
        $user = $$dbManager->getUserByToken($UserToken);

        if ($user !==null) {
            // User Validation
            if ($dbManager->verifyUser($user['id'], $user)) {
                $confirmationMessage = "Votre compte est bien confirmé !";
            } else {
                $confirmationMessage ="Une erreur est survenue lors de la confirmation. Veuillez réessayer plus tard.";
            }
        } else {
            $confirmationMessage = "Lien de confirmation invalide ou expiré.";
        }
    } else {
        $confirmationMessage = "Token non valide ou absent. Veuillez vérifier le lien envoyé par e-mail.";
    }
    ?>
    <main class="main-content">
        <h1 class="welcome-text"><? $confirmationMessage ?></h1>
        <p class="subtitle">Découvrez notre plateforme et rejoignez notre communauté grandissante. Connectez-vous pour accéder à tout le contenu </p>
        <button class="submit-btn"><a href="/ForumVaudois/pages/login.php">Se connecter</a></button>

    </main>

</body>

</html>