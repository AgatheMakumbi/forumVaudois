<?php session_start() ;
require_once 'vendor/autoload.php';
require_once __DIR__ . '/lang/lang_func.php'; // Inclusion corrigée du fichier

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;

// Charger les messages de traduction
try {
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
    $messages = loadLanguage($lang);
} catch (Exception $e) {
    // En cas d'erreur, afficher un message ou une langue par défaut
    $messages = loadLanguage('fr');
    error_log($e->getMessage());
}

// Initialisation de la connexion avec la base de données
$db = new DbManagerCRUD();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="
/assets/css/style.css?v=<?= time(); ?>">


    <title>Forum Vaudois - Accueil</title>
</head>

<body>
    
    <div class="wrapper">
        <!-- Inclusion du header -->
        <?php include './components/header.php';?>

        <main class="main-content">
            <section class="welcome-section">
                <h1><?php echo $messages['home_title']; ?></h1>
                <p>
                    <?php echo $messages['home_text']; ?>
                    <br>
                    <?php echo $messages['home_text_hashtag']; ?>
                </p>
            </section>
            <section class="thread-section">
                <a href="/pages/news.php?category=activity" class="thread-btn"><?php echo $messages['home_button_activities']; ?></a>
                <a href="/pages/news.php?category=food" class="thread-btn"><?php echo $messages['home_button_food']; ?></a>
                <a href="/pages/news.php?category=nature" class="thread-btn"><?php echo $messages['home_button_nature']; ?></a>
                <a href="/pages/news.php?category=culture" class="thread-btn"><?php echo $messages['home_button_culture']; ?></a>
            </section>
        </main>

        <!-- Inclusion du footer -->
        <?php include './components/footer.php' ?>
    </div>
</body>

</html>