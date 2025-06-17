<?php
// API pour mettre à jour le statut d'une demande d'acte
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$id = $input['id'] ?? null;
$type_acte = $input['type_acte'] ?? null;
$nouveau_statut = $input['statut'] ?? 'Traitée';

if (!$id || !$type_acte) {
    echo json_encode(['success' => false, 'error' => 'Paramètres manquants (id, type_acte)']);
    exit;
}

try {
    $table = '';
    switch ($type_acte) {
        case 'naissance':
            $table = 'demandes_naissance';
            break;
        case 'mariage':
            $table = 'demandes_mariage';
            break;
        case 'deces':
            $table = 'demandes_deces';
            break;
        default:
            echo json_encode(['success' => false, 'error' => 'Type d\'acte non reconnu']);
            exit;
    }
    
    // Vérifier si la colonne statut existe, sinon l'ajouter
    $check_column = "SHOW COLUMNS FROM $table LIKE 'statut'";
    $result_check = $connexion->query($check_column);
    
    if ($result_check->num_rows == 0) {
        // Ajouter la colonne statut si elle n'existe pas
        $add_column = "ALTER TABLE $table ADD COLUMN statut VARCHAR(50) DEFAULT 'En attente'";
        $connexion->query($add_column);
    }
    
    // Mettre à jour le statut
    $sql = "UPDATE $table SET statut = ? WHERE id = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("si", $nouveau_statut, $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true, 
                'message' => 'Statut mis à jour avec succès',
                'id' => $id,
                'type_acte' => $type_acte,
                'nouveau_statut' => $nouveau_statut
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Aucune demande trouvée avec cet ID']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour: ' . $connexion->error]);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur: ' . $e->getMessage()]);
}

$connexion->close();
?>
