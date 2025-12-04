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


## Difficultés rencontrées / pas encore résolues

- Prise en main de la bdd avec PDO : configuration PDO différente selon Windows / Linux, activation des extensions PDO MySQL ou du serveur Apache - Résolue

- Problème : les mots de passe fournis dans la BDD ne correspondaient pas au hash réel de "123456". Nous avons fait des tests avec password_hash() et password_verify() → découverte que le hash était invalide → remplacement des mots de passe hachés directement en BDD. - Résolue


- Gestion des chemins relatifs entre les pages (index.php, pages/accueil.php, pages/deconnexion.php) : erreurs 404 ou redirections incorrectes à cause de chemins mal construits - Résolue

- Manque de temps personnel pour cette semaine pour la plupart d'entre nous à causes de raisons personnelles - A adapter en deuxième semaine.



## Projections pour la 2e semaine


1. Les tâches restantes :
Notre but en deuxième semaine sera de mettre en place les fonctionnalités liées aux étudiants et aux professeurs une fois connectés, et de perfectionner le système de gestion de connexion et des rôles :
- Finaliser le système de connexion : ajouter un bouton déconnexion sur chaque page et améliorer si besoin

- Créer la page d’accueil (affiche différentes fonctionnalités selon le rôle de l'utilisateur connecté)

- Créer une page listant des données depuis la BDD

- Créer une page d’ajout (INSERT INTO) pour un nouveau cours, ou encore un nouvel étudiant

- Vérification poussée des données utilisateurs

- Gestion des rôles. Exemple : redirection automatique si un étudiant tente d’accéder à une page admin

- Tester en continu

- Rédiger le rapport final


2. L'organisation du groupe :
- Sylvain : Création du GitHub et de la base de données + Mise en forme du rapport final
- Zoé : Réflexion sur le scénario du projet et des fonctionnalités
- Jeanne : Création de la page de connexion et des fonctions utiles à la connexion/déconnexion
- Jade : Réflexion sur le projet et ses fonctionnalités

Tâches communes :
- Rédaction du rapport intermédaire
- Mise en commun des idées
- Communication continue

