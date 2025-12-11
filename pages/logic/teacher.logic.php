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
$prerequis = [];

if ($action === 'liste_cours') {
  $cours = $db->getAllCourses();
}

if ($action === 'liste_prerequis') {
  $prerequis = $db->getCoursePrerequisites((int)$coursId);
}
