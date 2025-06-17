<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_acte = $_POST['type_acte'] ?? '';
    $motif = $_POST['motif'] ?? '';
    $date_demande = date('Y-m-d H:i:s'); // Date actuelle
    $statut = 'En attente'; // Statut par défaut

    if ($type_acte === 'naissance') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenoms'] ?? ''; // Correction: prenoms au lieu de prenom
        $date_naissance = $_POST['date_naissance'] ?? '';
        $lieu_naissance = $_POST['lieu_naissance'] ?? '';
        $nom_pere = $_POST['nom_pere'] ?? '';
        $prenoms_pere = $_POST['prenoms_pere'] ?? '';
        $nom_mere = $_POST['nom_mere'] ?? '';
        $prenoms_mere = $_POST['prenoms_mere'] ?? '';
        $nombre_copies = $_POST['nombre_copies'] ?? 1;

        // Vérifier et ajouter les colonnes si nécessaires
        $check_columns = "SHOW COLUMNS FROM demandes_naissance";
        $result = $connexion->query($check_columns);
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        
        if (!in_array('date_demande', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN date_demande DATETIME DEFAULT CURRENT_TIMESTAMP");
        }
        if (!in_array('statut', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN statut VARCHAR(50) DEFAULT 'En attente'");
        }
        if (!in_array('prenoms', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN prenoms VARCHAR(255)");
        }
        if (!in_array('nom_pere', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN nom_pere VARCHAR(255)");
        }
        if (!in_array('prenoms_pere', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN prenoms_pere VARCHAR(255)");
        }
        if (!in_array('nom_mere', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN nom_mere VARCHAR(255)");
        }
        if (!in_array('prenoms_mere', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN prenoms_mere VARCHAR(255)");
        }
        if (!in_array('nombre_copies', $columns)) {
            $connexion->query("ALTER TABLE demandes_naissance ADD COLUMN nombre_copies INT DEFAULT 1");
        }

        $sql = "INSERT INTO demandes_naissance (nom, prenoms, date_naissance, lieu_naissance, nom_pere, prenoms_pere, nom_mere, prenoms_mere, nombre_copies, motif, date_demande, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("ssssssssssss", $nom, $prenom, $date_naissance, $lieu_naissance, $nom_pere, $prenoms_pere, $nom_mere, $prenoms_mere, $nombre_copies, $motif, $date_demande, $statut);
        $stmt->execute();
        $stmt->close();

    } elseif ($type_acte === 'mariage') {
        $nom_epoux = $_POST['nom_epoux'] ?? '';
        $prenom_epoux = $_POST['prenom_epoux'] ?? '';
        $nom_epouse = $_POST['nom_epouse'] ?? '';
        $prenom_epouse = $_POST['prenom_epouse'] ?? '';
        $date_mariage = $_POST['date_mariage'] ?? '';
        $lieu_mariage = $_POST['lieu_mariage'] ?? '';

        // Vérifier et ajouter les colonnes si nécessaires
        $check_columns = "SHOW COLUMNS FROM demandes_mariage";
        $result = $connexion->query($check_columns);
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        
        if (!in_array('date_demande', $columns)) {
            $connexion->query("ALTER TABLE demandes_mariage ADD COLUMN date_demande DATETIME DEFAULT CURRENT_TIMESTAMP");
        }
        if (!in_array('statut', $columns)) {
            $connexion->query("ALTER TABLE demandes_mariage ADD COLUMN statut VARCHAR(50) DEFAULT 'En attente'");
        }
        if (!in_array('prenom_epoux', $columns)) {
            $connexion->query("ALTER TABLE demandes_mariage ADD COLUMN prenom_epoux VARCHAR(255)");
        }
        if (!in_array('prenom_epouse', $columns)) {
            $connexion->query("ALTER TABLE demandes_mariage ADD COLUMN prenom_epouse VARCHAR(255)");
        }

        $sql = "INSERT INTO demandes_mariage (nom_epoux, prenom_epoux, nom_epouse, prenom_epouse, date_mariage, lieu_mariage, motif, date_demande, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("sssssssss", $nom_epoux, $prenom_epoux, $nom_epouse, $prenom_epouse, $date_mariage, $lieu_mariage, $motif, $date_demande, $statut);
        $stmt->execute();
        $stmt->close();

    } elseif ($type_acte === 'deces') {
        $nom_defunt = $_POST['nom_defunt'] ?? '';
        $prenoms_defunt = $_POST['prenoms_defunt'] ?? '';
        $date_deces = $_POST['date_deces'] ?? '';
        $lieu_deces = $_POST['lieu_deces'] ?? '';
        $date_naissance_defunt = $_POST['date_naissance_defunt'] ?? '';
        $lieu_naissance_defunt = $_POST['lieu_naissance_defunt'] ?? '';
        $nom_declarant = $_POST['nom_declarant'] ?? '';
        $prenoms_declarant = $_POST['prenoms_declarant'] ?? '';
        $lien_parente = $_POST['lien_parente'] ?? '';
        $nombre_copies = $_POST['nombre_copies'] ?? 1;

        // Vérifier et ajouter les colonnes si nécessaires
        $check_columns = "SHOW COLUMNS FROM demandes_deces";
        $result = $connexion->query($check_columns);
        $columns = [];
        while ($row = $result->fetch_assoc()) {
            $columns[] = $row['Field'];
        }
        
        if (!in_array('date_demande', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN date_demande DATETIME DEFAULT CURRENT_TIMESTAMP");
        }
        if (!in_array('statut', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN statut VARCHAR(50) DEFAULT 'En attente'");
        }
        if (!in_array('prenoms_defunt', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN prenoms_defunt VARCHAR(255)");
        }
        if (!in_array('date_naissance_defunt', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN date_naissance_defunt DATE");
        }
        if (!in_array('lieu_naissance_defunt', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN lieu_naissance_defunt VARCHAR(255)");
        }
        if (!in_array('nom_declarant', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN nom_declarant VARCHAR(255)");
        }
        if (!in_array('prenoms_declarant', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN prenoms_declarant VARCHAR(255)");
        }
        if (!in_array('lien_parente', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN lien_parente VARCHAR(255)");
        }
        if (!in_array('nombre_copies', $columns)) {
            $connexion->query("ALTER TABLE demandes_deces ADD COLUMN nombre_copies INT DEFAULT 1");
        }

        $sql = "INSERT INTO demandes_deces (nom_defunt, prenoms_defunt, date_deces, lieu_deces, date_naissance_defunt, lieu_naissance_defunt, nom_declarant, prenoms_declarant, lien_parente, nombre_copies, motif, date_demande, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("sssssssssssss", $nom_defunt, $prenoms_defunt, $date_deces, $lieu_deces, $date_naissance_defunt, $lieu_naissance_defunt, $nom_declarant, $prenoms_declarant, $lien_parente, $nombre_copies, $motif, $date_demande, $statut);
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