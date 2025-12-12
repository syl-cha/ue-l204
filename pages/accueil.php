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

                <!-- Lien "Mes cours" -->
                <!-- <li>
                    <a href="#" title="Mes cours">Mes cours</a>
                </li> -->

                <!-- Lien Gestion visible uniquement pour l'admin -->
                <?php if (isAdmin()): ?>
                    <li>
                        <a href="views/admin.php" title="Espace administration">Gestion</a>
                    </li>
                <?php endif; ?>

                <!-- Lien Gestion visible uniquement pour l'admin -->
                <?php if (isTeacher()): ?>
                    <li>
                        <a href="views/teacher.php" title="Espace enseignant">Gestion</a>
                    </li>
                <?php endif; ?>

                <!-- Lien Gestion visible uniquement pour l'étudiant -->
                <?php if (isStudent()): ?>
                    <li>
                        <a href="views/student.php" title="Espace étudiant">Gestion</a>
                    </li>
                <?php endif; ?>

                <!-- Déconnexion -->
                <li id="deconnexion">
                    <a href="../pages/deconnexion.php" title="Déconnexion">Se déconnecter</a>
                </li>
            </ul>
        </nav>

        <h1>Bonjour <?php echo htmlspecialchars($login, ENT_QUOTES, 'UTF-8'); ?></h1>
    </header>

    <main style="margin: 2rem;">
        <p>Connecté en tant que : <strong><?php echo htmlspecialchars($login, ENT_QUOTES, 'UTF-8'); ?></strong></p>

        <?php if ($role === 'admin'): ?>
            <h2>Accueil administrateur</h2>
            <p>
                Vous êtes connecté en tant qu’administrateur.
                Utilisez le menu <strong>Gestion</strong> pour accéder aux fonctionnalités
                de gestion des enseignants et des étudiants.
            </p>

        <?php elseif ($role === 'enseignant'): ?>
            <h2>Accueil enseignant</h2>
            <p>
                Vous êtes connecté en tant qu’enseignant.<br>
                Utilisez le menu <strong>Gestion</strong> pour accéder aux fonctionnalités
                de gestion de vos cours et de vos étudiants.
            </p>

        <?php elseif ($role === 'etudiant'): ?>
            <h2>Accueil étudiant</h2>
            <p>
                Vous êtes connecté en tant qu’étudiant.
                Ici, vous pourrez plus tard consulter vos inscriptions, vos résultats, etc.
            </p>

        <?php else: ?>
            <h2>Accueil</h2>
            <p>Bienvenue sur la plateforme interne de l’université.</p>
        <?php endif; ?>
    </main>
</body>
</html>
