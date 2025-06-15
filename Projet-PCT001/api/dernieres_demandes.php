<?php
require_once '../config.php';

header('Content-Type: application/json');

// Récupérer les 5 dernières demandes
$result = $connexion->query("SELECT id, type_acte, date_demande, statut FROM demandes ORDER BY date_demande DESC LIMIT 5");
$demandes = [];
while ($row = $result->fetch_assoc()) {
    $demandes[] = $row;
}

echo json_encode($demandes);
?>