<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\User;

$dbManager = new DbManagerCRUD();
$confirmationMessage = '';
$canConnect = false;

//  validation du Token
$UserToken = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);


if (!$UserToken) {
    $confirmationMessage = "Token manquant. Veuillez vérifier le lien envoyé par e-mail.";
} elseif (!preg_match("/^[a-zA-Z0-9]{32}$/", $UserToken)) {
    $confirmationMessage = "Token invalide.";
} else {
    // Trouver l'utilisateur correspondant
    $userId = $dbManager->getUserByToken($UserToken);

    if ($userId === null) {
        $confirmationMessage = "Lien de confirmation invalide ou expiré.";
    } else {
        // Verification de l'utilisateur
        if ($dbManager->verifyUser($userId)) {
            $confirmationMessage = "Votre compte est bien confirmé !";
            $canConnect = true;
        } else {
            $confirmationMessage = "Erreur lors de la confirmation. Veuillez réessayer.";
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
    <title>confirmation</title>
</head>

<body>
    <?php include '../components/header.php' ?>
    
    <main class="main-content">
    <h1 class="welcome-text"><?php echo htmlspecialchars($confirmationMessage) ?></h1>
        
        <?php if ($canConnect): ?>
            <p class="subtitle">Découvrez notre plateforme et rejoignez notre communauté.</p>
            <button class="submit-btn"><a href="/ForumVaudois/pages/login.php">Se connecter</a></button>
        <?php else: ?>
            <p class="subtitle">Vous rencontrez des difficultés ?</p>
            <button ><a href="/ForumVaudois/pages/signUp.php" class="btn btn-login">Réessayer l'inscription</a></button>
            
        <?php endif; ?> 

    </main>

</body>

</html>
