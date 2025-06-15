<?php
require_once '../config.php';

header('Content-Type: application/json');

// Récupérer les statistiques
$total_demandes = $connexion->query("SELECT COUNT(*) AS total FROM demandes")->fetch_assoc()['total'];
$nouvelles_demandes = $connexion->query("SELECT COUNT(*) AS total FROM demandes WHERE DATE(date_demande) = CURDATE()")->fetch_assoc()['total'];
$demandes_en_attente = $connexion->query("SELECT COUNT(*) AS total FROM demandes WHERE statut = 'En attente'")->fetch_assoc()['total'];
$total_citoyens = $connexion->query("SELECT COUNT(*) AS total FROM citoyens")->fetch_assoc()['total'];

echo json_encode([
    'total_demandes' => $total_demandes,
    'nouvelles_demandes' => $nouvelles_demandes,
    'demandes_en_attente' => $demandes_en_attente,
    'total_citoyens' => $total_citoyens
]);
?>