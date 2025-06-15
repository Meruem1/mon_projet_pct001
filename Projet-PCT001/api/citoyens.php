<?php
// filepath: c:\wamp64\www\Projet-PCT001\api\citoyens.php

// Démarrer la session
session_start();

// Inclure le fichier de configuration pour la base de données
require_once 'config.php'; // Assurez-vous que config.php est correctement configuré

// Vérifier la connexion à la base de données
if (!$connexion) {
    die("Erreur de connexion à la base de données.");
}

// Fonction de connexion citoyen
function connecterCitoyen($email, $mot_de_passe) {
    global $connexion;

    // Vérifier les identifiants dans la base de données
    $sql = "SELECT * FROM citoyens WHERE email = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultat = $stmt->get_result();

    if ($resultat->num_rows > 0) {
        $utilisateur = $resultat->fetch_assoc();
        // Vérifier le mot de passe (haché dans la base de données)
        if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            // Stocker les informations de session
            $_SESSION['citoyen_connecte'] = true;
            $_SESSION['citoyen_email'] = $email;
            $_SESSION['citoyen_nom'] = $utilisateur['nom'];
            $_SESSION['citoyen_prenom'] = $utilisateur['prenom'];

            // Rediriger vers le tableau de bord
            header('Location: ../citoyen/tableau_de_bord.html');
            exit();
        } else {
            return "Mot de passe incorrect.";
        }
    } else {
        return "Email non trouvé.";
    }
}

// Fonction d'inscription citoyen
function inscrireCitoyen($nom, $prenom, $email, $mot_de_passe) {
    global $connexion;

    // Vérifier si l'email existe déjà
    $sql_verification = "SELECT * FROM citoyens WHERE email = ?";
    $stmt_verification = $connexion->prepare($sql_verification);
    $stmt_verification->bind_param("s", $email);
    $stmt_verification->execute();
    $resultat = $stmt_verification->get_result();

    if ($resultat->num_rows > 0) {
        return "Cet email est déjà utilisé.";
    }

    // Insérer le nouveau citoyen
    $sql = "INSERT INTO citoyens (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT); // Hacher le mot de passe
    $stmt->bind_param("ssss", $nom, $prenom, $email, $mot_de_passe_hache);

    if ($stmt->execute()) {
        return null; // Inscription réussie
    } else {
        return "Erreur lors de l'inscription. Veuillez réessayer plus tard.";
    }
}

// Gestion de la requête
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'connexion') {
        $email = $_POST['email'] ?? '';
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';

        // Vérifier les champs obligatoires
        if (empty($email) || empty($mot_de_passe)) {
            header('Location: ../connexion_citoyen.html?erreur=' . urlencode("Tous les champs sont obligatoires."));
            exit();
        }

        $erreur = connecterCitoyen($email, $mot_de_passe);
        if ($erreur) {
            // Rediriger vers la page de connexion avec un message d'erreur
            header('Location: ../connexion_citoyen.html?erreur=' . urlencode($erreur));
            exit();
        }
    } elseif ($action === 'inscription') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';

        // Vérifier les champs obligatoires
        if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
            header('Location: ../inscription_citoyen.html?erreur=' . urlencode("Tous les champs sont obligatoires."));
            exit();
        }

        $erreur = inscrireCitoyen($nom, $prenom, $email, $mot_de_passe);
        if ($erreur) {
            // Rediriger vers la page d'inscription avec un message d'erreur
            header('Location: ../inscription_citoyen.html?erreur=' . urlencode($erreur));
            exit();
        } else {
            // Rediriger vers la page de connexion avec un message de succès
            header('Location: ../connexion_citoyen.html?inscription_reussie=1');
            exit();
        }
    }
} else {
    // Si la requête n'est pas POST
    header('HTTP/1.0 405 Method Not Allowed');
    echo "Méthode non autorisée.";
}
?>