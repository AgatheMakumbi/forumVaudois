<?php
session_start(); // Démarre la session

require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de traduction

try {
    // Récupère la langue depuis la requête GET ou utilise la langue par défaut (français)
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    $messages = loadLanguage($lang); // Charge les traductions
    $_SESSION['LANG'] = $lang; // Stocke la langue dans la session
} catch (Exception $e) {
    $messages = loadLanguage('fr'); // Charge par défaut le français en cas d'erreur
    error_log($e->getMessage()); // Log l'erreur pour débogage
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <title>À Propos</title>
</head>

<body>
    <?php 
     include '../components/header.php'; 
    ?>
    <main class="main-content-about">
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

    <!-- Team Section -->
    <section class="team">
        <h2><?php echo $messages['about_team_title']; ?></h2>
        <div class="team-members">
            <div class="member">
                <div class="icon">AJ</div>
                <p><?php echo $messages['about_team_josh_name']; ?></p>
                <p class="role"><?php echo $messages['about_team_josh_role']; ?></p>
            </div>
            <div class="member">
                <div class="icon">DM</div>
                <p><?php echo $messages['about_team_mic_name']; ?></p>
                <p class="role"><?php echo $messages['about_team_mic_role']; ?></p>
            </div>
            <div class="member">
                <div class="icon">JM</div>
                <p><?php echo $messages['about_team_mat_name']; ?></p>
                <p class="role"><?php echo $messages['about_team_mat_role']; ?></p>
            </div>
            <div class="member">
                <div class="icon">MA</div>
                <p><?php echo $messages['about_team_aga_name']; ?></p>
                <p class="role"><?php echo $messages['about_team_aga_role']; ?></p>
            </div>
            <div class="member">
                <div class="icon">MJ</div>
                <p><?php echo $messages['about_team_joa_name']; ?></p>
                <p class="role"><?php echo $messages['about_team_joa_role']; ?></p>
            </div>
        </div>
    </section>
</main>

<?php include '../components/footer.php'; ?>
</body>

</html>
