<?php
require_once 'vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Personne;

$personne = new Personne("agathe", "makumbi", "makagathe7@gmail.com", "0779553315", "shitIsReal");
echo $personne;
echo "<br>";

$db = new DbManagerCRUD();
echo $db;
echo "<br>";
echo $db->showCategories();


// Ce script permet d'envoyer un mail sur le serveur mail : MailHog en local
/* Maintenant que tout est prêt, il faut lancer le serveur de messagerie mailhog
Démarrage du serveur de messagerie
Windows :
Lancer l'exécution du fichier : MailHog_windows_386.exe
MacOs :
En ligne de commande (terminal)
// Remarque : Pour que cela fonctionne, il faut avoir démarré le serveur ;-) */
// Libraire permettant l'envoi de mail (Symfony Mailer)

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$transport = Transport::fromDsn('smtp://localhost:1025');
$mailer = new Mailer($transport);
$email = (new Email())
    ->from('dominique.martin@heig-vd.ch')
    ->to('desti.nataire@quelquepart.com')
    //->cc('cc@exemple.com')
    //->bcc('bcc@exemple.com')
    //->replyTo('replyto@exemple.com')
    //->priority(Email::PRIORITY_HIGH)
    ->subject('Concerne : Envoi de mail')
    ->text('Un peu de texte')
    ->html('<h1>Un peu de html</h1>');
$result = $mailer->send($email);
if ($result == null) {
    echo "Un mail a été envoyé ! <a href='http://localhost:8025'>voir le
mail</a>";
} else {
    echo "Un problème lors de l'envoi du mail est survenu";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Accueil</title>
</head>

<body>
    <?php include './components/header.php' ?>

    <main class="main-content">
        <h1 class="welcome-text">Bienvenue sur Secret News</h1>
        <p class="subtitle">Découvrez notre plateforme et rejoignez notre communauté grandissante. Connectez-vous ou inscrivez-vous pour accéder à tout le contenu exclusif.</p>

    </main>
</body>

</html>