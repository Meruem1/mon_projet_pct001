<?php
// filepath: c:\wamp64\www\Projet-PCT001\api\admin.php

// Démarrer la session
session_start();

// Inclure le fichier de configuration et la bibliothèque FPDF
require_once 'config.php';
require_once '../lib/fpdf/fpdf.php';

// Fonction pour connecter un administrateur
function connecterAdmin($email, $mot_de_passe) {
    global $connexion;

    $sql = "SELECT * FROM administrateurs WHERE email = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultat = $stmt->get_result();

    if ($resultat->num_rows > 0) {
        $admin = $resultat->fetch_assoc();
        // Vérifier le mot de passe
        if (password_verify($mot_de_passe, $admin['mot_de_passe'])) {
            // Stocker les informations de session
            $_SESSION['admin_connecte'] = true;
            $_SESSION['admin_nom'] = $admin['nom'];
            $_SESSION['admin_prenom'] = $admin['prenom'];
            $_SESSION['admin_email'] = $admin['email'];
            return null; // Connexion réussie
        } else {
            return "Mot de passe incorrect.";
        }
    } else {
        return "Email non trouvé.";
    }
}

// Fonction pour inscrire un administrateur
function inscrireAdmin($nom, $prenom, $email, $mot_de_passe) {
    global $connexion;

    // Vérifier si l'email existe déjà
    $sql_verification = "SELECT * FROM administrateurs WHERE email = ?";
    $stmt_verification = $connexion->prepare($sql_verification);
    $stmt_verification->bind_param("s", $email);
    $stmt_verification->execute();
    $resultat = $stmt_verification->get_result();

    if ($resultat->num_rows > 0) {
        return "Cet email est déjà utilisé.";
    }

    // Vérifier le nombre d'administrateurs
    $sql_count = "SELECT COUNT(*) AS total FROM administrateurs";
    $result_count = $connexion->query($sql_count);
    $row_count = $result_count->fetch_assoc();

    if ($row_count['total'] >= 10) {
        return "Le nombre maximum d'administrateurs (10) a été atteint.";
    }

    // Hacher le mot de passe
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    // Insérer l'administrateur dans la base de données
    $sql = "INSERT INTO administrateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("ssss", $nom, $prenom, $email, $mot_de_passe_hache);

    if ($stmt->execute()) {
        return null; // Inscription réussie
    } else {
        return "Erreur lors de la création du compte.";
    }
}

// Vérifier les actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'connexion') {
        // Gestion de la connexion
        $email = $_POST['email'] ?? '';
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';
        $erreur = connecterAdmin($email, $mot_de_passe);
        if ($erreur) {
            header('Location: ../administrateur/connexion.html?erreur=' . urlencode($erreur));
            exit();
        } else {
            header('Location: ../administrateur/tableau_de_bord.php');
            exit();
        }
    } elseif ($action === 'inscription') {
        // Gestion de l'inscription
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $mot_de_passe = $_POST['mot_de_passe'];

        $erreur = inscrireAdmin($nom, $prenom, $email, $mot_de_passe);
        if ($erreur) {
            echo $erreur;
            exit();
        } else {
            header('Location: ../administrateur/connexion.html');
            exit();
        }
    } elseif ($action === 'generer_pdf') {
        // Gestion de la génération de PDF
        if (!isset($_SESSION['admin_connecte'])) {
            header('HTTP/1.0 403 Forbidden');
            echo "Accès interdit.";
            exit();
        }

        $id_demande = $_POST['id_demande'] ?? null;
        $type_acte = $_POST['type_acte'] ?? null;

        if (!$id_demande || !$type_acte) {
            echo "ID de la demande ou type d'acte manquant.";
            exit();
        }

        // Récupérer les données de la demande
        $sql = "SELECT * FROM demandes WHERE id = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("i", $id_demande);
        $stmt->execute();
        $resultat = $stmt->get_result();

        if ($resultat->num_rows === 0) {
            echo "Aucune demande trouvée avec cet ID.";
            exit();
        }

        $demande = $resultat->fetch_assoc();

        // Générer le PDF
        $chemin_signature = "../images/signature.png";
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(0, 10, "Acte de " . ucfirst($type_acte), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        foreach ($demande as $cle => $valeur) {
            $pdf->Cell(0, 10, ucfirst($cle) . ": " . $valeur, 0, 1);
        }

        if (file_exists($chemin_signature)) {
            $pdf->Ln(10);
            $pdf->Cell(0, 10, "Signature numérique :", 0, 1);
            $pdf->Image($chemin_signature, 10, $pdf->GetY(), 50);
        }

        $nom_fichier = "acte_" . $type_acte . "_" . $id_demande . ".pdf";
        $chemin_fichier = "../documents/" . $nom_fichier;
        $pdf->Output('F', $chemin_fichier);

        echo "PDF généré avec succès : <a href='$chemin_fichier'>Télécharger l'acte</a>";
        exit();
    }
} else {
    header('HTTP/1.0 405 Method Not Allowed');
    echo "Méthode non autorisée.";
}
?>