<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../functions.php';
require_once __DIR__ . '/../../classes/universite-db.class.php';

startSession();

if (!isConnecte()) {
    header('Location: ../index.php');
    exit;
}

// Sécurité : seuls les admins peuvent accéder à cette page
if (!isAdmin()) {
    header('Location: ../accueil.php');
    exit;
}

// On instancie notre classe d'accès BDD
$db = new UniversiteDB();

// On récupère le login et le rôle (principalement pour l’affichage)
$login = $_SESSION['login'] ?? '';
$role  = $_SESSION['role']  ?? '';

// TRAITEMENT DES ACTIONS POST (admin uniquement)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionPost = $_POST['action'] ?? '';

    // Suppression d'un enseignant (on supprime l'utilisateur => cascade sur enseignant)
    if ($actionPost === 'delete_enseignant') {
        $idUtilisateur = (int)($_POST['id_utilisateur'] ?? 0);

        if ($idUtilisateur > 0) {
            $db->deleteUserById($idUtilisateur);
        }
        header('Location: admin.php?action=liste_enseignants');
        exit;
    }

    // Suppression d'un étudiant (on supprime l'utilisateur => cascade sur etudiant)
    if ($actionPost === 'delete_etudiant') {
        $idUtilisateur = (int)($_POST['id_utilisateur'] ?? 0);

        if ($idUtilisateur > 0) {
            $db->deleteUserById($idUtilisateur);
        }
        header('Location: admin.php?action=liste_etudiants');
        exit;
    }

    // Sauvegarde d'un enseignant modifié (avec validations)
    if ($actionPost === 'save_enseignant') {

        $errors = [];

        $idEns = (int)($_POST['id_enseignant'] ?? 0);
        if ($idEns <= 0) {
            $errors[] = "ID enseignant invalide.";
        }

        $nom    = validate_required_text($errors, 'nom', 'Nom', 3, 32);
        $prenom = validate_required_text($errors, 'prenom', 'Prénom', 3, 32);
        $email  = validate_email_required($errors, 'email', 'Email');
        $bureau = validate_bureau_required($errors, 'bureau', 'Bureau');
        $tel    = validate_phone_fr_required($errors, 'telephone', 'Téléphone');
        $spec   = validate_required_text($errors, 'specialite', 'Spécialité', 3, 64);
        $statut = validate_enum_required($errors, 'statut', 'Statut', [
            'titulaire', 'vacataire', 'contractuel'
        ]);

        if (!empty($errors)) {
            $_SESSION['feedback'] = [
                'message' => implode(' ', $errors),
                'success' => false
            ];
            header('Location: admin.php?action=edit_enseignant&id=' . $idEns);
            exit;
        }

        // updateEnseignant doit idéalement retourner bool.
        $ok = $db->updateEnseignant($idEns, $nom, $prenom, $email, $bureau, $tel, $spec, $statut);

        if ($ok) {
            $_SESSION['feedback'] = [
                'message' => "Les informations de l’enseignant ont été mises à jour.",
                'success' => true
            ];
        } else {
            $_SESSION['feedback'] = [
                'message' => "Erreur : mise à jour de l’enseignant impossible.",
                'success' => false
            ];
        }

        header('Location: admin.php?action=liste_enseignants');
        exit;
    }

    // Sauvegarde d'un étudiant modifié (avec validations)
    if ($actionPost === 'save_etudiant') {

        $errors = [];

        $idEtu = (int)($_POST['id_etudiant'] ?? 0);
        if ($idEtu <= 0) {
            $errors[] = "ID étudiant invalide.";
        }

        
        $numEtu = validate_numero_etudiant_required($errors, 'numero_etudiant', 'Numéro étudiant');
        $nom    = validate_required_text($errors, 'nom', 'Nom', 3, 32);
        $prenom = validate_required_text($errors, 'prenom', 'Prénom', 3, 32);
        $email  = validate_email_required($errors, 'email', 'Email');
        $niveau = validate_enum_required($errors, 'niveau', 'Niveau', [
            'L1','L2','L3','M1','M2'
        ]);

        if (!empty($errors)) {
            $_SESSION['feedback'] = [
                'message' => implode(' ', $errors),
                'success' => false
            ];
            header('Location: admin.php?action=edit_etudiant&id=' . $idEtu);
            exit;
        }

        $ok = $db->updateEtudiant($idEtu, $nom, $prenom, $email, $numEtu, $niveau);

        if ($ok) {
            $_SESSION['feedback'] = [
                'message' => "Les informations de l’étudiant ont été mises à jour.",
                'success' => true
            ];
        } else {
            $_SESSION['feedback'] = [
                'message' => "Erreur : mise à jour de l’étudiant impossible.",
                'success' => false
            ];
        }

        header('Location: admin.php?action=liste_etudiants');
        exit;
    }

    // Création d'un nouvel enseignant avec vérifications du remplissage des champs
    if ($actionPost === 'create_enseignant') {

        $errors = [];

        $loginEns = validate_login_required($errors, 'login', 'Login');
        $nom      = validate_required_text($errors, 'nom', 'Nom', 3, 32);
        $prenom   = validate_required_text($errors, 'prenom', 'Prénom', 3, 32);
        $email    = validate_email_required($errors, 'email', 'Email');
        $bureau   = validate_bureau_required($errors, 'bureau', 'Bureau');
        $tel      = validate_phone_fr_required($errors, 'telephone', 'Téléphone');
        $spec     = post_str('specialite');
        $statut   = validate_enum_required($errors, 'statut', 'Statut', ['titulaire', 'vacataire', 'contractuel']);

        if (!empty($errors)) {
            $_SESSION['feedback'] = [
                'message' => implode(' ', array_values($errors)),
                'success' => false
            ];
            header('Location: admin.php?action=add_enseignant');
            exit;
        }

        // Exemple : nettoyage optionnel des champs libres
        $bureau = ($bureau !== null) ? clean_text($bureau) : null;
        $spec   = ($spec !== null) ? clean_text($spec) : null;

        // Appel BDD (à adapter à ta signature réelle)
        $ok = $db->addEnseignant($loginEns, $nom, $prenom, $email, $bureau, $tel, $spec, $statut);

        if ($ok) {
            $_SESSION['feedback'] = ['message' => "L’enseignant \"$loginEns\" a bien été ajouté.", 'success' => true];
            header('Location: admin.php?action=liste_enseignants');
            exit;
        }

        $_SESSION['feedback'] = ['message' => "Erreur : l’enseignant n’a pas été ajouté (login déjà existant ?).", 'success' => false];
        header('Location: admin.php?action=add_enseignant');
        exit;
    }

    // Création d'un nouvel étudiant
    if ($actionPost === 'create_etudiant') {

        $errors = [];

        $loginEtu = validate_login_required($errors, 'login', 'Login');
        $numEtu   = validate_numero_etudiant_required($errors, 'numero_etudiant', 'Numéro étudiant');
        $nom      = validate_required_text($errors, 'nom', 'Nom', 3, 32);
        $prenom   = validate_required_text($errors, 'prenom', 'Prénom', 3, 32);
        $email    = validate_email_required($errors, 'email', 'Email');
        $niveau   = validate_enum_required($errors,'niveau','Niveau', ['L1', 'L2', 'L3', 'M1', 'M2']);

        // S'il y a des erreurs → feedback + retour formulaire
        if (!empty($errors)) {
            $_SESSION['feedback'] = [
                'message' => implode(' ', array_values($errors)),
                'success' => false
            ];
            header('Location: admin.php?action=add_etudiant');
            exit;
        }

        // Appel BDD
        $ok = $db->addEtudiant($loginEtu, $nom, $prenom, $email, $numEtu, $niveau);

        if ($ok) {
            $_SESSION['feedback'] = [
                'message' => "L’étudiant \"$loginEtu\" a bien été ajouté.",
                'success' => true
            ];
            header('Location: admin.php?action=liste_etudiants');
            exit;
        }

        // Erreur BDD (clé unique, contrainte, etc.)
        $_SESSION['feedback'] = [
            'message' => "Erreur : l’étudiant n’a pas été ajouté (login ou numéro étudiant déjà existant ?).",
            'success' => false
        ];
        header('Location: admin.php?action=add_etudiant');
        exit;
    }

}

// TRAITEMENT DES ACTIONS GET (listes / édition / ajout)

$action = $_GET['action'] ?? null;

// Ces variables servent pour l'affichage admin
$enseignants       = [];
$etudiants         = [];
$enseignantCourant = null;
$etudiantCourant   = null;

// Liste / détail seulement si on affiche les sections correspondantes
if ($action === 'liste_enseignants' || $action === 'edit_enseignant') {
    $enseignants = $db->getAllEnseignants();
}

if ($action === 'edit_enseignant' && isset($_GET['id'])) {
    $idEns = (int)$_GET['id'];
    if ($idEns > 0) {
        $enseignantCourant = $db->getEnseignantById($idEns);
    }
}

if ($action === 'liste_etudiants' || $action === 'edit_etudiant') {
    $etudiants = $db->getAllEtudiants();
}

if ($action === 'edit_etudiant' && isset($_GET['id'])) {
    $idEtu = (int)$_GET['id'];
    if ($idEtu > 0) {
        $etudiantCourant = $db->getEtudiantById($idEtu);
    }
}
