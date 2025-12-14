<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../functions.php';
require_once __DIR__ . '/../../classes/universite-db.class.php';

startSession();

// Sécurité : seuls les élèves peuvent accéder à cette page
if (!isConnecte() || !isStudent()) {
  header('Location: ../index.php');
  exit;
}

// On récupère le login, l'id et le rôle (principalement pour l’affichage)
$login = $_SESSION['login'] ?? '';
$role  = $_SESSION['role']  ?? '';
$etudiantId = $_SESSION['etudiant_id'] ?? null;

if (!$etudiantId) {
  die("Erreur : ID étudiant introuvable en session");
}

// On instancie notre classe d'accès BDD
$db = new UniversiteDB();

// TRAITEMENT DES ACTIONS GET 

// Formulaire de recherche des cours
$recherche_cours = '';
if (isset($_GET['search_cours']) and !empty(trim($_GET['search_cours']))) {
  $recherche_cours = htmlspecialchars(trim($_GET['search_cours']));
}

$action = $_GET['action'] ?? 'liste_cours';

// On gère l'inscription à un cours
if ($action === 'inscription_cours') {
  $coursId = filter_input(INPUT_GET, 'cours_id', FILTER_VALIDATE_INT);

  if ($coursId) {
    try {
      if ($db->addEnrollment($etudiantId, $coursId)) {
        $_SESSION['feedback'] = [
          'success' => true,
          'message' => 'Inscription réussie !'
        ];
      } else {
        $_SESSION['feesback'] = [
          'success' => false,
          'message' => 'Vous êtes déjà inscrit à ce cours'
        ];
      }
    } catch (Exception $e) {
      $_SESSION['feedback'] = [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  header('Location: accueil.php?action=liste_cours');
  exit;
}

//Variables d'affichage
$cours = [];
$coursDejaSuivis = [];

if ($action === 'liste_cours') {
  // Cas du formulaire de recherche
  if (!empty($recherche_cours)) {
    $cours = $db->searchCourses($recherche_cours);
  }

  //Cas du bouton "lister les cours"
  else {
    $cours = $db->getAllCourses();
  }

  $coursDejaSuivis = $db->getIdCoursInscritByStudent($etudiantId);

  $prerequisManquants = [];
  foreach ($cours as $c) {
    if (!in_array($c['id'], $coursDejaSuivis)) {
      $missingPrereq = $db->getMissingPrerequisites($etudiantId, $c['id']);
      if (!empty($missingPrereq)) {
        $prerequisManquants[$c['id']] = $missingPrereq;
      }
    }
  }
}

if ($action === 'liste_enseignements') {
  $coursDejaSuivis = $db->getCoursInscritByStudent($etudiantId);
}

//On gère la désinscription
if ($action === 'desinscription_cours') {
  $coursId = filter_input(INPUT_GET, 'cours_id', FILTER_VALIDATE_INT);

  if ($coursId) {
    try {
      if ($db->removeEnrollment($etudiantId, $coursId)) {
        $_SESSION['feedback'] = [
          'success' => true,
          'message' => 'Désinscription réussie !'
        ];
      } else {
        $_SESSION['feedback'] = [
          'success' => false,
          'message' => 'Erreur lors de la désinscription'
        ];
      }
    } catch (Exception $e) {
      $_SESSION['feedback'] = [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  header('Location: accueil.php?action=liste_enseignements');
  exit;
}
