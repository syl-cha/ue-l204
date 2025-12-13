<?php
// ini_set("display_errors", "Off");
ini_set("database-errors", "On");
// On appelle le fichier des fonctions une seule fois et s'il n'existe pas, le script s'arrête (grâce à require_once)
require_once 'functions.php';
startSession();
require('./classes/universite-db.class.php');

$db = new UniversiteDB();

$addTest = $db->addCourse('INFO-L113', 'Introduction Rust', 3, "Initiation au langage Rust", '2025-2026', 40);
$addTest2 = $db->addCourse('INFO-L213', 'Rust Avancé', 3, "Approfondissement du langage Rust", '2025-2026', 20,['INFO-L113','INFO-L101','INFO-L106']);


// Si l'utilisateur est déjà connecté, il est directement redirigé vers l'accueil
if (isConnecte()) {
  header('Location: pages/accueil.php');
  exit;
}

$erreur = '';

// Vérification de l'identifiant et du mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['identifiant']);
  $mdp   = trim($_POST['mdp']);

  if ($login === '' || $mdp === '') {
    $erreur = "Veuillez remplir tous les champs.";
  } elseif ($db->goodLoginPasswordPair($login, $mdp)) {
    setConnecte($db->getUserByLogin($login));
    header('Location: pages/accueil.php');
    exit;
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
      <!--
      <p><strong><?php if ($addTest) {
                    echo 'Course 1 added successfully<br>';
                  } else {
                    echo 'Course 1 addition failed<br>';
                  } ?></strong></p>
      <p><strong><?php if ($addTest2) {
                    echo 'Course 2 added successfully<br>';
                  } else {
                    echo 'Course 2 addition failed<br>';
                  } ?></strong></p>-->
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
        <?php if (!empty($erreur)): ?>
          <p class="erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>
        <button type="submit" name="submit" class="btn btn-form">Se connecter</button>
      </form>
    </div>
  </div>
</body>

</html>