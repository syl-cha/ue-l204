    <!-- Boutons d'action (teacher) -->
    <div class="admin-actions">
      <a class="btn" href="accueil.php?action=liste_cours" title="Lister les cours">Lister les cours</a>
      <a class="btn" href="accueil.php?action=liste_enseignements" title="Lister mes enseignements">Lister vos enseignements</a>
      <a class="btn btn-secondary" href="accueil.php?action=creer_cours" title="Créer un cours">Créer un cours</a>


      <div class="search-form">
        <form method="GET" action="accueil.php">
          <input type="hidden" name="action" value="liste_cours">
          <input type="search" name="search_cours" placeholder="Rechercher un cours" class="search">
          <button type="submit" name="submit_search" class="btn">Rechercher</button>
        </form>
      </div>
    </div>

    <hr>


    <?php if (hasFeedbackInSession()): ?>
      <span class=<?= $_SESSION['feedback']['success'] ? 'success' : 'warning' ?>><?= htmlspecialchars($_SESSION['feedback']['message']) ?></span>
      <?php unset($_SESSION['feedback']); ?>
    <?php endif; ?>

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
              <table class="table-cours">
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
                          <?php if (in_array($c['id'], $coursDejaEnseignes)): ?>
                            <span class="badge badge-soft">Membre</span>
                          <?php else: ?>
                            <a href="accueil.php?action=enseigner_cours&cours_id=<?= (int)$c['id'] ?>" class="btn btn-xs">Enseigner</a>
                          <?php endif; ?>
                          <?php if ($c['nb_prerequis'] ?? 0) : ?>
                            <a href="accueil.php?action=liste_prerequis&cours_id=<?= (int)$c['id'] ?>" class="btn btn-xs orange">Prérequis</a>
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

    <?php if ($action === 'liste_prerequis'): ?>
      <?php if ($prerequis === false): ?>
        <p class="warning">Problème avec la récupération des prérequis.</p>
      <?php else: ?>
        <?php if (empty($prerequis)): ?>
          <p>Le cours <?= $db->getCourseCodeById($coursId) ?> n'a pas de prérequis</p>
        <?php else: ?>
          <div class="table-container">
            <h2>Liste des prérequis pour le cours <?= $db->getCourseCodeById($coursId) ?></h2>
            <p class="subtitle">Visualisation du catalogue des cours prérequis</p>
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
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($prerequis as $p): ?>
                    <tr>
                      <td><?= htmlspecialchars($p['id']); ?></td>
                      <td><?= htmlspecialchars($p['code']); ?></td>
                      <td><?= htmlspecialchars($p['nom']); ?></td>
                      <td><?= htmlspecialchars($p['credits']); ?></td>
                      <td><?= htmlspecialchars($p['description']); ?></td>
                      <td><?= htmlspecialchars($p['capacite_max']); ?></td>
                      <td><?= htmlspecialchars($p['annee_universitaire']); ?></td>
                      <td><span class="badge badge-soft"><?= htmlspecialchars($p['actif'] ? 'Actif' : 'Inactif') ?></span></td>
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
      <?php if (empty($coursDejaEnseignes)): ?>
        <p>Vous ne participez actuellement à aucun cours.</p>
      <?php else: ?>
        <div class="table-container">
          <h2>Liste de vos enseignements</h2>
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
                </tr>
              </thead>
              <tbody>
                <?php foreach ($coursDejaEnseignes as $cId):
                  $cours = $db->getCourseById($cId);
                ?>
                  <tr>
                    <td><?= htmlspecialchars($cours['id']); ?></td>
                    <td><?= htmlspecialchars($cours['code']); ?></td>
                    <td><?= htmlspecialchars($cours['nom']); ?></td>
                    <td><?= htmlspecialchars($cours['credits']); ?></td>
                    <td><?= htmlspecialchars($cours['description']); ?></td>
                    <td><?= htmlspecialchars($cours['capacite_max']); ?></td>
                    <td><?= htmlspecialchars($cours['annee_universitaire']); ?></td>
                    <td><span class="badge badge-soft"><?= htmlspecialchars($cours['actif'] ? 'Actif' : 'Inactif') ?></span></td>
                    <td>
                      <div class="actions">
                        <a href="accueil.php?action=liste_etudiants&cours_id=<?= (int)$cours['id'] ?>" class="btn btn-xs">Étudiants</a>
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

    <?php if ($action === 'liste_etudiants'): ?>
      <h2>Etudiants inscrits dans votre cours</h2>
      <?php
      $infosCours = $db->getCourseById((int)$coursId);

      if ($infosCours): ?>
        <p class="subtitle">
          <strong><?= htmlspecialchars($infosCours['code']) ?></strong> -
          <?= htmlspecialchars($infosCours['nom']) ?>
        </p>

        <?php if (empty($etudiants)): ?>
          <p>Aucun étudiant inscrit pour le moment.</p>

        <?php else: ?>
          <div class="table-container">
            <p class="subtitle">Total : <?= count($etudiants) ?> étudiant(s)</p>
            <div class="table-wrapper">
              <table class="table-admin">
                <thead>
                  <tr>
                    <th>N° Étudiant</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Niveau</th>
                    <th>Date inscription</th>
                    <th>Note</th>
                    <th>Statut</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($etudiants as $etu): ?>
                    <tr>
                      <td><?= htmlspecialchars($etu['numero_etudiant']) ?></td>
                      <td><?= htmlspecialchars($etu['nom']) ?></td>
                      <td><?= htmlspecialchars($etu['prenom']) ?></td>
                      <td><?= htmlspecialchars($etu['email']) ?></td>
                      <td><?= htmlspecialchars($etu['niveau']) ?></td>
                      <td><?= htmlspecialchars(date('d/m/Y', strtotime($etu['date_inscription']))) ?></td>
                      <td>...</td>
                      <td><span class="badge badge-soft">Actif</span></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($action === 'creer_cours'): ?>
      <div class="form-wrapper">
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
            <a href="accueil.php?action=liste_cours" class="btn btn-secondary">Annuler</a>
          </div>
        </form>
      </div>
    <?php endif; ?>
