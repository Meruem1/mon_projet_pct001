<?php
// API pour récupérer toutes les demandes d'actes pour l'interface administrateur
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'config.php';

try {
    $demandes = [];
    
    // Récupérer les demandes de naissance
    $sql_naissance = "SELECT 
        id, 
        nom, 
        prenoms as prenom, 
        date_naissance, 
        lieu_naissance, 
        motif,
        'naissance' as type_acte,
        COALESCE(date_demande, NOW()) as date_demande,
        COALESCE(statut, 'En attente') as statut
    FROM demandes_naissance 
    ORDER BY id DESC";
    
    $result_naissance = $connexion->query($sql_naissance);
    if ($result_naissance) {
        while ($row = $result_naissance->fetch_assoc()) {
            $row['details'] = "Nom: {$row['nom']} {$row['prenom']}, Né(e) le: {$row['date_naissance']} à {$row['lieu_naissance']}";
            $demandes[] = $row;
        }
    }
    
    // Récupérer les demandes de mariage
    $sql_mariage = "SELECT 
        id,
        nom_epoux,
        nom_epouse,
        date_mariage,
        lieu_mariage,
        motif,
        'mariage' as type_acte,
        COALESCE(date_demande, NOW()) as date_demande,
        COALESCE(statut, 'En attente') as statut
    FROM demandes_mariage 
    ORDER BY id DESC";
    
    $result_mariage = $connexion->query($sql_mariage);
    if ($result_mariage) {
        while ($row = $result_mariage->fetch_assoc()) {
            $row['details'] = "Époux: {$row['nom_epoux']}, Épouse: {$row['nom_epouse']}, Marié(e)s le: {$row['date_mariage']} à {$row['lieu_mariage']}";
            $demandes[] = $row;
        }
    }
    
    // Récupérer les demandes de décès
    $sql_deces = "SELECT 
        id,
        nom_defunt,
        date_deces,
        lieu_deces,
        motif,
        'deces' as type_acte,
        COALESCE(date_demande, NOW()) as date_demande,
        COALESCE(statut, 'En attente') as statut
    FROM demandes_deces 
    ORDER BY id DESC";
    
    $result_deces = $connexion->query($sql_deces);
    if ($result_deces) {
        while ($row = $result_deces->fetch_assoc()) {
            $row['details'] = "Défunt(e): {$row['nom_defunt']}, Décédé(e) le: {$row['date_deces']} à {$row['lieu_deces']}";
            $demandes[] = $row;
        }
    }
    
    // Trier toutes les demandes par date (plus récentes en premier)
    usort($demandes, function($a, $b) {
        return strtotime($b['date_demande']) - strtotime($a['date_demande']);
    });
    
    // Retourner les données au format JSON
    echo json_encode([
        'success' => true,
        'demandes' => $demandes,
        'total' => count($demandes)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la récupération des demandes: ' . $e->getMessage()
    ]);
}

$connexion->close();
?>
