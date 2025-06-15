<?php
// filepath: c:\wamp64\www\Projet-PCT001\administrateur\tableau_de_bord.php

// Vérifier si l'administrateur est connecté
session_start();
if (!isset($_SESSION['admin_connecte']) || $_SESSION['admin_connecte'] !== true) {
    header('Location: connexion.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administrateur - DE LA MAIRIE DE BOTRO</title>
    <link rel="stylesheet" href="../ressources/css/style.css">
</head>
<body>
    <header>
        <h1>Tableau de Bord Administrateur</h1>
        <nav>
            <ul>
                <li><a href="tableau_de_bord.php">Tableau de bord</a></li>
                <li><a href="gestion_actes.html">Gestion des actes</a></li>
                <li><a href="generation_pdf.html">Générer PDF</a></li>
                <li><a href="gestion_utilisateurs.html">Gestion des utilisateurs</a></li>
                <li><a href="statistiques.html">Statistiques</a></li>
                <li><a href="configuration.html">Configuration</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Statistiques Rapides</h2>
            <ul>
                <li>Nombre total de demandes d'actes: <span id="total_demandes"></span></li>
                <li>Nouvelles demandes aujourd'hui: <span id="nouvelles_demandes"></span></li>
                <li>Demandes en attente de traitement: <span id="demandes_en_attente"></span></li>
                <li>Nombre total de citoyens inscrits: <span id="total_citoyens"></span></li>
            </ul>
        </section>
        <section>
            <h2>Dernières Demandes</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Type d'acte</th>
                        <th>Date de la demande</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="liste_dernieres_demandes">
                </tbody>
            </table>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 MAIRIE DE BOTRO</p>
    </footer>
    <script src="../ressources/js/script.js"></script>
    <script>
        // Récupérer dynamiquement les statistiques et les dernières demandes depuis le backend
        document.addEventListener('DOMContentLoaded', function() {
            // Appel API pour les statistiques
            fetch('/Projet-PCT001/api/statistiques.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total_demandes').textContent = data.total_demandes || '0';
                    document.getElementById('nouvelles_demandes').textContent = data.nouvelles_demandes || '0';
                    document.getElementById('demandes_en_attente').textContent = data.demandes_en_attente || '0';
                    document.getElementById('total_citoyens').textContent = data.total_citoyens || '0';
                })
                .catch(error => console.error('Erreur lors de la récupération des statistiques:', error));

            // Appel API pour les dernières demandes
            fetch('/Projet-PCT001/api/dernieres_demandes.php')
                .then(response => response.json())
                .then(demandes => {
                    const listeDemandes = document.getElementById('liste_dernieres_demandes');
                    demandes.forEach(demande => {
                        const row = listeDemandes.insertRow();
                        const typeCell = row.insertCell();
                        const dateCell = row.insertCell();
                        const statutCell = row.insertCell();
                        const actionCell = row.insertCell();

                        typeCell.textContent = demande.type_acte;
                        dateCell.textContent = demande.date_demande;
                        statutCell.textContent = demande.statut;
                        actionCell.innerHTML = `<a href="details_demande.php?id=${demande.id}">Voir</a>`;
                    });
                })
                .catch(error => console.error('Erreur lors de la récupération des dernières demandes:', error));
        });
    </script>
</body>
</html>