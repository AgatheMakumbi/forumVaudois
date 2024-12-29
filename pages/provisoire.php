
<?php
require_once '../vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Post;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$title = "Bel-Air Coffee";
$text = "Bonjour à tous, je souhaite partager avec vous ma récente découverte du Bel-Air Coffee, un charmant coffee shop niché dans une cour intérieure au 6, rue des Terreaux, entre Chauderon et Bel-Air. Dès l’entrée, l’ambiance nordique épurée et l’accueil chaleureux d’André derrière sa magnifique machine Eagle One mettent tout de suite à l’aise. Le café de spécialité, principalement torréfié en Scandinavie, est un véritable délice pour les amateurs.

J’ai particulièrement apprécié leur cappuccino, accompagné d’un cardamom bun végan, moelleux et parfumé, cuit sur place. Le Bel-Air Coffee propose également des alternatives de lait pour les boissons comme le chaï ou le matcha, ce qui est parfait pour tous les goûts.

Le cadre est idéal pour une pause détente, avec un canapé cosy et une sélection d’accessoires autour du café ainsi que des objets et livres pour se mettre en mode « hygge ».

Pour ceux qui souhaitent prolonger l’expérience à la maison, il est même possible d’acheter des cafés de spécialité en grains.

Le Bel-Air Coffee est ouvert tous les jours, avec des horaires adaptés en semaine et le week-end.

Et vous, avez-vous déjà visité ce lieu ? Quelles sont vos adresses préférées pour un bon café à Lausanne ?";
$budget = 30;
$author = 10;
$city = 1;
$category = 1;
$created_at = new DateTime('now');
$last_update = new Datetime('now');

$post = new Post($title, $text, $budget, $author, $city, $category, $created_at, $last_update);

$dbManager = new DbManagerCRUD();
if ($dbManager->createPost($post)) {
    echo "Post créé avec succès !";
} else {
    echo "Échec de la création du post.";
}
