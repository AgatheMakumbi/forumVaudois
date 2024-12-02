<?php
session_start();
session_destroy(); // Détruire la session
header('Location: /ForumVaudois/'); // Redirige vers la page d'accueil 
exit;