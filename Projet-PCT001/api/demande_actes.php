<?php
// filepath: c:\wamp64\www\Projet-PCT001\api\demande_actes.php

session_start();

// Inclure le fichier de configuration pour la base de données
require_once 'config.php';

// Fonction pour enregistrer une demande d'acte de naissance
function enregistrerDemandeNaissance($donnees) {
    global $connexion; // Utiliser la connexion à la base de données définie dans config.php

    // Exemple d'insertion dans la base de données
    $sql = "INSERT INTO demandes_naissance (nom, prenom, date_naissance, lieu_naissance) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param(
        "ssss",
        $donnees['nom'],
        $donnees['prenom'],
        $donnees['date_naissance'],
        $donnees['lieu_naissance'],
        $donnees['nom du père'],
        $donnees['prenom du père'],
        $donnees['nom de la mère'],
        $donnees['prenom de la mère'],
        $donnees['nombre de copies souhaitées']
    );

    if ($stmt->execute()) {
        echo "Demande d'acte de naissance enregistrée avec succès.";
    } else {
        echo "Erreur lors de l'enregistrement : " . $connexion->error;
    }

    $stmt->close();
    exit();
}

// Fonction pour enregistrer une demande d'acte de mariage
function enregistrerDemandeMariage($donnees) {
    global $connexion;

    $sql = "INSERT INTO demandes_mariage (nom_epoux, nom_epouse, date_mariage, lieu_mariage) VALUES (?, ?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param(
        "ssss",
        $donnees['nom_epoux'],
        $donnees['nom_epouse'],
        $donnees['date_mariage'],
        $donnees['lieu_mariage']
    );

    if ($stmt->execute()) {
        echo "Demande d'acte de mariage enregistrée avec succès.";
    } else {
        echo "Erreur lors de l'enregistrement : " . $connexion->error;
    }

    $stmt->close();
    exit();
}

// Fonction pour enregistrer une demande d'acte de décès
function enregistrerDemandeDeces($donnees) {
    global $connexion;

    $sql = "INSERT INTO demandes_deces (nom_defunt, date_deces, lieu_deces) VALUES (?, ?, ?)";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param(
        "sss",
        $donnees['nom_defunt'],
        $donnees['date_deces'],
        $donnees['lieu_deces']
    );

    if ($stmt->execute()) {
        echo "Demande d'acte de décès enregistrée avec succès.";
    } else {
        echo "Erreur lors de l'enregistrement : " . $connexion->error;
    }

    $stmt->close();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['citoyen_connecte'])) {
    $action = $_POST['action'] ?? '';

    if ($action === 'demande_naissance') {
        $donnees = $_POST;
        enregistrerDemandeNaissance($donnees);
    } elseif ($action === 'demande_mariage') {
        $donnees = $_POST;
        enregistrerDemandeMariage($donnees);
    } elseif ($action === 'demande_deces') {
        $donnees = $_POST;
        enregistrerDemandeDeces($donnees);
    } else {
        echo "Action non reconnue.";
    }
} else {
    echo "Accès non autorisé.";
}
?>