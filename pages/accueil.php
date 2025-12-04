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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <p class="txt-center">
        <a href="../pages/deconnexion.php" title="Déconnexion">Se déconnecter</a>
    </p>
</body>
</html>