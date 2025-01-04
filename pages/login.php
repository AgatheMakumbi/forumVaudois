<?php
/**
 * Page de connexion utilisateur.
 * Cette page permet aux utilisateurs de se connecter en utilisant leurs identifiants.
 * Elle gère le traitement des formulaires et l'affichage dynamique des messages multilingues.
 */

// Inclusion des fichiers nécessaires
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;

/**
 * Gestion de la langue : 
 * - Récupère la langue depuis la requête GET, la session ou utilise 'fr' par défaut.
 * 
 * @var string $lang Langue sélectionnée.
 * @var array $messages Messages traduits pour la langue sélectionnée.
 */
try {
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    $messages = loadLanguage('fr'); // Par défaut, charge le français
    error_log($e->getMessage()); // Log l'erreur pour le débogage
}

// Activation des erreurs (pour le développement uniquement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrage de la session utilisateur
session_start();

/**
 * Initialisation des variables :
 * - @var DbManagerCRUD $db Instance pour gérer les données.
 * - @var array $erreurs Tableau pour stocker les messages d'erreur liés au formulaire.
 * - @var string $email Email soumis par l'utilisateur.
 * - @var string $password Mot de passe soumis par l'utilisateur.
 */
$db = new DbManagerCRUD();
$erreurs = ['email' => '', 'password' => ''];
$email = '';
$password = '';

// Traitement du formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /**
     * Récupération et validation des données du formulaire :
     * - Email : Validation pour vérifier que le format est correct.
     * - Mot de passe : Désinfection des caractères spéciaux.
     */
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    // Vérifie si l'utilisateur est déjà connecté
    if (isset($_SESSION["isConnected"]) && $_SESSION["isConnected"]) {
        header('Location: /ForumVaudois/pages/news.php');
        exit();
    }

    // Vérification des champs obligatoires
    if (empty($email)) {
        $erreurs['email'] = $messages['login_error_email'];
    }
    if (empty($password)) {
        $erreurs['password'] = $messages['login_error_password'];
    }

    // Si aucun champ n'est vide, tentative de connexion
    if (empty($erreurs['email']) && empty($erreurs['password'])) {
        /**
         * Vérification des identifiants de l'utilisateur :
         * - Utilise la méthode `loginUser` pour valider les informations.
         * 
         * @var int|false $userId Identifiant utilisateur si la connexion réussit, sinon `false`.
         */
        $userId = $db->loginUser($email, $password);

        if ($userId) {
            // Succès : Création des variables de session
            $_SESSION["isConnected"] = true;
            $_SESSION["id"] = $userId;
            header('Location: /ForumVaudois/pages/news.php');
            exit();
        } else {
            // Erreur : Identifiants incorrects
            $erreurs['email'] = $messages['login_error_credentials'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title><?= t('login_title'); ?></title>
</head>

<body>
    <!-- Lien de retour à la page d'accueil -->
    <a href="../index.php" class="header-back"><?= t('login_back_home'); ?></a>

    <main class="main-content-login">
        <div class="login-container">
            <!-- Section gauche : Logo et slogan -->
            <div class="left-side">
             <img src="../assets/images/logo1.png" alt="Forum Vaudois Logo" class="logo">
               <h1 class="slogan"><?= t('login_left_title'); ?></h1>
            </div>


            <!-- Section droite : Formulaire de connexion -->
            <div class="right-side">
                <!-- Sélecteur de langue -->
                <div class="language-selector">
                    <form method="GET">
                        <label for="language"><?= t('change_language'); ?></label>
                        <select name="lang" id="language" onchange="this.form.submit()">
                            <option value="fr" <?= $lang == 'fr' ? 'selected' : ''; ?>>Français</option>
                            <option value="en" <?= $lang == 'en' ? 'selected' : ''; ?>>English</option>
                            <option value="de" <?= $lang == 'de' ? 'selected' : ''; ?>>Deutsch</option>
                            <option value="it" <?= $lang == 'it' ? 'selected' : ''; ?>>Italiano</option>
                        </select>
                    </form>
                </div>
<br>
                <!-- Formulaire de connexion -->
                <form class="login-form" action="login.php" method="POST">
                    <h2 class="form-title"><?= t('login_form_title'); ?></h2>

                    <!-- Messages d'erreur -->
                    <?php if (!empty($erreurs['email']) || !empty($erreurs['password'])): ?>
                        <p class="error-message"><?= htmlspecialchars($erreurs['email']); ?></p>
                    <?php endif; ?>

                    <!-- Champ email -->
                    <div class="form-group">
                        <label for="email"><?= t('login_form_email'); ?></label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                        <p style="color:red;"><?= $erreurs['email']; ?></p>
                    </div>

                    <!-- Champ mot de passe -->
                    <div class="form-group">
                        <label for="password"><?= t('login_form_password'); ?></label>
                        <input type="password" id="password" name="password" required>
                        <p style="color:red;"><?= $erreurs['password']; ?></p>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit" class="submit-btn"><?= t('login_form_button'); ?></button>
                </form>

                <!-- Lien vers la création de compte -->
                <p class="register-link">
                    <?= t('login_no_account'); ?> <a href="signUp.php"><?= t('login_sign_up'); ?></a>
                </p>
            </div>
        </div>
    </main>
</body>

</html>
