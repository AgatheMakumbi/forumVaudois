<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lang/lang_func.php';



use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// ============================
// TRAITEMENT DU FORMULAIRE
// ============================

// Initialisation des variables
$validForm = true;
$errors = ['name' => "", 'email' => "", 'message' => ""];
$name = "";
$email = "";
$message = "";
$status = "";

// Charger les messages de traduction
try {
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr'; // Par défaut, 'fr'
    $messages = loadLanguage($lang);
} catch (Exception $e) {
    $messages = loadLanguage('fr'); // Fallback si une erreur survient
    error_log($e->getMessage());
}

// Récupération des entrées utilisateur et validation
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

    // Envoi du mail
    if ($validForm == true) {
        $transport = Transport::fromDsn('smtp://localhost:1025');
        $mailer = new Mailer($transport);
        $emailToSend = (new Email())
            ->from($email)
            ->to('contact@forumvaudois.ch')
            ->subject('Formulaire de contact')
            ->text($message)
            ->html("<p>De : {$name}</p> <p>{$message}</p>");

        try {
            $mailer->send($emailToSend);
            $status = "Un mail a été envoyé ! <a href='http://localhost:8025'>voir le mail</a>";
        } catch (Exception $e) {
            $status = "Un problème lors de l'envoi du mail est survenu";
            error_log($e->getMessage());
        }
    }
}
?>

<footer>
    <div class="footer-content">
        <div class="company">
            <!-- Sélecteur de langue placé au-dessus du titre -->
            <div class="language-selector">
                <form method="GET">
                    <label for="language">Changer la langue :</label>
                    <select name="lang" id="language" onchange="this.form.submit()">
                        <option value="fr" <?php if ($lang == 'fr') echo 'selected'; ?>>Français</option>
                        <option value="en" <?php if ($lang == 'en') echo 'selected'; ?>>English</option>
                        <option value="de" <?php if ($lang == 'de') echo 'selected'; ?>>Deutsch</option>
                        <option value="it" <?php if ($lang == 'it') echo 'selected'; ?>>Italiano</option>
                    </select>
                </form>
            </div>
<br><br><br><br>
            <h3><?php echo $messages['footer_title']; ?></h3>
            <p><?php echo $messages['footer_address']; ?> : Av. des Sports 20, 1401 Yverdon-les-Bains</p>
            <p><?php echo $messages['footer_phone']; ?> : <a href="tel:+41245577600">024 557 76 00</a></p>
            <p><?php echo $messages['footer_email']; ?> : <a href="mailto:contact@forumvaudois.ch">contact@forumvaudois.ch</a></p>
            <p>&copy; 2024 Forum Vaudois - HEIG-VD ProgServ2</p>
        </div>

        <div class="contact-form" id="contact-form">
            <form action="#contact-form" method="POST">
                <label for="name"><?php echo $messages['footer_form_name']; ?></label>
                <input type="text" id="name" name="name" placeholder="<?php echo $messages['footer_form_name']; ?>">

                <label for="email"><?php echo $messages['footer_form_email']; ?></label>
                <input type="text" id="email" name="email" placeholder="<?php echo $messages['footer_form_email']; ?>">

                <label for="message"><?php echo $messages['footer_form_message']; ?></label>
                <textarea id="message" name="message" placeholder="<?php echo $messages['footer_form_message']; ?>"></textarea>

                <button type="submit"><?php echo $messages['footer_form_button']; ?></button>
            </form>
        </div>
    </div>
</footer>
