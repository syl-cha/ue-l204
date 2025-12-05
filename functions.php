<?php
// Session

function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// BDD (PDO)

function getPDO(): PDO {
    $host = 'localhost';
    $dbname = 'universite1';
    $user = 'root';
    $pass = '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}

// Connexion

/* Récupère un utilisateur par son login (table `utilisateur`) */
function getUserByLogin(PDO $pdo, string $login): ?array {
    $sql = "SELECT id, login, mot_de_passe, role
            FROM utilisateur 
            WHERE login = :login";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch();

    return $user ?: null;
}

/* Connecte un utilisateur si le login et le mot de passe sont corrects */
function loginUser(PDO $pdo, string $login, string $password): bool {
    $user = getUserByLogin($pdo, $login);
    // utilisateur introuvable
    if (!$user) {
        return false;
    }
    // mot de passe incorrect
    if (!password_verify($password, $user['mot_de_passe'])) {
        return false;
    }
    // Si ok, on enregistre la session
    setConnecte($user);
    return true;
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


/*** Déconnexion et redirection vers la page de connexion ***/
function logout(): void {
    startSession();
    session_unset();
    session_destroy();
    header('Location: ../index.php');
    exit;
}
