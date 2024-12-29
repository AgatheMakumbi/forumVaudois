<?php
//require_once '/../vendor/autoload.php';
require_once '../lang/lang_func.php';

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
    <?php session_start();
    include '../components/header.php';
    ?>

    <div class="wrapper">
        <main class="main-content-about">
            <section class="introduction">
                <div class="content">
                    <h2><?php echo (t('about_intro_title')) ?></h2>
                    <p><?php echo (t('about_intro_content')) ?></p>
                </div>
                <div class="objectives">
                    <h2><?php echo (t('about_mission_title')) ?></h2>
                    <ul>
                        <li><?php echo (t('about_mission_content1')) ?></li>
                        <li><?php echo (t('about_mission_content2')) ?>.</li>
                        <li><?php echo (t('about_mission_content3')) ?></li>
                    </ul>
                </div>
            </section>

            <!-- Team Section -->
            <section class="team">
                <h2>L'équipe</h2>
                <div class="team-members">
                    <div class="member">
                        <div class="icon">AJ</div>
                        <p class="name"><?php echo (t('about_team_josh_name')) ?></p>
                        <p class="role"><?php echo (t('about_team_josh_role')) ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">DM</div>
                        <p class="name"><?php echo (t('about_team_mic_name')) ?></p>
                        <p class="role"><?php echo (t('about_team_mic_role')) ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">JM</div>
                        <p class="name"><?php echo (t('about_team_mat_name')) ?></p>
                        <p class="role"><?php echo (t('about_team_mat_role')) ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">MA</div>
                        <p class="name"><?php echo (t('about_team_aga_name')) ?></p>
                        <p class="role"><?php echo (t('about_team_aga_role')) ?></p>
                    </div>
                    <div class="member">
                        <div class="icon">MJ</div>
                        <p class="name"><?php echo (t('about_team_joa_name')) ?></p>
                        <p class="role"><?php echo (t('about_team_joa_role')) ?></p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer Inclusion -->
        <?php include '../components/footer.php'; ?>
    </div>
</body>

</html>