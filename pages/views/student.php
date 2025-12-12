<?php
require_once __DIR__ . '/../logic/student.logic.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Mini-projet | Groupe 5 - UE 204 / Étudiant</title>
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
    <h1>Espace Étudiant</h1>
  </header>

  <main style="margin: 2rem;">
    <p>Connecté en tant que : <strong><?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?></strong></p>

    <h2>Gestion de vos cours</h2>

    <!-- Boutons d'action (student) -->
    <div class="admin-actions">
      <a class="btn" href="student.php?action=liste_cours" title="Lister tous les cours">Lister tous les cours</a>
      <a class="btn" href="student.php?action=liste_enseignements" title="Lister mes enseignements">Lister mes cours</a>

      <?php if (hasFeedbackInSession()): ?>
        <span class=<?= $_SESSION['feedback']['success'] ? 'success' : 'warning'?> ><?= htmlspecialchars($_SESSION['feedback']['message']) ?></span>
        <?php unset($_SESSION['feedback']); ?>
      <?php endif; ?>
    </div>

    <hr>

    <?php if ($action === 'liste_cours'): ?>
      <?php if ($cours === false): ?>
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
                          <?php if (in_array($c['id'], $coursDejaSuivis)): ?>
                            <span class="badge badge-soft">Inscrit</span>
                          <?php else: ?>
                            <a href="student.php?action=inscription_cours&cours_id=<?= (int)$c['id'] ?>" class="btn btn-xs">S'inscrire</a>
                          <?php endif; ?>
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

  

    <?php if ($action === 'liste_enseignements'): ?>
      <h2>Liste de vos enseignements</h2>
      <?php if (empty($coursDejaSuivis)): ?>
        <p>Vous ne participez actuellement à aucun cours.</p>
      <?php else: ?>
        <div class="table-container">
          <p class="subtitle">Visualisation du catalogue des cours auxquels vous participez</p>
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
                  <th>Note</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($coursDejaSuivis as $c): ?>
                  <tr>
                    <td><?= htmlspecialchars($c['id']); ?></td>
                    <td><?= htmlspecialchars($c['code']); ?></td>
                    <td><?= htmlspecialchars($c['nom']); ?></td>
                    <td><?= htmlspecialchars($c['credits']); ?></td>
                    <td><?= htmlspecialchars($c['description']); ?></td>
                    <td><?= htmlspecialchars($c['capacite_max']); ?></td>
                    <td><?= htmlspecialchars($c['annee_universitaire']); ?></td>
                    <td><span class="badge badge-soft"><?= htmlspecialchars($cours['actif'] ? 'Actif' : 'Inactif') ?></span></td>
                    <td><span class="badge <?= $c['valide'] ? 'badge-soft' : '' ?>"><?= $c['valide'] ? 'Validé' : 'En cours' ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($action === 'creer_cours'): ?>
      <h3>Créer un cours</h3>
      <form class="edit-form" method="post">
        <input type="hidden" name="action" value="add_course">
        <div>
          <label for="course-code">Code : </label>
          <input type="text" name="course-code" id="course-code" required>
        </div>
        <div>
          <label for="course-name">Nom : </label>
          <input type="text" name="course-name" id="course-name" required>
        </div>
        <div>
          <label for="course-credits">Credits : </label>
          <input type="number" min=1 max=12 name="course-credits" id="course-credits" required>
        </div>
        <div>
          <label for="course-description">Description : </label>
          <input type="text" name="course-description" id="course-description" required>
        </div>
        <div>
          <label for="course-year">Année : </label>
          <input type="text" name="course-year" id="course-year" required>
        </div>
        <div>
          <label for="course-capacity">Capacité max : </label>
          <input type="number" min=1 max=100 name="course-capacity" id="course-capacity" required>
        </div>
        <div>
          <label for="course-prerequisites">Prérequis (codes séparés par virgule) : </label>
          <input type="text" name="course-prerequisites" id="course-prerequisites">
        </div>

        <div class="edit-form-actions">
          <button type="submit" class="btn">Créer</button>
          <a href="teacher.php?action=liste_cours" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    <?php endif; ?>
  </main>

</body>

</html>