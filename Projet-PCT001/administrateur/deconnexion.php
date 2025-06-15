<?php
// filepath: c:\wamp64\www\Projet-PCT001\administrateur\deconnexion.php

// Démarrer la session
session_start();

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header('Location: connexion.html');
exit();
?>