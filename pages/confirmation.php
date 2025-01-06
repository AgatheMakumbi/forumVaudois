<?php
/**
 * Script de confirmation de compte utilisateur.
 * Ce script traite la validation d'un utilisateur en fonction d'un token envoyé par email.
 */

require_once '../vendor/autoload.php'; // Chargement des dépendances via Composer

use M521\ForumVaudois\CRUDManager\DbManagerCRUD; // Import de la classe pour gérer les opérations CRUD

/** @var DbManagerCRUD $dbManager Gestionnaire des opérations CRUD */
$dbManager = new DbManagerCRUD();

/** @var string $confirmationMessage Message affiché à l'utilisateur après tentative de validation */
$confirmationMessage = '';

/** @var bool $canConnect Indique si l'utilisateur peut se connecter après validation */
$canConnect = false;

// ============================
// Validation du Token
// ============================

/** 
 * @var string|null $UserToken Token récupéré depuis l'URL 
 * Le token est utilisé pour identifier et valider l'utilisateur.
 */
$UserToken = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$UserToken) {
    // Aucun token trouvé
    $confirmationMessage = "Token manquant. Veuillez vérifier le lien envoyé par e-mail.";
} elseif (!preg_match("/^[a-zA-Z0-9]{32}$/", $UserToken)) {
    // Token non valide
    $confirmationMessage = "Token invalide.";
} else {
    // Rechercher l'utilisateur correspondant au token
    /** @var int|null $userId ID de l'utilisateur correspondant au token */
    $userId = $dbManager->getUserByToken($UserToken);

    if ($userId === null) {
        // Token introuvable ou expiré
        $confirmationMessage = "Lien de confirmation invalide ou expiré.";
    } else {
        // Tenter de vérifier l'utilisateur
        if ($dbManager->verifyUser($userId)) {
            // Succès de la confirmation
            $confirmationMessage = "Votre compte est bien confirmé !";
            $canConnect = true;
        } else {
            // Échec de la confirmation
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
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>"> <!-- Ajout du cache-buster -->
    <title>Confirmation</title>
</head>

<body>
    <!-- Lien retour vers la page d'accueil -->
    <a href="../index.php" class="header-back">Retour à l'accueil</a>

    <main class="main-content-confirmation">
        <div class="confirmation-container">
            <!-- Section gauche contenant le logo et le slogan -->
            <div class="left-side">
                <img src="../assets/images/logo1.png" alt="Forum Vaudois Logo" class="logo">
                <h1 class="slogan">Rejoignez le forum de la région !</h1>
            </div>

            <!-- Section droite affichant le message de confirmation -->
            <div class="right-side">
                <div class="confirmation-message">
                    <h2><?= htmlspecialchars($confirmationMessage) ?></h2>
                    <!-- Message sécurisé contre les injections -->

                    <?php if ($canConnect): ?>
                        <!-- Si l'utilisateur peut se connecter -->
                        <p class="subtitle">Découvrez notre plateforme et rejoignez notre communauté.</p>
                        <a href="/pages/login.php" class="submit-btn">Se connecter</a>
                    <?php else: ?>
                        <!-- Si la confirmation a échoué -->
                        <p class="subtitle">Vous rencontrez des difficultés ?</p>
                        <a href="/pages/signUp.php" class="submit-btn">Réessayer l'inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>