<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration pour la base de données
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['citoyen_connecte']) || $_SESSION['citoyen_connecte'] !== true) {
    header('Location: ../connexion_citoyen.html');
    exit();
}

// Vérifier si la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'])) {
        // Mise à jour des informations personnelles
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = htmlspecialchars($_POST['email']);
        $telephone = htmlspecialchars($_POST['telephone']);

        // Mettre à jour la base de données
        $sql = "UPDATE citoyens SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE email = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("sssss", $nom, $prenom, $email, $telephone, $_SESSION['citoyen_email']);

        if ($stmt->execute()) {
            // Mettre à jour les données dans la session
            $_SESSION['citoyen_nom'] = $nom;
            $_SESSION['citoyen_prenom'] = $prenom;
            $_SESSION['citoyen_email'] = $email;
            $_SESSION['citoyen_telephone'] = $telephone;

            header('Location: ../citoyen/profil.php?success=1');
            exit();
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'modifier_mot_de_passe') {
        // Modification du mot de passe
        $mot_de_passe_actuel = $_POST['mot_de_passe_actuel'];
        $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
        $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

        if ($nouveau_mot_de_passe !== $confirmer_mot_de_passe) {
            header('Location: ../citoyen/profil.php?error=Les mots de passe ne correspondent pas.');
            exit();
        }

        // Vérifier le mot de passe actuel
        $sql = "SELECT mot_de_passe FROM citoyens WHERE email = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("s", $_SESSION['citoyen_email']);
        $stmt->execute();
        $resultat = $stmt->get_result();
        $utilisateur = $resultat->fetch_assoc();

        if (password_verify($mot_de_passe_actuel, $utilisateur['mot_de_passe'])) {
            // Mettre à jour le mot de passe
            $nouveau_mot_de_passe_hache = password_hash($nouveau_mot_de_passe, PASSWORD_BCRYPT);
            $sql = "UPDATE citoyens SET mot_de_passe = ? WHERE email = ?";
            $stmt = $connexion->prepare($sql);
            $stmt->bind_param("ss", $nouveau_mot_de_passe_hache, $_SESSION['citoyen_email']);
            $stmt->execute();

            header('Location: ../citoyen/profil.php?success=Mot de passe mis à jour.');
            exit();
        } else {
            header('Location: ../citoyen/profil.php?error=Mot de passe actuel incorrect.');
            exit();
        }
    }
}
?>