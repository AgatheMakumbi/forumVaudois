<?php
session_start();
session_destroy(); // Détruit la session
header('Location: /ForumVaudois/'); // Redirige vers la page d'accueil ou de connexion
exit;