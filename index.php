<?php
// ini_set("display_errors", "Off");
ini_set("database-errors", "On");
// On appelle le fichier des fonctions une seule fois et s'il n'existe pas, le script s'arrête (grâce à require_once)
require_once 'functions.php';
startSession();
require('./classes/universite-db.class.php');

// Instancie la classe UniversiteDB (dans le fichier "universite-db.class.php)
$db = new UniversiteDB();

// Appel de la fonction : si l'utilisateur est déjà connecté, il est directement redirigé vers l'accueil
if (isConnecte()) {
  header('Location: pages/accueil.php');
  exit;
}

$erreur = '';

// Vérification de l'identifiant et du mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['identifiant']); // trim () supprime les espaces et les caractères invisibles
  $mdp   = trim($_POST['mdp']);

  // Gestion de la connexion de l’utilisateur :
  // - sinon -> erreur d’authentification
  if ($login === '' || $mdp === '') { // - champs vides → erreur
    $erreur = "Veuillez remplir tous les champs.";
    // - identifiants valides -> connexion + redirection
  } elseif ($db->goodLoginPasswordPair($login, $mdp)) {
    setConnecte($db->getUserByLogin($login));
    header('Location: pages/accueil.php');
    exit;
    // - sinon -> erreur d’authentification
  } else {
    $erreur = "Identifiant ou mot de passe incorrect.";
  }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Mini-projet | Groupe 5 - UE 204</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="assets/logo.png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="row connexion">
    <!-- Formulaire de connexion -->
    <div class="img-connexion">
      <img src="assets/images/logo.png" alt="Logo de l'Université">
      <h1>Université de Limoges</h1>
      <p>Bienvenue sur la plateforme interne de l’Université de Limoges. Veuillez vous connecter pour accéder à votre espace personnel.
      </p>
    </div>
    <div class="section-connexion">
      <h2>Connexion</h2>
      <form action="" method="post" class="login-form">
        <label for="identifiant">Identifiant :</label>
        <div class="input-icon">
          <input type="text" id="identifiant" name="identifiant" placeholder="Veuillez entrer votre identifiant" required>
          <i class="fa-solid fa-user"></i>
        </div>
        <label for="mdp">Mot de passe :</label>
        <div class="input-icon">
          <input type="password" id="mdp" name="mdp" placeholder="Veuillez entrer votre mot de passe" required>
          <i class="fa-solid fa-lock"></i>
        </div>
        <!-- Si $erreur n'est pas vide, ou égale à 0, on l'affiche tout en l’échappant 
        pour éviter toute injection HTML avec htmlspecialchars (empêche l'exécution de code) 

        La synthaxe "?= $erreur ?" permet de remplacer "php echo : $erreur; -->
        <?php if (!empty($erreur)): ?>
          <p class="erreur"><?= htmlspecialchars($erreur) ?></p> 
        <?php endif; ?>
        <button type="submit" name="submit" class="btn btn-form">Se connecter</button>
      </form>
    </div>
  </div>
</body>

</html>
