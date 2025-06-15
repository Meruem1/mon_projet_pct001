<?php
// filepath: c:\wamp64\www\Projet-PCT001\api\paiements.php

session_start();

// Inclure le fichier de configuration pour la base de données
require_once 'config.php'; // Assurez-vous que config.php est correctement configuré

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['citoyen_connecte'])) {
    $action = $_POST['action'] ?? '';

    if ($action === 'confirmer_paiement') {
        // Étape 1 : Afficher les détails du paiement pour confirmation
        $montant = $_POST['montant'] ?? '';
        $mode_paiement = $_POST['mode_paiement'] ?? '';
        $reference_demande = $_POST['reference_demande'] ?? '';
        $numero_telephone = $_POST['numero_telephone'] ?? '';

        echo "Vous êtes sur le point de payer <strong>$montant</strong> via <strong>$mode_paiement</strong> pour la demande <strong>$reference_demande</strong> avec le numéro <strong>$numero_telephone</strong>.";
        echo "<form method='POST' action='paiements.php'>
                <input type='hidden' name='action' value='effectuer_paiement'>
                <input type='hidden' name='montant' value='$montant'>
                <input type='hidden' name='mode_paiement' value='$mode_paiement'>
                <input type='hidden' name='reference_demande' value='$reference_demande'>
                <input type='hidden' name='numero_telephone' value='$numero_telephone'>
                <button type='submit'>Confirmer le paiement</button>
              </form>";
        exit();
    } elseif ($action === 'effectuer_paiement') {
        // Étape 2 : Effectuer le paiement après confirmation
        $montant = $_POST['montant'] ?? '';
        $mode_paiement = $_POST['mode_paiement'] ?? '';
        $reference_demande = $_POST['reference_demande'] ?? '';
        $numero_telephone = $_POST['numero_telephone'] ?? '';

        // Vérifier que le numéro de téléphone est fourni
        if (empty($numero_telephone)) {
            echo "Le numéro de téléphone est requis pour effectuer le paiement.";
            exit();
        }

        // Vérifier le mode de paiement et effectuer les actions correspondantes
        if ($mode_paiement === 'orange_money') {
            echo "Traitement du paiement via Orange Money pour la demande " . $reference_demande . " avec le numéro " . $numero_telephone . ".";
        } elseif ($mode_paiement === 'mtn_money') {
            echo "Traitement du paiement via MTN Money pour la demande " . $reference_demande . " avec le numéro " . $numero_telephone . ".";
        } elseif ($mode_paiement === 'moov_money') {
            echo "Traitement du paiement via Moov Money pour la demande " . $reference_demande . " avec le numéro " . $numero_telephone . ".";
        } elseif ($mode_paiement === 'wave') {
            echo "Traitement du paiement via Wave pour la demande " . $reference_demande . " avec le numéro " . $numero_telephone . ".";
        } else {
            echo "Mode de paiement non reconnu.";
            exit();
        }

        // Mettre à jour le statut de la demande dans la base de données
        global $connexion;
        $sql = "UPDATE demandes SET statut = 'payé', mode_paiement = ?, numero_telephone = ? WHERE reference = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("sss", $mode_paiement, $numero_telephone, $reference_demande);

        if ($stmt->execute()) {
            echo "Le statut de la demande a été mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour du statut : " . $connexion->error;
        }

        $stmt->close();
    } else {
        echo "Action de paiement non reconnue.";
    }
} else {
    header('HTTP/1.0 403 Forbidden');
    echo "Accès interdit.";
}
?>