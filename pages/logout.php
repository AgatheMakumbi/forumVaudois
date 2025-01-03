<?php
/**
 * Script de déconnexion utilisateur.
 * Ce script détruit la session en cours et redirige l'utilisateur vers la page d'accueil.
 */

// Démarre la session si elle n'est pas déjà active
session_start();

/**
 * Détruit toutes les données associées à la session active.
 * Cela inclut la suppression des variables de session et l'identifiant de session.
 */
session_destroy(); // Détruire la session utilisateur

/**
 * Redirige l'utilisateur vers la page d'accueil du site.
 * La fonction `header()` envoie un en-tête HTTP au navigateur pour effectuer une redirection.
 */
header('Location: /ForumVaudois/'); // Redirige vers la page d'accueil

// Terminer l'exécution du script pour éviter toute exécution supplémentaire après la redirection
exit;
