<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_acte = $_POST['type_acte'] ?? '';
    $motif = $_POST['motif'] ?? '';

    if ($type_acte === 'naissance') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $date_naissance = $_POST['date_naissance'] ?? '';
        $lieu_naissance = $_POST['lieu_naissance'] ?? '';

        $sql = "INSERT INTO demandes_naissance (nom, prenom, date_naissance, lieu_naissance, motif) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("sssss", $nom, $prenom, $date_naissance, $lieu_naissance, $motif);
        $stmt->execute();
        $stmt->close();

    } elseif ($type_acte === 'mariage') {
        $nom_epoux = $_POST['nom_epoux'] ?? '';
        $nom_epouse = $_POST['nom_epouse'] ?? '';
        $date_mariage = $_POST['date_mariage'] ?? '';
        $lieu_mariage = $_POST['lieu_mariage'] ?? '';

        $sql = "INSERT INTO demandes_mariage (nom_epoux, nom_epouse, date_mariage, lieu_mariage, motif) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("sssss", $nom_epoux, $nom_epouse, $date_mariage, $lieu_mariage, $motif);
        $stmt->execute();
        $stmt->close();

    } elseif ($type_acte === 'deces') {
        $nom_defunt = $_POST['nom_defunt'] ?? '';
        $date_deces = $_POST['date_deces'] ?? '';
        $lieu_deces = $_POST['lieu_deces'] ?? '';

        $sql = "INSERT INTO demandes_deces (nom_defunt, date_deces, lieu_deces, motif) VALUES (?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("ssss", $nom_defunt, $date_deces, $lieu_deces, $motif);
        $stmt->execute();
        $stmt->close();

    } else {
        echo "Type d'acte non reconnu.";
        exit();
    }

    // Redirection vers la page de paiement
    header('Location: ../paiement.html');
    exit();
} else {
    echo "Méthode non autorisée.";
}
?>