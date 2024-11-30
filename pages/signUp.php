<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Sign up</title>
</head>
<body>
<?php include '../components/header.php'?>
<main class="main-content">
        <form class="signup-form" action="signup.php" method="POST">
            <h2 class="form-title">Créer un compte</h2>
            
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="portable">N° Portable</label>
                <input type="tel" id="portable" name="portable" pattern="[0-9]{10}" title="Veuillez entrer un numéro de téléphone valide (10 chiffres)" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="submit-btn">S'inscrire</button>
        </form>
    </main>
</body>
</html>