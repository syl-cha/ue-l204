<?php
require_once __DIR__ . '/../functions.php';
startSession();

if (!isConnecte()) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Mini-projet | Groupe 5 - UE 204 / Accueil</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assets/logo.png"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header id="header">
        <nav id="header-nav">
            <ul>
                <div class="logo-menu">
                    <a href="accueil.php">
                        <img src="../assets/images/logo.png" alt="Logo université" class="logo-menu-img">
                    </a>
                </div>
                <li>
                    <a href="#" title="Mes cours"> Mes cours</a>
                </li>
                <li>
                    <a href="../pages/ajouts.php" title="Page ajouts">Gestion</a>
                </li>
                <li id="deconnexion">
                    <a href="../pages/deconnexion.php" title="Déconnexion">Se déconnecter</a>
                </li>
            </ul>
        </nav>
        <h1>Bonjour <?php echo $_SESSION['login']; ?></h1>
    </header>

</body>
</html>