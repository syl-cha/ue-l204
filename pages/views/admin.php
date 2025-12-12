<?php
require_once __DIR__ . '/../logic/admin.logic.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mini-projet | Groupe 5 - UE 204 / Administration</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="assets/logo.png"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
<header id="header">
    <nav id="header-nav">
        <ul>
            <div class="logo-menu">
                <a href="../accueil.php">
                    <img src="../../assets/images/logo.png" alt="Logo université" class="logo-menu-img">
                </a>
            </div>

            <li>
                <a href="../accueil.php" title="Retour">Retour</a>
            </li>

            <!-- On reste sur admin.php pour la gestion -->
            <!-- <li>
                <a href="admin.php" title="Espace administration">Gestion</a>
            </li> -->

            <li id="deconnexion">
                <a href="../../pages/deconnexion.php" title="Déconnexion">Se déconnecter</a>
            </li>
        </ul>
    </nav>
    <h1>Espace administration</h1>
</header>

<main style="margin: 2rem;">
    <p>Connecté en tant que : <strong><?= htmlspecialchars($login, ENT_QUOTES, 'UTF-8') ?></strong></p>

    <h2>Administration des utilisateurs</h2>

    <!-- Boutons d'action (admin) -->
    <div class="admin-actions">
        <a class="btn" href="admin.php?action=liste_enseignants">Lister les enseignants</a>
        <a class="btn" href="admin.php?action=liste_etudiants">Lister les étudiants</a>
        <a class="btn btn-secondary" href="admin.php?action=add_enseignant">Ajouter un enseignant</a>
        <a class="btn btn-secondary" href="admin.php?action=add_etudiant">Ajouter un étudiant</a>
    </div>

    <hr>

    <!-- Formulaire d'ajout d'enseignant -->
    <?php if ($action === 'add_enseignant'): ?>
        <h3>Ajouter un enseignant</h3>
        <form method="post" class="edit-form">
            <input type="hidden" name="action" value="create_enseignant">

            <div>
                <label>Login</label>
                <input type="text" name="login" required>
            </div>

            <div>
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>

            <div>
                <label>Prénom</label>
                <input type="text" name="prenom" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div>
                <label>Bureau</label>
                <input type="text" name="bureau">
            </div>

            <div>
                <label>Téléphone</label>
                <input type="text" name="telephone">
            </div>

            <div>
                <label>Spécialité</label>
                <input type="text" name="specialite">
            </div>

            <div>
                <label>Statut</label>
                <select name="statut">
                    <?php foreach (['titulaire','vacataire','contractuel'] as $s): ?>
                        <option value="<?= $s ?>"><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="edit-form-actions">
                <button type="submit" class="btn">Créer</button>
                <a href="admin.php?action=liste_enseignants" class="btn btn-secondary">Annuler</a>
            </div>
            <?php if (hasFeedbackInSession() && empty($_SESSION['feedback']['success'])): ?>
                    <p class="warning"><?= htmlspecialchars($_SESSION['feedback']['message']) ?></p>
                    <?php unset($_SESSION['feedback']); ?>
                <?php endif; ?>
        </form>

        <hr>
    <?php endif; ?>

    <!-- Formulaire d'ajout d'étudiant -->
    <?php if ($action === 'add_etudiant'): ?>
        <h3>Ajouter un étudiant</h3>
        <form method="post" class="edit-form">
            <input type="hidden" name="action" value="create_etudiant">

            <div>
                <label>Login</label>
                <input type="text" name="login" required>
            </div>

            <div>
                <label>Numéro étudiant</label>
                <input type="text" name="numero_etudiant" required>
            </div>

            <div>
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>

            <div>
                <label>Prénom</label>
                <input type="text" name="prenom" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div>
                <label>Niveau</label>
                <select name="niveau">
                    <?php foreach (['L1','L2','L3','M1','M2'] as $n): ?>
                        <option value="<?= $n ?>"><?= $n ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="edit-form-actions">
                <button type="submit" class="btn">Créer</button>
                <a href="admin.php?action=liste_etudiants" class="btn btn-secondary">Annuler</a>
            </div>
            <?php if (hasFeedbackInSession() && empty($_SESSION['feedback']['success'])): ?>
                    <p class="warning"><?= htmlspecialchars($_SESSION['feedback']['message']) ?></p>
                    <?php unset($_SESSION['feedback']); ?>
            <?php endif; ?>
        </form>

        <hr>
    <?php endif; ?>

    <!-- Liste + édition enseignants -->
    <?php if ($action === 'liste_enseignants' || $action === 'edit_enseignant'): ?>

        <?php if (hasFeedbackInSession() && !empty($_SESSION['feedback']['success'])): ?>
            <p class="success"><?= htmlspecialchars($_SESSION['feedback']['message']) ?></p>
            <?php unset($_SESSION['feedback']); ?>
        <?php endif; ?>

        <?php if (empty($enseignants)): ?>
            <p>Aucun enseignant trouvé.</p>
        <?php else: ?>
            <div class="table-container">
                <h2>Liste des enseignants</h2>
                <p class="subtitle">Visualisation et gestion des comptes enseignants.</p>
                <div class="table-wrapper">
                    <table class="table-admin">
                        <thead>
                        <tr>
                            <th>Login</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Bureau</th>
                            <th>Téléphone</th>
                            <th>Spécialité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($enseignants as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['login']) ?></td>
                                <td><?= htmlspecialchars($e['nom']) ?></td>
                                <td><?= htmlspecialchars($e['prenom']) ?></td>
                                <td><?= htmlspecialchars($e['email']) ?></td>
                                <td><?= htmlspecialchars($e['bureau']) ?></td>
                                <td><?= htmlspecialchars($e['telephone']) ?></td>
                                <td><?= htmlspecialchars($e['specialite']) ?></td>
                                <td><span class="badge badge-soft"><?= htmlspecialchars($e['statut']) ?></span></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-xs"
                                           href="admin.php?action=edit_enseignant&id=<?= (int)$e['id_enseignant'] ?>">
                                            Modifier
                                        </a>
                                        <form method="post"
                                              onsubmit="return confirm('Supprimer cet enseignant ?');">
                                            <input type="hidden" name="action" value="delete_enseignant">
                                            <input type="hidden" name="id_utilisateur" value="<?= (int)$e['id_utilisateur'] ?>">
                                            <button type="submit" class="btn btn-xs btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <?php if (
                                $action === 'edit_enseignant'
                                && $enseignantCourant
                                && $enseignantCourant['id_enseignant'] == $e['id_enseignant']
                            ): ?>
                                <tr class="inline-edit-row">
                                    <td colspan="9" class="inline-edit-cell">
                                        <form method="post" class="edit-form-modif">
                                            <input type="hidden" name="action" value="save_enseignant">
                                            <input type="hidden" name="id_enseignant" value="<?= (int)$e['id_enseignant'] ?>">

                                            <div>
                                                <label>Nom</label>
                                                <input type="text" name="nom" value="<?= htmlspecialchars($enseignantCourant['nom']) ?>">
                                            </div>

                                            <div>
                                                <label>Prénom</label>
                                                <input type="text" name="prenom" value="<?= htmlspecialchars($enseignantCourant['prenom']) ?>">
                                            </div>

                                            <div>
                                                <label>Email</label>
                                                <input type="email" name="email" value="<?= htmlspecialchars($enseignantCourant['email']) ?>">
                                            </div>

                                            <div>
                                                <label>Bureau</label>
                                                <input type="text" name="bureau" value="<?= htmlspecialchars($enseignantCourant['bureau']) ?>">
                                            </div>

                                            <div>
                                                <label>Téléphone</label>
                                                <input type="text" name="telephone" value="<?= htmlspecialchars($enseignantCourant['telephone']) ?>">
                                            </div>

                                            <div>
                                                <label>Spécialité</label>
                                                <input type="text" name="specialite" value="<?= htmlspecialchars($enseignantCourant['specialite']) ?>">
                                            </div>

                                            <div>
                                                <label>Statut</label>
                                                <select name="statut">
                                                    <?php foreach (['titulaire','vacataire','contractuel'] as $s): ?>
                                                        <option value="<?= $s ?>" <?= $s === $enseignantCourant['statut'] ? 'selected' : '' ?>>
                                                            <?= ucfirst($s) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div>
                                                <button type="submit" class="btn">Enregistrer</button>
                                            </div>

                                            <div>
                                                <a href="admin.php?action=liste_enseignants" class="btn btn-secondary">Annuler</a>
                                            </div>
                                           
                                        </form>
                                         <?php if (hasFeedbackInSession() && empty($_SESSION['feedback']['success'])): ?>
                                                <p class="warning"><?= htmlspecialchars($_SESSION['feedback']['message']) ?></p>
                                                <?php unset($_SESSION['feedback']); ?>
                                            <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <hr>
    <?php endif; ?>

    <!-- Liste + édition étudiants -->
    <?php if ($action === 'liste_etudiants' || $action === 'edit_etudiant'): ?>
        
        <?php if (hasFeedbackInSession() && !empty($_SESSION['feedback']['success'])): ?>
            <p class="success"><?= htmlspecialchars($_SESSION['feedback']['message']) ?></p>
            <?php unset($_SESSION['feedback']); ?>
        <?php endif; ?>

        <?php if (empty($etudiants)): ?>
            <p>Aucun étudiant trouvé.</p>
        <?php else: ?>
            <div class="table-container">
                <h2>Liste des étudiants</h2>
                <p class="subtitle">Visualisation et gestion des comptes étudiants.</p>

                <div class="table-wrapper">
                    <table class="table-admin">
                        <thead>
                        <tr>
                            <th>Numéro étudiant</th>
                            <th>Login</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Niveau</th>
                            <th>Date d’inscription</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($etudiants as $e): ?>
                            <tr>
                                <td><?= htmlspecialchars($e['numero_etudiant']) ?></td>
                                <td><?= htmlspecialchars($e['login']) ?></td>
                                <td><?= htmlspecialchars($e['nom']) ?></td>
                                <td><?= htmlspecialchars($e['prenom']) ?></td>
                                <td><span class="badge badge-soft"><?= htmlspecialchars($e['niveau']) ?></span></td>
                                <td><?= htmlspecialchars($e['date_inscription']) ?></td>
                                <td><?= htmlspecialchars($e['email']) ?></td>
                                <td>
                                    <div class="actions">
                                        <form method="get">
                                            <input type="hidden" name="action" value="edit_etudiant">
                                            <input type="hidden" name="id" value="<?= (int)$e['id_etudiant'] ?>">
                                            <button type="submit" class="btn btn-xs">Modifier</button>
                                        </form>
                                        <form method="post"
                                              onsubmit="return confirm('Supprimer cet étudiant ?');">
                                            <input type="hidden" name="action" value="delete_etudiant">
                                            <input type="hidden" name="id_utilisateur" value="<?= (int)$e['id_utilisateur'] ?>">
                                            <button type="submit" class="btn btn-xs btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <?php if (
                                $action === 'edit_etudiant'
                                && $etudiantCourant
                                && $etudiantCourant['id_etudiant'] == $e['id_etudiant']
                            ): ?>
                                <tr class="inline-edit-row">
                                    <td colspan="8" class="inline-edit-cell">
                                        <form method="post" class="edit-form">
                                            <input type="hidden" name="action" value="save_etudiant">
                                            <input type="hidden" name="id_etudiant" value="<?= (int)$etudiantCourant['id_etudiant'] ?>">

                                            <div>
                                                <label>Numéro étudiant</label>
                                                <input type="text" name="numero_etudiant" value="<?= htmlspecialchars($etudiantCourant['numero_etudiant']) ?>">
                                            </div>

                                            <div>
                                                <label>Nom</label>
                                                <input type="text" name="nom" value="<?= htmlspecialchars($etudiantCourant['nom']) ?>">
                                            </div>

                                            <div>
                                                <label>Prénom</label>
                                                <input type="text" name="prenom" value="<?= htmlspecialchars($etudiantCourant['prenom']) ?>">
                                            </div>

                                            <div>
                                                <label>Email</label>
                                                <input type="email" name="email" value="<?= htmlspecialchars($etudiantCourant['email']) ?>">
                                            </div>

                                            <div>
                                                <label>Niveau</label>
                                                <select name="niveau">
                                                    <?php
                                                    $niveaux = ['L1', 'L2', 'L3', 'M1', 'M2'];
                                                    foreach ($niveaux as $n) {
                                                        $sel = ($etudiantCourant['niveau'] === $n) ? 'selected' : '';
                                                        echo '<option value="' . htmlspecialchars($n) . "\" $sel>" . htmlspecialchars($n) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="edit-form-actions">
                                                <button type="submit" class="btn">Enregistrer</button>
                                                <a href="admin.php?action=liste_etudiants" class="btn btn-secondary">Annuler</a>
                                            </div>
                                            
                                        </form>
                                        <?php if (hasFeedbackInSession() && empty($_SESSION['feedback']['success'])): ?>
                                                <p class="warning"><?= htmlspecialchars($_SESSION['feedback']['message']) ?></p>
                                                <?php unset($_SESSION['feedback']); ?>
                                            <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</main>

</body>
</html>
