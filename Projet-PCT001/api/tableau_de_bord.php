<?php
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
    <title>Tableau de Bord Administrateur</title>
</head>
<body>
    <header>
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_nom']); ?></h1>
        <nav>
            <ul>
                <li><a href="gerer_demandes.php">Gérer les Demandes</a></li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <p>Utilisez le menu pour gérer les demandes.</p>
    </main>
</body>
</html>