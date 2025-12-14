<?php

/* 
Fonction qui démarre la session
"void" indique que la fonction ne retourne aucune valeur (pas besoin vu que c'est juste pour démarrer une session)
PHP_SESSION_NONE signifie qu’aucune session n’est actuellement active,
ce qui permet de lancer session_start() uniquement si nécessaire */
function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/* Enregistre les données de l'utilisateur dans la session*/
function setConnecte(array $user): void {
    startSession();
    $_SESSION['user_id'] = (int) $user['id']; // (int) force la conversion de la valeur en entier
    $_SESSION['login']   = $user['login'];
    $_SESSION['role']    = $user['role'];

    if ($user['role'] === 'etudiant') {
        require_once __DIR__ . '/classes/universite-db.class.php';
        $db       = new UniversiteDB();
        /* La "flèche" sert à appeler une méthode ou accéder à une propriété d’un objet :
        ici, on récupère les informations de l’étudiant associées à l’utilisateur connecté depuis la base de données */
        $etudiant = $db->getEtudiantByUtilisateurId($user['id']);
        // si l'utilisateur est un étudiant : on stocke ses infos en session pour y accéder facilement sans faire d'autres requêtes
        if ($etudiant) {
            $_SESSION['etudiant_id'] = (int) $etudiant['id_etudiant'];
            $_SESSION['numero_etudiant'] = $etudiant['numero_etudiant'];
        }
    }
}

/* On vérifie si l'utilisateur est déjà connecté (retourne vrai ou faux) */
function isConnecte(): bool {
    startSession();
    return isset($_SESSION['user_id']);
}

/* On vérifie si l'utilisateur connecté est admin (retourne vrai ou faux) */
function isAdmin(): bool {
    startSession();
    // On vérifie que la variable $_SESSION['role'] existe et qu’elle contient bien la valeur admin (pareil avec les autres fonctions suivantes)
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

    // Si le role n'est pas présent en session, on redirige vers la page de connexion
    if (!isset($_SESSION['role'])) {
        header('Location: ../index.php');
        exit;
    }

    // Avec switch, on détermine quel espace doit être affiché en fonction du rôle de la personne connectée : administrateur, enseignant ou étudiant
    switch($_SESSION['role']) {
        case 'admin':
            return [
              'espace'     => 'administration',
              'tab'        => 'Admin',
              'accueil'    => 'administrateur',
              'message'    => 'Administration des utilisateurs',
              // On retourne les chemins des fichiers
              'logic_path' => __DIR__ . '/pages/logic/admin.logic.php',
              'view_path'  => __DIR__ . '/pages/views/admin.php'
            ];
        
        case 'enseignant':
            return [
              'espace'     => 'enseignant',
              'tab'        => 'Enseignant',
              'accueil'    => 'enseignant',
              'message'    => 'Administration des cours et élèves',
              'logic_path' => __DIR__ . '/pages/logic/teacher.logic.php',
              'view_path'  => __DIR__ . '/pages/views/teacher.php'
            ];

        case 'etudiant':
            return [
              'espace'     => 'étudiant',
              'tab'        => 'Étudiant',
              'accueil'    => 'étudiant',
              'message'    => 'Gestion de vos cours',
              'logic_path' => __DIR__ . '/pages/logic/student.logic.php',
              'view_path'  => __DIR__ . '/pages/views/student.php'
            ];
        
        default:
            header('Location: deconnexion.php');
            exit;
    }
}

/*** Déconnexion et redirection vers la page de connexion (supprime toutes les variables
 *  stockées dans la session courante, détruit la session puis redirige ) ***/
function logout(): void {
    startSession();
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}

/* Cette fonction vérifie s’il existe un message de feedback valide dans la session 
(clé feedback présente, de type tableau et non vide) afin de savoir s’il faut l’afficher à l’utilisateur */
function hasFeedbackInSession(){
	if($_SESSION && count($_SESSION) && array_key_exists('feedback', $_SESSION) && gettype($_SESSION['feedback']) === 'array' && count($_SESSION['feedback'])){
		return true;
	}else{
		return false;
	}
}

/*********** VERIFICATIONS DES FORMULAIRES (ADMIN) ***********/

function post_has(string $key): bool { // vérifie si une clé donnée existe bien dans le tableau $_POST, renvoie vrai ou faux
    return isset($_POST) && is_array($_POST) && array_key_exists($key, $_POST);
}

// Récupère une valeur de $_POST, vérifie que c’est une chaîne non vide 
// et retourne null si elle est invalide
    if (!post_has($key)) return null;
function post_str(string $key): ?string {  // Peut retourner une chaîne de caractères, ou "null" si ça échoue
    $v = $_POST[$key];
    if (!is_string($v)) return null;
    $v = trim($v);
    return ($v === '') ? null : $v;
}

// Nettoyage “stockage” : évite les caractères invisibles
function clean_text(string $s): string {
    $s = trim($s);
    $s = preg_replace('/\s+/', ' ', $s);
    return $s;
}

// Validation générique : ajoute un message d'erreur dans $errors si KO
// Retourne la valeur validée (string) ou null si erreur
function validate_required_text(array &$errors, string $key, string $label, int $minLen = 3, int $maxLen = 32): ?string {
    $v = post_str($key);
    // Champ obligatoire
    if ($v === null || trim($v) === '') {
        $errors[$key] = "Le champ \"$label\" est obligatoire.";
        return null;
    }
    $v = trim($v);
    // Longueur
    if (mb_strlen($v) < $minLen || mb_strlen($v) > $maxLen) { // md_strlen compte le nombre de caractères (mêmes spéciaux ou accentués)
        $errors[$key] = "Le champ \"$label\" doit contenir entre $minLen et $maxLen caractères.";
        return null;
    }
   
    return clean_text($v);
}

// Validation du format de l'e-mail : présent et non vide, format valide -> retourne l'e-mail "nettoyé"
// sinon on ajoute un message d’erreur
function validate_email_required(array &$errors, string $field, string $label): ?string
{
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $errors[$field] = "Le champ \"$label\" est obligatoire.";
        return null;
    }

    $email = trim($_POST[$field]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Filtre la valeur de l'e-mail
        $errors[$field] = "Le champ \"$label\" doit contenir une adresse email valide.";
        return null;
    }

    return $email;
}

// Validation format tel français : 10 chiffres, commence par 0
// Accepte espaces, points, tirets, exemple : 06 12 34 56 78
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

// Login simple entre 3 et 32 caractères, lettres/chiffres/
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

// Numéro étudiant : exemple (à adapter)
// Ici : 6 à 20 caractères alphanum + tirets
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

// Validation du format du bureau (4 caractères, maj et chiffres)
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
