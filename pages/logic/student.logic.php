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

$action = $_GET['action'] ?? 'liste_cours';

// On gère l'inscription à un cours
if ($action === 'inscription_cours') {
  $coursId = filter_input(INPUT_GET, 'cours_id', FILTER_VALIDATE_INT);

  if ($coursId){
    try {
      if ($db -> addEnrollment ($etudiantId, $coursId)){
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

  header('Location: student.php?action=liste_cours');
  exit;
}

//Variables d'affichage
$cours = [];
$coursDejaSuivis = [];

if ($action === 'liste_cours') {
  $cours = $db->getAllCourses();
  $coursDejaSuivis = $db -> getCoursInscritByStudent($etudiantId);
}

if ($action === 'liste_enseignements') {
  $coursDejaSuivis = $db -> getCoursInscritByStudent($etudiantId);
}

// Fonction pour vérifier le feedback
function hasFeedbackInSession(): bool {
    return isset($_SESSION['feedback']);
}
