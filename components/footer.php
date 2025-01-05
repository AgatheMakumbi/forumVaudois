<?php

/**
 * Script du footer
 * Ce script est inclu dans plusieurs pages du site.
 */

// Inclusion des dépendances nécessaires
require_once __DIR__ . '/../vendor/autoload.php'; // Charge les dépendances via Composer
require_once __DIR__ . '/../lang/lang_func.php'; // Charge les fonctions de gestion des langues

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Vérifie si une session est déjà active avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas encore active
}

try {
    /**
     * @var string $lang Langue courante de l'utilisateur (récupérée depuis GET ou la session).
     */
    $lang = isset($_GET['lang']) ? $_GET['lang'] : (isset($_SESSION['LANG']) ? $_SESSION['LANG'] : 'fr');
    /**
     * @var array $messages Tableau contenant les messages traduits pour l'interface utilisateur.
     */
    $messages = loadLanguage($lang); // Charge les traductions
    // Stocke la langue actuelle dans la session
    $_SESSION['LANG'] = $lang;
} catch (Exception $e) {
    // En cas d'erreur, charge les messages en français par défaut
    $messages = loadLanguage('fr'); // Fallback en cas d'erreur
    error_log($e->getMessage()); // Enregistre l'erreur dans le journal
}

// ============================
// TRAITEMENT DU FORMULAIRE
// ============================

/**
 * Initialisation des variables pour le traitement du formulaire
 * 
 * @var bool $validForm Indique si le formulaire est valide
 * @var array $errors Contient les messages d'erreurs par champ
 * @var string $name Nom saisi par l'utilisateur
 * @var string $email Adresse email saisie par l'utilisateur
 * @var string $message Message saisi par l'utilisateur
 * @var string $status Message de confirmation ou d'erreur pour l'envoi d'email
 */
$validForm = true;
$errors = ['name' => "", 'email' => "", 'message' => ""];
$name = "";
$email = "";
$message = "";
$status = "";

/**
 * Traitement des données du formulaire si la méthode est POST
 * 
 * @return void
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form-identifier'] === 'contact-form') {
    /**
     * Validation du champ "name"
     * 
     * @return void
     */
    if (strlen(filter_input(INPUT_POST, 'name', FILTER_CALLBACK, ['options' => 'trim'])) > 0) {
        $name = filter_input(INPUT_POST, 'name', FILTER_CALLBACK, ['options' => 'trim']);
    } else {
        $errors['name'] = "Veuillez saisir votre nom.";
        $validForm = false;
    }

    /**
     * Validation du champ "email"
     * 
     * @return void
     */
    if (filter_var(filter_input(INPUT_POST, 'email', FILTER_CALLBACK, ['options' => 'trim']), FILTER_VALIDATE_EMAIL)) {
        $email = filter_input(INPUT_POST, 'email', FILTER_CALLBACK, ['options' => 'trim']);
    } else {
        $errors['email'] = "Veuillez saisir une adresse email valide.";
        $validForm = false;
    }

    /**
     * Validation du champ "message"
     * 
     * @return void
     */
    if (strlen(filter_input(INPUT_POST, 'message', FILTER_CALLBACK, ['options' => 'trim'])) > 0) {
        $message = filter_input(INPUT_POST, 'message', FILTER_CALLBACK, ['options' => 'trim']);
    } else {
        $errors['message'] = "Veuillez saisir un message.";
        $validForm = false;
    }

    /**
     * Envoi de l'email si le formulaire est valide
     * @return void
     * @throws Exception Si l'envoi du mail échoue
     */
    if ($validForm) {
        $transport = Transport::fromDsn('smtp://localhost:1025'); // Configuration SMTP
        $mailer = new Mailer($transport);
        $emailToSend = (new Email())
            ->from($email) // Adresse email de l'expéditeur
            ->to('contact@forumvaudois.ch') // Adresse email de destination
            ->subject('Formulaire de contact') // Sujet de l'email
            ->text($message) // Contenu texte brut
            ->html("<p>De : {$name}</p> <p>{$message}</p>"); // Contenu HTML

        try {
            $mailer->send($emailToSend); // Envoi de l'email
            $status = "Un mail a été envoyé ! <a href='http://localhost:8025'>voir le mail</a>";
        } catch (Exception $e) {
            $status = "Un problème lors de l'envoi du mail est survenu";
            error_log($e->getMessage()); // Log de l'erreur
        }
    }
}
?>

<footer>
    <div class="footer-content">
        <div class="company">
            <!-- Sélecteur de langue -->
            <div class="footer-language-selector">
                <form method="GET">
                    <label for="language"><?php echo $messages['change_language']; ?></label>
                    <select name="lang" id="language" onchange="this.form.submit()">
                        <option value="fr" <?php echo $lang == 'fr' ? 'selected' : ''; ?>>Français</option>
                        <option value="en" <?php echo $lang == 'en' ? 'selected' : ''; ?>>English</option>
                        <option value="de" <?php echo $lang == 'de' ? 'selected' : ''; ?>>Deutsch</option>
                        <option value="it" <?php echo $lang == 'it' ? 'selected' : ''; ?>>Italiano</option>
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
                <!--Champ caché qui sert à vérifier le formulaire-->
                <input type="hidden" name="form-identifier" value="contact-form">

                <label for="name"><?php echo $messages['footer_form_name']; ?></label>
                <p style="color: red"><?php echo $errors['name']; ?></p>
                <input type="text" id="name" name="name" placeholder="<?php echo $messages['footer_form_name']; ?>" required>

                <label for="email"><?php echo $messages['footer_form_email']; ?></label>
                <p style="color: red"><?php echo $errors['email']; ?></p>
                <input type="email" id="email" name="email" placeholder="<?php echo $messages['footer_form_email']; ?>" required>

                <label for="message"><?php echo $messages['footer_form_message']; ?></label>
                <p style="color: red"><?php echo $errors['message']; ?></p>
                <textarea id="message" name="message" placeholder="<?php echo $messages['footer_form_message']; ?>" required></textarea>

                <button type="submit"><?php echo $messages['footer_form_button']; ?></button>
            </form>
        </div>
    </div>
</footer>