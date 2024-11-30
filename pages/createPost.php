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
            <h2 class="form-title">Créer un post</h2>
            
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="post-content">Que souhaite tu partager?</label>
                <input type="text" id="post-content" name="post-content" required>
            </div>

            <div class="form-group">
                <label for="city">Ville</label>
                <select name="city" id="city"  required>
                    <option value="Neuchatel">Neuchatel</option>
                    <option value="Vevey">Vevey</option>
                    <option value="Lausanne">Lausanne</option>
                </select>
            </div>

            <div class="form-group">
                <label for="budget">Quel budget prévoir ?</label>
                <input type="number" id="budget" name="budget" required>
            </div>

            <div class="form-group">
                <label for="addresse">Si applicable saisit l'adresse</label>
                <input type="text" id="addresse" name="addresse">
            </div>
<!-- ajouter upload d'images-->

            <button type="submit" class="submit-btn">Partager</button>
        </form>
    </main>
</body>
</html>