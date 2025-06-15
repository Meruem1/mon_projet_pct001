<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion_citoyen.html');
    exit;
}
require_once '../config/database.php';

$nom = $_SESSION['user_nom'] ?? '';
$prenom = $_SESSION['user_prenom'] ?? '';
$email = $_SESSION['user_email'] ?? '';
$telephone = $_SESSION['user_telephone'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'])) {
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $email, $telephone, $_SESSION['user_id']]);
    $_SESSION['user_nom'] = $nom;
    $_SESSION['user_prenom'] = $prenom;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_telephone'] = $telephone;
    $_SESSION['message'] = "Profil mis à jour avec succès.";
    header('Location: profil.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="../ressources/css/style.css">
</head>
<body>
    <header>
        <h1>Mon Profil</h1>
        <nav>
            <ul>
                <li><a href="tableau_de_bord.php">Accueil</a></li>
                <li><a href="mes_actes.html">Mes Actes</a></li>
                <li><a href="demande_acte.html">Faire une Demande</a></li>
                <li><a href="../deconnexion.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="success">'.htmlspecialchars($_SESSION['message']).'</div>';
            unset($_SESSION['message']);
        }
        ?>
        <section>
            <h2>Informations Personnelles</h2>
            <form action="profil.php" method="POST">
                <div>
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                </div>
                <div>
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" required>
                </div>
                <div>
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div>
                    <label for="telephone">Téléphone :</label>
                    <input type="tel" id="telephone" name="telephone" value="<?php echo htmlspecialchars($telephone); ?>" required>
                </div>
                <button type="submit">Mettre à Jour</button>
            </form>
        </section>
    </main>
</body>
</html>