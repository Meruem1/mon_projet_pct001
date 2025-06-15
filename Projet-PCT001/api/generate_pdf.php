<?php
// filepath: c:\wamp64\www\Projet-PCT001\api\generate_pdf.php

require_once '../lib/fpdf/fpdf.php'; // Inclure la bibliothèque FPDF

// Fonction pour générer un PDF pour un acte demandé
function genererPDFActe($type_acte, $donnees, $signature_path) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Titre du document
    $pdf->Cell(0, 10, "Acte de " . ucfirst($type_acte), 0, 1, 'C');
    $pdf->Ln(10);

    // Contenu de l'acte
    $pdf->SetFont('Arial', '', 12);
    foreach ($donnees as $cle => $valeur) {
        $pdf->Cell(0, 10, ucfirst($cle) . ": " . $valeur, 0, 1);
    }

    // Ajouter une signature numérique
    if (file_exists($signature_path)) {
        $pdf->Ln(10);
        $pdf->Cell(0, 10, "Signature numérique :", 0, 1);
        $pdf->Image($signature_path, 10, $pdf->GetY(), 50); // Ajouter l'image de la signature
    } else {
        $pdf->Ln(10);
        $pdf->Cell(0, 10, "Signature numérique non disponible.", 0, 1);
    }

    // Générer le fichier PDF
    $nom_fichier = "acte_" . $type_acte . "_" . time() . ".pdf";
    $chemin_fichier = "../documents/" . $nom_fichier;
    $pdf->Output('F', $chemin_fichier); // Sauvegarder le fichier PDF

    return $chemin_fichier; // Retourner le chemin du fichier généré
}

// Exemple d'utilisation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_acte = $_POST['type_acte'] ?? 'naissance'; // Exemple : naissance, mariage, deces
    $donnees = $_POST['donnees'] ?? []; // Données de l'acte
    $signature_path = "../images/signature.png"; // Chemin de la signature numérique

    $chemin_pdf = genererPDFActe($type_acte, $donnees, $signature_path);

    if ($chemin_pdf) {
        echo "PDF généré avec succès : <a href='$chemin_pdf'>Télécharger l'acte</a>";
    } else {
        echo "Erreur lors de la génération du PDF.";
    }
}
?>