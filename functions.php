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
function redirectByRole(): array {
    startSession();

    //Si pas de rôle, redirigé vers la page de connexion
    if (!isset($_SESSION['role'])) {
        header('Location: ../index.php');
        exit;
    }

    switch($_SESSION['role']) {
        case 'admin':
            require_once __DIR__ . '/pages/logic/admin.logic.php';
            return [
              'espace' => 'administration',
              'tab' => 'Admin',
              'accueil' => 'administrateur',
              'message' => 'Administration des utilisateurs'
            ];
            break;
        
        case 'enseignant':
            require_once __DIR__ . '/pages/logic/teacher.logic.php';
            return [
              'espace' => 'enseignant',
              'tab' => 'Enseignant',
              'accueil' => 'enseignant',
              'message' => 'Administration des cours et élèves'
            ];
            break;

        case 'etudiant':
            require_once __DIR__ . '/pages/logic/student.logic.php';
            return [
              'espace' => 'étudiant',
              'tab' => 'Étudiant',
              'accueil' => 'étudiant',
              'message' => 'Gestion de vos cours'
            ];
            break;
        
        //Si rôle inconnue -> déconnexion
        default:
            header('Location: deconnexion.php');
            return [];
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

/*********** VERIFICATIONS DES FORMULAIRES (ADMIN) ***********/

function post_has(string $key): bool {
    return isset($_POST) && is_array($_POST) && array_key_exists($key, $_POST);
}

function post_str(string $key): ?string {
    if (!post_has($key)) return null;
    $v = $_POST[$key];
    if (!is_string($v)) return null;
    $v = trim($v);
    return ($v === '') ? null : $v;
}

/**
 * Nettoyage “affichage” : on garde la valeur pour la réafficher dans le formulaire.
 * (à utiliser dans value="..." dans le HTML)
 */
function esc(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/**
 * Nettoyage “stockage” : tu peux décider de stocker tel quel (après trim),
 * mais au minimum on évite les caractères invisibles.
 */
function clean_text(string $s): string {
    $s = trim($s);
    $s = preg_replace('/\s+/', ' ', $s);
    return $s;
}

/**
 * Validation générique : ajoute un message d'erreur dans $errors si KO.
 * Retourne la valeur validée (string) ou null si erreur.
 */
function validate_required_text(array &$errors, string $key, string $label, int $minLen = 3, int $maxLen = 32): ?string {
    $v = post_str($key);
    // Champ obligatoire
    if ($v === null || trim($v) === '') {
        $errors[$key] = "Le champ \"$label\" est obligatoire.";
        return null;
    }
    $v = trim($v);
    // Longueur
    if (mb_strlen($v) < $minLen || mb_strlen($v) > $maxLen) {
        $errors[$key] = "Le champ \"$label\" doit contenir entre $minLen et $maxLen caractères.";
        return null;
    }
   
    return clean_text($v);
}

function validate_email_required(array &$errors, string $field, string $label): ?string
{
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $errors[$field] = "Le champ \"$label\" est obligatoire.";
        return null;
    }

    $email = trim($_POST[$field]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[$field] = "Le champ \"$label\" doit contenir une adresse email valide.";
        return null;
    }

    return $email;
}

/**
 * Téléphone FR : 10 chiffres, commence par 0.
 * Accepte espaces, points, tirets. Exemple: 06 12 34 56 78
 */
function validate_phone_fr_required(array &$errors, string $key, string $label): ?string
{
    if (!isset($_POST[$key]) || trim($_POST[$key]) === '') {
        $errors[$key] = "Le champ \"$label\" est obligatoire.";
        return null;
    }

    $v = trim($_POST[$key]);

    if (!is_string($v)) {
        $errors[$key] = "Le champ \"$label\" est invalide.";
        return null;
    }

    // Normalisation : suppression espaces, points, tirets
    $normalized = preg_replace('/[\s\.\-]/', '', $v);

    // Numéro français : 10 chiffres commençant par 0
    if (!preg_match('/^0\d{9}$/', $normalized)) {
        $errors[$key] = "Le champ \"$label\" doit contenir un numéro français valide (10 chiffres commençant par 0).";
        return null;
    }

    return $normalized;
}

function validate_enum_required(array &$errors, string $key, string $label, array $allowed): ?string {
    $v = post_str($key);
    if ($v === null) {
        $errors[$key] = "Le champ \"$label\" est obligatoire.";
        return null;
    }
    if (!in_array($v, $allowed, true)) {
        $errors[$key] = "La valeur sélectionnée pour \"$label\" est invalide.";
        return null;
    }
    return $v;
}

/**
 * Login simple : 3..32, lettres/chiffres/._-
 * (adapte si vous voulez autre chose)
 */
function validate_login_required(array &$errors, string $key, string $label): ?string {
    $v = post_str($key);
    if ($v === null) {
        $errors[$key] = "Le champ \"$label\" est obligatoire.";
        return null;
    }
    if (!preg_match('/^[a-zA-Z0-9._-]{3,32}$/', $v)) {
        $errors[$key] = "Le champ \"$label\" doit faire 3 à 32 caractères (lettres, chiffres, . _ -).";
        return null;
    }
    return $v;
}

/**
 * Numéro étudiant : exemple (à adapter)
 * Ici : 6 à 20 caractères alphanum + tirets.
 */
function validate_numero_etudiant_required(array &$errors, string $key, string $label): ?string {
    $v = post_str($key);
    if ($v === null) {
        $errors[$key] = "Le champ \"$label\" est obligatoire.";
        return null;
    }
    if (!preg_match('/^[A-Za-z0-9-]{6,20}$/', $v)) {
        $errors[$key] = "Le champ \"$label\" est invalide (6 à 20 caractères, lettres/chiffres/tirets).";
        return null;
    }
    return $v;
}


function validate_bureau_required(array &$errors, string $field, string $label): string
{
    $value = strtoupper(trim($_POST[$field] ?? ''));

    if ($value === '') {
        $errors[] = "$label est obligatoire.";
        return '';
    }

    // Exactement 4 caractères : A–Z et 0–9 uniquement
    if (!preg_match('/^[A-Z0-9]{4}$/', $value)) {
        $errors[] = "$label doit contenir exactement 4 caractères (lettres majuscules et chiffres).";
        return '';
    }

    return $value;
}
