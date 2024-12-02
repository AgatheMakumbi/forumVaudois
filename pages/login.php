<?php
require_once '../vendor/autoload.php';


use M521\ForumVaudois\Entity\Personne;

$personne = new Personne("agathe", "makumbi", "makagathe7@gmail.com", "0779553315", "shitIsReal");
echo $personne;
echo "<br>";



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Login</title>
</head>
<body>
<?php include '../components/header.php'?>
<main class="main-content">
        <form class="signup-form" action="login.php" method="POST">
            <h2 class="form-title">Se connecter</h2>
            

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="submit-btn">se connecter</button>
            <a href="/ForumVaudois/pages/signUp.php" class="btn btn-signup" style="margin-top: 50px;">Sign Up</a>
        </form>
    </main>
</body>
</html>