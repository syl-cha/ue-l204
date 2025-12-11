<?php
// Session

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


/*** Déconnexion et redirection vers la page de connexion ***/
function logout(): void {
    startSession();
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}
