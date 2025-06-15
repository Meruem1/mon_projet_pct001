<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration pour la base de données
require_once 'config.php';

// Vérifier si la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier que tous les champs sont remplis
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe)) {
        echo "Tous les champs sont obligatoires.";
        exit();
    }

    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "L'adresse email n'est pas valide.";
        exit();
    }

    // Vérifier si l'email existe déjà
    $sql_verification = "SELECT * FROM administrateurs WHERE email = ?";
    $stmt_verification = $connexion->prepare($sql_verification);
    $stmt_verification->bind_param("s", $email);
    $stmt_verification->execute();
    $resultat = $stmt_verification->get_result();

    if ($resultat->num_rows > 0) {
        echo "Cet email est déjà utilisé.";
        exit();
    }

    // Hacher le mot de passe
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    // Insérer l'administrateur dans la base de données
    $sql = "INSERT INTO administrateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("ssss", $nom, $prenom, $email, $mot_de_passe_hache);

    if ($stmt->execute()) {
        echo "Compte administrateur créé avec succès.";
        header('Location: ../admin/connexion_admin.html');
        exit();
    } else {
        echo "Erreur lors de la création du compte.";
    }
}
?>