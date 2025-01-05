<?php

/**
 * Script de la page "À propos"
 * Initialise la session, gère la traduction, et affiche le contenu de la page.
 */

// Inclut les dépendances nécessaires
require_once __DIR__ . '/../lang/lang_func.php';

// Démarre la session
session_start();

// Gère la traduction 
try {
    /**
     * Récupère la langue depuis la requête GET, la session, ou utilise 'fr' par défaut.
     * @var string $lang Langue sélectionnée.
     */
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');

    /**
     * Charge les traductions pour la langue sélectionnée.
     * @var array $messages Tableau contenant les messages traduits.
     */
    $messages = loadLanguage($lang);

    // Stocke la langue dans la session pour une utilisation future
    $_SESSION['LANG'] = $lang;
} catch (Exception $e) {
    // Charge par défaut le français en cas d'erreur
    $messages = loadLanguage('fr');

    // Log l'erreur pour débogage
    error_log($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Métadonnées de la page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>À Propos</title>
</head>

<body>
    <div class="wrapper">
        <?php
        // Inclusion du header
        include '../components/header.php';
        ?>

        <main class="main-content-about">
            <!-- Section d'introduction -->
            <section class="introduction">
                <div class="content">
                    <h2><?php echo $messages['about_intro_title']; ?></h2>
                    <p><?php echo $messages['about_intro_content']; ?></p>
                </div>
                <div class="objectives">
                    <h2><?php echo $messages['about_mission_title']; ?></h2>
                    <ul>
                        <li><?php echo $messages['about_mission_content1']; ?></li>
                        <li><?php echo $messages['about_mission_content2']; ?></li>
                        <li><?php echo $messages['about_mission_content3']; ?></li>
                    </ul>
                </div>
            </section>

            <!-- Section de l'équipe -->
            <section class="team">
                <h2><?php echo $messages['about_team_title']; ?></h2>
                <div class="team-members">
                    <div class="member">
                        <div class="icon">AJ</div>
                        <p class="name"><?php echo $messages['about_team_josh_name']; ?></p>
                        <p class="role"><?php echo $messages['about_team_josh_role']; ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">DM</div>
                        <p class="name"><?php echo $messages['about_team_mic_name']; ?></p>
                        <p class="role"><?php echo $messages['about_team_mic_role']; ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">JM</div>
                        <p class="name"><?php echo $messages['about_team_mat_name']; ?></p>
                        <p class="role"><?php echo $messages['about_team_mat_role']; ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">MA</div>
                        <p class="name"><?php echo $messages['about_team_aga_name']; ?></p>
                        <p class="role"><?php echo $messages['about_team_aga_role']; ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">MJ</div>
                        <p class="name"><?php echo $messages['about_team_joa_name']; ?></p>
                        <p class="role"><?php echo $messages['about_team_joa_role']; ?></p>
                    </div>
                </div>
            </section>
        </main>

        <?php
        // Inclusion du footer
        include '../components/footer.php';
        ?>
    </div>
</body>


</html>