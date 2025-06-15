<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reseau = $_POST['reseau'] ?? '';
    $numero = $_POST['numero_rapide'] ?? '';
    $montant = $_POST['montant_rapide'] ?? '';

    // Ici, tu peux enregistrer le paiement dans la base si besoin

    // Exemple d'envoi de SMS (pseudo-code, à remplacer par l'API de ton opérateur)
    $message = "Votre paiement de $montant FCFA via $reseau a bien été reçu. Merci !";

    // Exemple avec une API SMS (remplace par ton fournisseur réel)
    // send_sms($numero, $message);

    // Affichage d'une confirmation à l'utilisateur
    echo "<script>alert('Un message de confirmation a été envoyé sur votre téléphone.');window.location='../citoyen/paiement.html';</script>";
    exit();
}
?>