<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion_citoyen.html');
    exit;
}
$nom = $_SESSION['user_nom'] ?? '';
$prenom = $_SESSION['user_prenom'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord - MAIRIE DE BOTRO</title>
    <link rel="stylesheet" href="../ressources/css/style.css">
</head>
<body>
    <header>
        <h1>Tableau de Bord</h1>
        <nav>
            <ul>
                <li><a href="tableau_de_bord.php">Accueil</a></li>
                <li><a href="mes_actes.html">Mes Actes</a></li>
                <li><a href="demande_acte.html">Faire une Demande</a></li>
                <li><a href="profil.php">Mon Profil</a></li>
                <li><a href="../deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Bienvenue, <?php echo htmlspecialchars($prenom . ' ' . $nom); ?></h2>
            <p>Voici votre espace personnel. Toutes vos informations et actions sont ici.</p>
        </section>
        <!-- Tu peux ajouter ici des sections personnalisées pour chaque citoyen -->
    </main>
</body>
</html>