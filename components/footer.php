<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// ============================
// TRAITEMENT DU FORMULAIRE
// ============================

//Initialisation des variables
$validForm = true;
$errors = ['name' => "", 'email'  => "", 'message' => ""];
$name = "";
$email = "";
$message = "";

//Récupération des entrées utilisateur et validation 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strlen(filter_input(INPUT_POST, 'name', FILTER_CALLBACK, ['options' => 'trim'])) > 0) {
        $name = filter_input(INPUT_POST, 'name', FILTER_CALLBACK, ['options' => 'trim']);
    } else {
        $errors['name'] = "Veuillez saisir votre nom.";
        $validForm = false;
    }
    if (filter_var(filter_input(INPUT_POST, 'email', FILTER_CALLBACK, ['options' => 'trim']), FILTER_VALIDATE_EMAIL)) {
        $email = filter_input(INPUT_POST, 'email', FILTER_CALLBACK, ['options' => 'trim']);
    } else {
        $errors['email'] = "Veuillez saisir une adresse email valide.";
        $validForm = false;
    }
    if (strlen(filter_input(INPUT_POST, 'message', FILTER_CALLBACK, ['options' => 'trim'])) > 0) {
        $message = filter_input(INPUT_POST, 'message', FILTER_CALLBACK, ['options' => 'trim']);
    } else {
        $errors['message'] = "Veuillez saisir un message.";
        $validForm = false;
    }

    //Envoi du mail 
    if ($validForm == true) {
        $transport = Transport::fromDsn('smtp://localhost:1025');
        $mailer = new Mailer($transport);
        $emailToSend = (new Email())
            ->from($email)
            ->to('contact@forumvaudois.ch')
            ->subject('Formulaire de contact')
            ->text($message)
            ->html("<p>De : {$name}</p> <p>{$message}</p>");

        $result = $mailer->send($emailToSend);
        if ($result == null) {
            $status = "Un mail a été envoyé ! <a href='http://localhost:8025'>voir le mail</a>";
        } else {
            $status = "Un problème lors de l'envoi du mail est survenu";
        }
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
        <div class="contact-form" id="contact-form">
            <form action="#contact-form" method="POST">
                <label for="name">
                    Nom
                    <p style="color:red; font-weight:normal">
                        <?php echo $errors['name'] ?>
                    </p>
                </label>
                <input type="text" id="name" name="name" placeholder="Nom">

                <label for="email">
                    Email
                    <p style="color:red; font-weight:normal">
                        <?php echo $errors['email'] ?>
                    </p>
                </label>
                <input type="text" id="email" name="email" placeholder="Email">

                <label for="message">
                    Message
                    <p style="color:red; font-weight:normal">
                        <?php echo $errors['message'] ?>
                    </p>
                </label>
                <textarea id="message" name="message" placeholder="Message"></textarea>

                <button type="submit">Envoyer</button>

                <p style="color:red"><?php echo $status ?></p>
            </form>
        </div>
    </div>
</footer>