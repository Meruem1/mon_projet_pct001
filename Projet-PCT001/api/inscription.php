<?php
// filepath: c:\wamp64\www\Projet-PCT001\api\inscription.php

// Inclure le fichier de configuration pour la base de données
require_once 'config.php';

// Vérifier la connexion à la base de données
if (!$connexion) {
    die("Erreur de connexion à la base de données.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $numero_telephone = $_POST['telephone'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'] ?? '';

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($numero_telephone)) {
        echo "Tous les champs sont obligatoires.";
        exit();
    }

    // Nettoyer et valider les données
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "L'adresse email n'est pas valide.";
        exit();
    }

    $nom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
    $prenom = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');
    $numero_telephone = htmlspecialchars($numero_telephone, ENT_QUOTES, 'UTF-8');

    // Vérifier que les mots de passe correspondent
    if ($mot_de_passe !== $confirmer_mot_de_passe) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Vérifier si l'email existe déjà
    $sql_verification = "SELECT * FROM citoyens WHERE email = ?";
    $stmt_verification = $connexion->prepare($sql_verification);
    $stmt_verification->bind_param("s", $email);
    $stmt_verification->execute();
    $resultat = $stmt_verification->get_result();

    if ($resultat->num_rows > 0) {
        echo "Cet email est déjà utilisé.";
        exit();
    }

    // Insérer le nouveau citoyen dans la base de données
    $sql = "INSERT INTO citoyens (nom, prenom, email, mot_de_passe, numero_telephone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT); // Hacher le mot de passe
    $stmt->bind_param("sssss", $nom, $prenom, $email, $mot_de_passe_hache, $numero_telephone);

    if ($stmt->execute()) {
        // Redirection vers la page de connexion après une inscription réussie
        header('Location: ../connexion_citoyen.html');
        exit();
    } else {
        echo "Une erreur est survenue lors de l'inscription. Veuillez réessayer plus tard.";
    }

    $stmt->close();
} else {
    echo "Méthode non autorisée.";
}
?>