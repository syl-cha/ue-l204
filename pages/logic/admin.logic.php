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

    // Sauvegarde d'un enseignant modifié
    if ($actionPost === 'save_enseignant') {
        $idEns   = (int)($_POST['id_enseignant'] ?? 0);
        $nom     = $_POST['nom']        ?? '';
        $prenom  = $_POST['prenom']     ?? '';
        $email   = $_POST['email']      ?? '';
        $bureau  = $_POST['bureau']     ?? '';
        $tel     = $_POST['telephone']  ?? '';
        $spec    = $_POST['specialite'] ?? '';
        $statut  = $_POST['statut']     ?? 'titulaire';

        if ($idEns > 0) {
            $db->updateEnseignant(
                $idEns,
                $nom,
                $prenom,
                $email,
                $bureau,
                $tel,
                $spec,
                $statut
            );
        }

        header('Location: admin.php?action=liste_enseignants');
        exit;
    }

    // Sauvegarde d'un étudiant modifié
    if ($actionPost === 'save_etudiant') {
        $idEtu   = (int)($_POST['id_etudiant'] ?? 0);
        $nom     = $_POST['nom']              ?? '';
        $prenom  = $_POST['prenom']           ?? '';
        $email   = $_POST['email']            ?? '';
        $numEtu  = $_POST['numero_etudiant']  ?? '';
        $niveau  = $_POST['niveau']           ?? 'L1';

        if ($idEtu > 0) {
            $db->updateEtudiant(
                $idEtu,
                $nom,
                $prenom,
                $email,
                $numEtu,
                $niveau
            );
        }

        header('Location: admin.php?action=liste_etudiants');
        exit;
    }

    // Création d'un nouvel enseignant
    if ($actionPost === 'create_enseignant') {
        $loginEns  = $_POST['login']        ?? '';
        $pwdEns    = $_POST['mot_de_passe'] ?? '';
        $nom       = $_POST['nom']          ?? '';
        $prenom    = $_POST['prenom']       ?? '';
        $email     = $_POST['email']        ?? '';
        $bureau    = $_POST['bureau']       ?? '';
        $tel       = $_POST['telephone']    ?? '';
        $spec      = $_POST['specialite']   ?? '';
        $statut    = $_POST['statut']       ?? 'titulaire';

        if ($loginEns !== '' && $pwdEns !== '' && $nom !== '' && $prenom !== '') {
            $db->addEnseignant(
                $loginEns,
                $pwdEns,
                $nom,
                $prenom,
                $email,
                $bureau,
                $tel,
                $spec,
                $statut
            );
        }

        header('Location: admin.php?action=liste_enseignants');
        exit;
    }

    // Création d'un nouvel étudiant
    if ($actionPost === 'create_etudiant') {
        $loginEtu = $_POST['login']           ?? '';
        $pwdEtu   = $_POST['mot_de_passe']    ?? '';
        $nom      = $_POST['nom']             ?? '';
        $prenom   = $_POST['prenom']          ?? '';
        $email    = $_POST['email']           ?? '';
        $numEtu   = $_POST['numero_etudiant'] ?? '';
        $niveau   = $_POST['niveau']          ?? 'L1';

        if ($loginEtu !== '' && $pwdEtu !== '' && $nom !== '' && $prenom !== '' && $numEtu !== '') {
            $db->addEtudiant(
                $loginEtu,
                $pwdEtu,
                $nom,
                $prenom,
                $email,
                $numEtu,
                $niveau
            );
        }

        header('Location: admin.php?action=liste_etudiants');
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