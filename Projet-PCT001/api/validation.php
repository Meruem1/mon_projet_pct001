<?php
// filepath: c:\wamp64\www\Projet-PCT001\api\validation.php
session_start();
require_once 'config.php'; // ou 'database.php' selon ton projet

// Définir le baseUrl pour les redirections
$baseUrl = '/Projet-PCT001/';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $baseUrl . 'connexion_citoyen.html');
    exit;
}

// Vérifier que l'ID de la demande est présent
if (isset($_GET['demande_id'])) {
    $demande_id = intval($_GET['demande_id']);

    // Exemple : valider la demande (statut = "validée")
    $stmt = $pdo->prepare("UPDATE demandes SET statut = 'validée' WHERE id = ?");
    if ($stmt->execute([$demande_id])) {
        $_SESSION['message'] = "La demande a été validée avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la validation.";
    }
    header('Location: ' . $baseUrl . 'citoyen/mes_actes.html');
    exit;
} else {
    $_SESSION['message'] = "Aucune demande sélectionnée.";
    header('Location: ' . $baseUrl . 'citoyen/mes_actes.html');
    exit;
}
?>