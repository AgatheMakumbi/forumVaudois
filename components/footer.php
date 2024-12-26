<?php
//require_once '../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// ============================
// TRAITEMENT DU FORMULAIRE
// ============================

//Initialisation des variables
$errors = ['name' => "", 'email'  => "", 'message' => ""];
$name = "";
$email = "";
$message = "";

//Récupération des entrées utilisateur et validation 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$name = filter_input(INPUT_POST, 'name', FILTER_CALLBACK, ['options' => 'trim']);
    //$email = filter_input(INPUT_POST, 'email', FILTER_CALLBACK, ['options' => 'trim']);
    //$message = filter_input(INPUT_POST, 'message', FILTER_CALLBACK, ['options' => 'trim']);


    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message = filter_input(INPUT_POST, 'message');

    if (empty($nom)) {
        $errors['name'] = "Veuillez saisir votre nom.";
    }
    if (empty($email)) {
        $errors['email'] = "Veuillez saisir une adresse email valide.";
    }
    if (empty($message)) {
        $errors['message'] = "Veuillez saisir un message.";
    }

    //Envoi du mail 
    $transport = Transport::fromDsn('smtp://localhost:1025');
    $mailer = new Mailer($transport);
    $email = (new Email())
        ->from($email)
        ->to('contact@forumvaudois.ch')
        ->subject('Formulaire de contact')
        ->text($message)
        ->html("<p>De : {$name}</p> <p>{$message}</p>");

    $result = $mailer->send($email);
    if ($result == null) {
        echo "Un mail a été envoyé ! <a href='http://localhost:8025'>voir le mail</a>";
    } else {
        echo "Un problème lors de l'envoi du mail est survenu";
    }
}


?>

<footer>
    <div class="footer-content">
        <div class="company">
            <h3>Contact</h3>
            <p>Adresse : Av. des Sports 20, 1401 Yverdon-les-Bains</p>
            <p>Téléphone : <a href="tel:+41245577600">024 557 76 00</a></p>
            <p>Email : <a href="mailto:contact@forumvaudois.ch">contact@forumvaudois.ch</a></p>
            <br>
            <br><br> <br> <br><br><br><br> <br> <br><br> <br>
            <p>&copy; 2024 Forum Vaudois - HEIG-VD ProgServ2</p>
        </div>
        <div class="contact-form">
            <form action="" method="POST">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" placeholder="Nom">
                <p><?php echo $errors['name'] ?></p>

                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Email">
                <p><?php echo $errors['email'] ?></p>

                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Message"></textarea>
                <p><?php echo $errors['message'] ?></p>

                <button type="submit">Envoyer</button>
            </form>
            <p><?php var_dump($name); ?></p>
            <p><?php var_dump($email); ?></p>
            <p><?php echo ($message); ?></p>
        </div>
    </div>
</footer>