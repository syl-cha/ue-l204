<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

startSession();

if (!isConnecte()) {
  header('Location: ../index.php');
  exit;
}

// On récupère le login et le rôle
$login = $_SESSION['login'] ?? '';
$role  = $_SESSION['role']  ?? '';

$elements = redirectByRole();

if (file_exists($elements['logic_path'])) {
    require_once $elements['logic_path'];
} else {
    die("Erreur : fichier logique introuvable.");
}
?>
 
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Mini-projet | Groupe 5 - UE 204 / <?= $elements['tab'] ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="assets/logo.png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>
  <header id="header">
    <nav id="header-nav">
      <ul>
        <div class="logo-menu">
            <img src="../assets/images/logo.png" alt="Logo université" class="logo-menu-img">
        </div>

        <!-- Déconnexion -->
        <li id="deconnexion">
          <a href="../pages/deconnexion.php" title="Déconnexion">Se déconnecter</a>
        </li>
      </ul>
    </nav>

    <h1>Espace <?= $elements['espace'] ?></h1>
  </header>

  <main style="margin: 2rem;">

    <p>Connecté en tant que : <strong><?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?></strong></p>

    <h2>Accueil <?= $elements['accueil'] ?></h2>
    <p>
      <?= $elements['message'] ?>
    </p>
    <?php
    switch ($role) {
      case 'admin':
        include('views/admin.php');
        break;
      case 'enseignant':
        include('views/teacher.php');
        break;
      case 'etudiant':
        include('views/student.php');
        break;

      default:
        # code...
        break;
    } ?>
  </main>
</body>

</html>