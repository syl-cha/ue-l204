<?php

function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/* Enregistre les données de l'utilisateur dans la session */
function setConnecte(array $user): void {
    startSession();
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['login']   = $user['login'];
    $_SESSION['role']    = $user['role'];

    if ($user['role'] === 'etudiant') {
        require_once __DIR__ . '/classes/universite-db.class.php';
        $db       = new UniversiteDB();
        $etudiant = $db->getEtudiantByUtilisateurId($user['id']);

        if ($etudiant) {
            $_SESSION['etudiant_id'] = (int) $etudiant['id_etudiant'];
            $_SESSION['numero_etudiant'] = $etudiant['numero_etudiant'];
        }
    }
}

/*** On vérifie si l'utilisateur est déjà connecté (retourne vrai ou faux) ***/
function isConnecte(): bool {
    startSession();
    return isset($_SESSION['user_id']);
}

/* On vérifie si l'utilisateur connecté est admin */
function isAdmin(): bool {
    startSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}


/* On vérifie si l'utilisateur connecté est enseignant */
function isTeacher(): bool {
    startSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'enseignant';
}


/* On vérifie si l'utilisateur connecté est enseignant */
function isStudent(): bool {
    startSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'etudiant';
}

/* Redirige l'utilisateur sur sa page d'accueil en fonction de son rôle*/
function redirectByRole(): void {
    startSession();

    //Si pas de rôle, redirigé vers la page de connexion
    if (!isset($_SESSION['role'])) {
        header('Location: ../index.php');
        exit;
    }

    switch($_SESSION['role']) {
        case 'admin':
            header('Location: views/admin.php');
            break;
        
        case 'enseignant':
            header('Location: views/teacher.php');
            break;

        case 'etudiant':
            header('Location: views/student.php');
            break;
        
        //Si rôle inconnue -> déconnexion
        default:
            header('Location: deconnexion.php');
            exit;
    }
}

/*** Déconnexion et redirection vers la page de connexion ***/
function logout(): void {
    startSession();
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}

function hasFeedbackInSession(){
	if($_SESSION 
		&& count($_SESSION) 
			&& array_key_exists('feedback', $_SESSION)
				&& gettype($_SESSION['feedback']) === 'array'
					&& count($_SESSION['feedback'])){
		return true;
	}else{
		return false;
	}
}
