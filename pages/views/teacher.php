<?php
require_once __DIR__ . '/../logic/teacher.logic.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Mini-projet | Groupe 5 - UE 204 / Enseignant</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="assets/logo.png" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
  <header id="header">
    <nav id="header-nav">
      <ul>
        <div class="logo-menu">
          <a href="accueil.php">
            <img src="../../assets/images/logo.png" alt="Logo université" class="logo-menu-img">
          </a>
        </div>

        <li>
          <a href="../accueil.php" title="Retour">Retour</a>
        </li>

        <li id="deconnexion">
          <a href="../../pages/deconnexion.php" title="Déconnexion">Se déconnecter</a>
        </li>
      </ul>
    </nav>
    <h1>Espace Enseignant</h1>
  </header>

  <main style="margin: 2rem;">
    <p>Connecté en tant que : <strong><?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?></strong></p>

    <h2>Administration des cours et élèves</h2>

    <!-- Boutons d'action (teacher) -->
    <div class="admin-actions">
      <a class="btn" href="teacher.php?action=liste_cours" title="Lister les cours">Lister les cours</a>
      <!-- <a class="btn" href="teacher.php?action=liste_etudiants">Lister les étudiants</a>
        <a class="btn btn-secondary" href="teacher.php?action=add_enseignant">Ajouter un enseignant</a>
        <a class="btn btn-secondary" href="teacher.php?action=add_etudiant">Ajouter un étudiant</a> -->
    </div>

    <hr>

    <?php if ($action === 'liste_cours'): ?>
      <?php if (!$cours): ?>
        <p class="warning">Problème avec la récupération des cours.</p>
      <?php else: ?>
      <?php if (empty($cours)): ?>
        <p>Aucun cours trouvé</p>
      <?php else: ?>
        <div class="table-container">
          <h2>Liste des cours</h2>
          <p class="subtitle">Visualisation du catalogue des cours</p>
          <div class="table-wrapper">
            <table class="table-admin">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Code</th>
                  <th>Nom</th>
                  <th>Credits</th>
                  <th>Description</th>
                  <th>Capacité Max</th>
                  <th>Année</th>
                  <th>Actif</th>
                            <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($cours as $c): ?>
                  <tr>
                    <td><?= htmlspecialchars($c['id']); ?></td>
                    <td><?= htmlspecialchars($c['code']); ?></td>
                    <td><?= htmlspecialchars($c['nom']); ?></td>
                    <td><?= htmlspecialchars($c['credits']); ?></td>
                    <td><?= htmlspecialchars($c['description']); ?></td>
                    <td><?= htmlspecialchars($c['capacite_max']); ?></td>
                    <td><?= htmlspecialchars($c['annee_universitaire']); ?></td>
                    <td><span class="badge badge-soft"><?= htmlspecialchars($c['actif'] ? 'Actif' : 'Inactif') ?></span></td>
                    <td>
                      <div class="actions">
                        <a href="teacher.php?action=liste_perequis&cours_id=<?= (int)$c['id'] ?>" class="btn btn-xs">Prérequis</a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
  </main>

</body>

</html>