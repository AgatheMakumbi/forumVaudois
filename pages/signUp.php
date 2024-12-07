<?php
$erreurs["username"]="";
$erreurs["password"]="";
$erreurs["email"]="";
$validationMessage= "";
$username = "";
$password = "";
$email = "";
?>

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
        <?php echo $validationMessage; ?>
            <h2 class="form-title">Créer un compte</h2>
            
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" placeholder="luca1014" maxlength="20" required value="<?php echo htmlspecialchars($username); ?>"> <?php echo $erreurs['username']; ?>
            </div>

            <div class="form-group">
                <label for="email" >Email</label>
                <input type="email" id="email" name="email" placeholder="lucamartin@gmail.com" autocomplete="username"  maxlength="20" required value="<?php echo htmlspecialchars($email); ?>"> <?php echo $erreurs['email']; ?>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" minlength="8" maxlength="20" autocomplete="current-password" required value="<?php echo htmlspecialchars($password); ?>"> <?php echo $erreurs['password']; ?>
            </div>
            
            <button type="submit" class="submit-btn">S'inscrire</button>
        </form>
        <div><a class="submit-btn" href="/ForumVaudois/pages/login.php">Se connecter</a></div>
    </main>
</body>


<?php 
require_once 'vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Personne;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Input Sanitization (IMPORTANT)
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS, ["options" => ["regexp" => "/^[A-ZÇÉÈÊËÀÂÎÏÔÙÛ]{1}([a-zçéèêëàâîïôùû]+){1,19}$/"]]);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS, [
        "options" => ["regexp" => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,20}$/"]
    ]);



    // INPUT VALIDATION
    if (!empty($username) && $username) {
        //check if username already exists 
        if(!empty(existsUsername($username))){
            $erreurs['username'] = "Ce nom d'utiliateur est déjà pris";
        }
        
    }else{
        $erreurs['username'] = " Veuillez saisir commençant par une majuscule suivie de lettres (max 20 caractères).";
    }
    if (!empty($email) && $email) {
        //check if email already exists 
        if(!empty(existsEmail($email))){
            $erreurs['email'] = "Un compte existe déjà avec cet adresse email";
        }
    }else{
        $erreurs['email'] = "Veuillez saisir une adresse email valide.";
    }
    if (empty($password) && !$password) {
        $erreurs['password'] = " Veuillez saisir un mot de passe avec au moins une lettre majuscule, une lettre minuscule, un chiffre, et une longueur de 8 à 20 caractères.";
    }

    $UserToken ='';
    

    //USER CREATION 
    if (empty($erreurs)) {
        $dbManager = new DbManagerCRUD();
        $UserToken = $dbManager->generateToken();
        $newUser = new User($username,$email,$password,$UserToken);

        $IsCreated = $dbManager->createUser($newUser); 

        if($IsCreated){
            try {
                $confirmationLink = "http://localhost/ForumVaudois/confirmation.php?token=" . urlencode($UserToken);
                // SEND CONFITMATION EMAIL
                $transport = Transport::fromDsn('smtp://localhost:1025');
                $mailer = new Mailer($transport);
                $confirmationEmail = (new Email())
                    ->from('inscription@forumvaudois.ch')
                    ->to($email)
                    ->subject('Concerne : Inscription au forum Vaudois')
                    ->html('
                            <!DOCTYPE html>
                            <html lang="fr">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Confirmation d\'inscription</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        background-color: #f4f4f9;
                                        color: #333;
                                        padding: 0;
                                        margin: 0;
                                    }

                                    .email-container {
                                        background-color: #ffffff;
                                        max-width: 600px;
                                        margin: 20px auto;
                                        border-radius: 10px;
                                        overflow: hidden;
                                        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                                    }

                                    .email-header {
                                        background-color: #4CAF50;
                                        color: #ffffff;
                                        text-align: center;
                                        padding: 20px 0;
                                    }

                                    .email-header h1 {
                                        margin: 0;
                                        font-size: 24px;
                                    }

                                    .email-body {
                                        padding: 20px;
                                        text-align: center;
                                    }

                                    .email-body p {
                                        font-size: 16px;
                                        line-height: 1.6;
                                        margin-bottom: 20px;
                                    }

                                    .btn-confirm {
                                        display: inline-block;
                                        padding: 15px 25px;
                                        font-size: 16px;
                                        color: #ffffff;
                                        background-color: #4CAF50;
                                        text-decoration: none;
                                        border-radius: 5px;
                                        transition: background-color 0.3s ease;
                                    }

                                    .btn-confirm:hover {
                                        background-color: #45a049;
                                    }

                                    .email-footer {
                                        background-color: #f4f4f9;
                                        color: #666;
                                        text-align: center;
                                        padding: 20px 0;
                                        font-size: 14px;
                                    }

                                    .email-footer a {
                                        color: #4CAF50;
                                        text-decoration: none;
                                    }

                                    .email-footer a:hover {
                                        text-decoration: underline;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="email-container">
                                    <div class="email-header">
                                        <h1>Bienvenue au Forum Vaudois !</h1>
                                    </div>
                                    <div class="email-body">
                                        <p>Bonjour,</p>
                                        <p>Merci de vous être inscrit au Forum Vaudois. Veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous :</p>
                                        <a href=',$confirmationLink,' class="btn-confirm">Confirmer mon inscription</a>
                                        <p>Si vous n\'avez pas demandé cette inscription, veuillez ignorer cet e-mail.</p>
                                    </div>
                                    <div class="email-footer">
                                        <p>© 2024 Forum Vaudois. Tous droits réservés.</p>
                                        <p>Si vous avez des questions, visitez notre <a href="https://forumvaudois.ch/support">centre d\'aide</a>.</p>
                                    </div>
                                </div>
                            </body>
                            </html>
                        ');
                $mailer->send($confirmationEmail);
                
                $validationMessage = "<p style='color: green;'>Un mail a été envoyé ! <a href='http://localhost:8025'>voir le
                mail</a></p>";
                
            } catch (PDOException $e) {
                $validationMessage = "<p style='color: red;'>Un problème lors de l'envoi du mail est survenu.</p>";
            } 
        }else{
            $validationMessage = "<p style='color: red;'>Une erreur est survenue lors de la creation du compte. Veuillez réessayer.</p>";
        }
    }
    
    
    
    
?>
