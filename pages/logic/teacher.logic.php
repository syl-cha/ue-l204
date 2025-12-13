<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../functions.php';
require_once __DIR__ . '/../../classes/universite-db.class.php';

startSession();

if (!isConnecte()) {
  header('Location: ../index.php');
  exit;
}

// Sécurité : seuls les enseignants peuvent accéder à cette page
if (!isTeacher()) {
  header('Location: ../accueil.php');
  exit;
}

// On instancie notre classe d'accès BDD
$db = new UniversiteDB();

// On récupère le login et le rôle (principalement pour l’affichage)
$login = $_SESSION['login'] ?? '';
$role  = $_SESSION['role']  ?? '';

// TRAITEMENT DES ACTIONS GET 

$action = $_GET['action'] ?? null;
$coursId = $_GET['cours_id'] ?? null;

$cours = [];
$coursDejaEnseignes = [];
$prerequis = [];
$etudiant = [];

if ($action === 'liste_cours' || $action === 'liste_enseignements') {
  $cours = $db->getAllCourses();
  // récupération des enseignement de l'enseignant
  $enseignantConnecte = $db->getTeacherByLogin($login);
  if ($enseignantConnecte) {
    $coursDejaEnseignes = $db->getTeachedCoursesId($enseignantConnecte['id']);
  }
}

if ($action === 'liste_prerequis') {
  $prerequis = $db->getCoursePrerequisites((int)$coursId);
}

if ($action === 'liste_etudiant') {
  $etudiant = $db->getStudentsByCourse((int)$coursId);
}

if ($action === 'enseigner_cours' && $coursId) {
  $enseignant = $db->getTeacherByLogin($login);
  if ($enseignant) {
    $infosCours = $db->getCourseById($coursId);
    if ($infosCours) {
      $succes = $db->addTeaching(
        (int)$enseignant['id'],
        (int)$infosCours['id'],
        $infosCours['annee_universitaire']
      );
      if ($succes) {
        $_SESSION['feedback'] = ['message' => 'Vous enseignez à présent en '. $infosCours['code'] . " : " . $infosCours['nom']. '.', 'success' => true];
      } else {
        $_SESSION['feedback'] = ['message' => 'Impossible de s\'inscrire. Enseignez-vous déjà en '. $infosCours['code'] . " : " . $infosCours['nom'] . ' ?', 'success' => false];
      }
    } else {
        $_SESSION['feedback'] = ['message' => 'Le cours auquel vous tenter de vous inscrire est introuvable.', 'success' => false];
    }
  } else {
        $_SESSION['feedback'] = ['message' => 'Enseignant introuvable.', 'success' => false];
  }
  header('Location: ../views/teacher.php?action=liste_cours');
    exit;
}

// TRAITEMENT DES ACTIONS POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $actionPost = $_POST['action'] ?? '';

  if ($actionPost === 'add_course') {
    $courseCode = $_POST['course-code'] ?? null;
    $courseName = $_POST['course-name'] ?? null;
    $courseCredits = (int)($_POST['course-credits'] ?? 0);
    $courseDescription = $_POST['course-description'] ?? null;
    $courseYear = $_POST['course-year'] ?? null;
    $courseCapacity = (int)($_POST['course-capacity'] ?? 0);
    $courseYear = $_POST['course-year'] ?? null;
    $coursePrerequisites = $_POST['course-prerequisites'] ?? null;
    // traitement de la saisie des prérequis
    if (trim($coursePrerequisites) === '') {
      // ques des espaces dans le champ des prérequis
      $preCodesClean = [];
    } else {
      $preCodesRaw = explode(',', $coursePrerequisites);
      $preCodesTrimmed = array_map('trim', $preCodesRaw);
      $preCodesClean = array_values(array_filter(array_map('strtoupper', $preCodesTrimmed)));
    }
    if ($db->addCourse(
      $courseCode,
      $courseName,
      $courseCredits,
      $courseDescription,
      $courseYear,
      $courseCapacity,
      $preCodesClean
    )) {
      $_SESSION['feedback'] = [
        'message' => 'Le cours a été ajouté',
        'success' => true,
      ];
      header('Location: ../views/teacher.php');
      exit;
    } else {
      $_SESSION['feedback'] = [
        'message' => 'Erreur lors de l\'ajout du cours. Vérifier les champs',
        'success' => false
      ];
      header('Location: ../views/teacher.php?action=creer_cours');
      exit;
    }
  }
}
