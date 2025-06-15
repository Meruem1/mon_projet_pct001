<?php
// Informations de connexion à la base de données
$serveur = "localhost"; // Adresse du serveur MySQL
$utilisateur = "root"; // Nom d'utilisateur de la base de données
$mot_de_passe = ""; // Mot de passe de la base de données
$nom_base_de_donnees = "projet_pct001"; // Nom de la base de données

// Tentative de connexion à la base de données
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $nom_base_de_donnees);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion à la base de données : " . $connexion->connect_error);
}
?>