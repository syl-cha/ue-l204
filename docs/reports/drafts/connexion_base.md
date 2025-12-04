# Connexion à la base de données

Nous avons mis en place un système de connexion sécurisé permettant d’accéder à l’espace étudiant/enseignant. Pour l'instant, on peut s'y connecter et accéder à la page qui permettra aux utilisateurs selon leurs rôles d'effectuer diverses actions la recherche ou la modification dans la BDD. Cette page est en attente de développement : seul un bouton de déconnexion est présent, qui renvoie à une page "deconnexion.php" qui détruit la session et puis redirige vers l'index.php.


## Principe

1. Mise en place de la page de connexion et de déconnexion

Création et mise en forme d'une page index.php contenant un formulaire de connexion (HTML/CSS) :
- Saisie de l’identifiant et du mot de passe
- Vérification du remplissage des champs
- Affichage d’un message d’erreur si les données entrées sont incorrectes (voir photo)
- Si connecté -> "accueil.php" avec bouton de déconnexion -> "deconnexion.php" et redirection vers l'index.php

![Page de connexion](index.png)

2. Gestion sécurisée de la base de données
- Création d’une connexion PDO dans functions.php qui permet de stocker les variables nécessaires permettant de se connecter à MySQL, de gérer les erreurs et de récupérer les résultats dans un tableau associatif.
```php
function getPDO(): PDO {
    $host = 'localhost';
    $dbname = 'universite1';
    $user = 'root';
    $pass = 'root';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
```
- Utilisation de requêtes préparées pour éviter les injections SQL
- Vérification des utilisateurs via la table utilisateur
- Mise en place de sessions via des fonctions (setConnecte() → enregistre l’utilisateur connecté ; isConnecte() → vérifie l’état de connexion)

```php
function setConnecte(array $user): void {
    startSession();
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['login']   = $user['login'];
    $_SESSION['role']    = $user['role'];
}

function isConnecte(): bool {
    startSession();
    return isset($_SESSION['user_id']);
}
```

3. Page d’accueil protégée
- La page pages/accueil.php n’est accessible que si l’utilisateur est connecté, sinon redirection automatique
