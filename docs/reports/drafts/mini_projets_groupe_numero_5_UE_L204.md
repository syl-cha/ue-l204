# RAPPEL DES CONSIGNES POUR LE PROJET

À la fin du travail du mini-projet (le dimanche suivant à 23h55), vous devrez rendre les
documents suivants :
— Un export de votre BDD et de tous les fichiers liés au site (fichiers php des différentes pages et éventuels fichiers annexes : style css, fonctions php, ...)
— Un document de synthèse sur l’ensemble du projet (le document de mi-parcours
pourra bien sûr servir de base pour cette synthèse). Ce document devra contenir un
paragraphe détaillé (et un tableau ?) qui décrit la contribution de chaque étudiant
du groupe aux différentes étapes du travail réalisé.
— Un fichier contenant les identifiants de connexion à la base de données.
Attention, au moment du dépôt final, la BDD fournie aura été complétée et comportera
au moins 30 entrées différentes. Il est à noter également que la connexion avec votre BDD
devra être réalisée à l’aide d’un mot de passe qui ne sera pas stocké en clair !
Pensez à vérifier cette liste avant de rendre votre travail.

Il est demandé de mettre en place obligatoirement les pages (et fonctionnalités associées) suivantes :

1. une page de connexion (formulaire d’authentification vérifiant la présence de l’utilisateur en base de données à l'aide d'un mot de passe crypté)
2. une page listant les différents résultats associés à une recherche dans la base de données
3. une page permettant d'enrichir la base de données en ajoutant de nouveaux contenus
4. une vérification poussée des données utilisateurs fournies avant tout accès à la BDD
5. deux rôles définis : un rôle "administrateur" et un rôle "utilisateur"
6. Il est obligatoire de réaliser des requêtes préparées via PDO pour interroger votre BDD. Il est donc interdit d'utiliser d'autres solutions (obsolètes et moins sécurisées) comme mysqli ou mysql_, etc.
7. Il est demandé de compléter chaque table avec au moins 20 entrées.
À noter que vous n’êtes pas obligé de partir de zéro : le travail effectué ces dernières semaines, et les exemples et corrections fournis, peuvent bien sûr servir d’inspiration.





# Première partie

## Projet

### Méthodes de travail et outils utilisés

- Teams pour la communication et le partage de fichiers;
- GitHub pour la mise en commun du code sur un dépôt et pouvoir mieux gérer
les modifications via le système de branche;
- Forum de groupe de l’UE : communication et retour sur l’avancement du projet;
- Word pour une rédaction commune et LaTeX pour la finalisation des rapports;
- Un système d’intelligence artificielle générationnelle (Gemini) pour générer les
entrées dans les tables.
- Diagram.net pour créer un modèle de notre future BDD et visualiser les tables et leurs connexions
- PhpMyAdmin pour la création des tables et la génération des entrées dans ces
table
- VS Code (ou autre éditeurs) : rédaction du code
- XAMPP sous Windows ou un stack LAMP sous Linux.

### Organisation du groupe 

#### Échanges synchrones et asynchrones

- 1 réunion synchrone par semaine (soit 2 en tout) pour tout le groupe, et autres réunions synchrones pour faire des points d'avancement sur certaines parties du projet, expliquer des changements, etc.
- Communication continue tout au long du projet, points d'étapes réguliers entre nous.

#### Répartition des tâches 

Afin d’assurer une progression efficace et structurée du projet, les tâches ont été réparties au sein du groupe en fonction des différents domaines à couvrir. Chaque membre a pris en charge une ou plusieurs parties spécifiques du projet, tout en participant activement aux réflexions communes et aux décisions globales. Cette organisation a permis de travailler efficacement tout en garantissant la cohérence et l’évolution continue du projet.

| **Tâches** | **Assignation** |
|:-------- |:--------:|
| Création du Github et de la BDD | Sylvain Chambon |
| Mise à jour des idées et de l'avancement du projet | Sylvain Chambon |
| Mise en forme des rapports sur LaTeX | Sylvain Chambon |
| Scénario du projet | Zoe Van De Moortele |
| Gestion de l'admin et des sessions | Jeanne Salvadori |
| Gestion des enseignants | Sylvain Chambon |
| Gestion des étudiants | Zoe Van De Moortele |
| Mise en forme CSS | Jade Faroux |
| Création du support de présentation | Jade Faroux |
| Création du rapport final | Jeanne Salvadori |

| Tâches communes | Assignation |
|:-------- |:--------:|
| Création, reflexion sur le projet et ses fonctionnalités | * |
| Amélioration continue, apport d'idées | * |
| Communication continue,  | * |

*( * = l'ensemble du groupe)*

#### Difficultés rencontrées / Pas encore résolues

- Prise en main de la BDD avec PDO : configuration PDO différente selon Windows
/ Linux, activation des extensions PDO MySQL ou du serveur Apache.
Check-square Résolue
- Problème : les mots de passe fournis dans la BDD ne correspondaient pas au
hash réel de ”123456”. Nous avons fait des tests avec password_hash() et password_verify() ⟶découverte que le hash était invalide⟶remplacement des mots de passe
hachés directement en BDD.
Check-square Résolue
- Gestion des chemins relatifs entre les pages (index.php, pages/accueil.php, pages/deconnexionerreurs 404 ou redirections incorrectes à cause de chemins mal construits)
Check-square Résolue
- Hashages réalisés par l’IA pour les utilisateurs fictifs se sont avérés faux. NOus
avons dû les refaire à l’aide de commande PHP.
Check-square Résolue
- Manque de temps personnel pour la plupart d’entre nous à causes de situations personnelles ou professionnelles.

# Deuxième partie

## Code, techniques et choix de développement

Depuis le début du projet, nous avons progressivement fait évoluer l’architecture et le code afin de gagner en lisibilité, en sécurité et en maintenabilité. Le projet a d’abord été développé avec des fonctionnalités essentielles, puis celles-ci ont été progressivement améliorées et complétées au fil du temps.

1. **Arborescence**

Choix d'une arborescence claire :
- Le dossier `assets` regroupe tout ce qui concerne la mise en forme et les ressources statiques (CSS, images, polices).
- Le dossier  `classes` contient les classes PHP, notamment celles dédiées à l’accès à la base de données et à la centralisation des requêtes SQL.
- Le dossier `docs` est utilisé pour stocker des documents de travail, notamment des fichiers Markdown servant à noter les idées, les pistes d’amélioration et l’avancement du projet.
- Le dossier `pages` est structuré en sous-dossiers afin de séparer la logique applicative (logic) de l’affichage (views). Cette séparation, faite en deuxième semaine, permet de rendre le code plus lisible et plus facile à maintenir.
- Au premier niveau du projet, on retrouve les fichiers `index.php`, `config.php`, `functions.php` ainsi que les scripts SQL de la base de données, qui centralisent la configuration, l’initialisation et les fonctions communes utilisées par l’ensemble des pages.

2. **Fonctionnalités**

Au fil du développement, plusieurs fonctionnalités majeures ont été implémentées : 
- Un système d’**authentification** permettant de différencier les utilisateurs **selon leur rôle** (administrateur, enseignant, étudiant). En fonction de ce rôle, l’utilisateur est redirigé vers des pages spécifiques (`admin.php`, `teacher.php` et `student.php`) et n’a accès qu’aux fonctionnalités qui le concernent.
- L’**espace administrateur** permet de gérer les utilisateurs : lister les enseignants et les étudiants, modifier leurs informations, les supprimer, ou encore en ajouter via des formulaires dédiés.
- L’**espace enseignant** propose quant à lui des fonctionnalités liées aux cours, comme la consultation du catalogue, la création de nouveaux cours avec gestion des prérequis, et des actions préparatoires pour l’enseignement ou la suppression de cours.
- L’**espace éudiant** propose des fonctionnalités qui permettent de lister tous les cours de l'université, de pouvoir s'y inscrire et ensuite de pouvoir visualiser ses propres cours. Par contre, certains cours nécessitent des prérequis à l'inscription. Exemple : si on veut s'inscrire à INFO-L204, il faudra avoir suivi le cours INFO-L104.
- Un fichier de log `database-errors.log` est également utilisé afin de **tracer les erreurs liées à la base de données**, ce qui facilite le débogage et l’analyse des problèmes côté serveur.

3. **BDD**

La base de données a été pensée de manière relationnelle, avec une table centrale utilisateur et des tables spécifiques pour les enseignants, les étudiants et les cours.
Les opérations sensibles (ajout d’un enseignant ou d’un étudiant) sont réalisées à l’aide de transactions afin de garantir la cohérence des données. En cas d’erreur, les modifications sont annulées et l’erreur est enregistrée dans les logs.
L’utilisation de clés étrangères permet également de gérer automatiquement certaines suppressions grâce aux mécanismes de cascade.

4. **Codes**

- **Chargement des dépendances et structure commune**

Les pages reposent sur l’utilisation systématique de `require_once` pour charger les fichiers nécessaires (configuration, fonctions communes, logique métier).
Cela permet d’éviter les redéfinitions multiples et garantit que toutes les dépendances sont disponibles avant l’exécution du code.

```php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../functions.php';
require_once __DIR__ . '/../../classes/universite-db.class.php';
```

- **Gestion des sessions et contrôle d’accès**

La gestion des sessions est centralisée dans functions.php.
Chaque page sensible vérifie que l’utilisateur est connecté et qu’il possède le rôle adéquat avant de continuer.

```php
startSession();

if (!isConnecte()) {
    header('Location: ../index.php');
    exit;
}

if (!isAdmin()) {
    header('Location: ../accueil.php');
    exit;
}
```

Ce mécanisme empêche l’accès direct aux pages via l’URL et renforce la sécurité côté serveur.

- **Centralisation de l’accès à la base de données**

Toutes les requêtes SQL sont regroupées dans une classe dédiée (UniversiteDB).
Les pages appellent uniquement des méthodes métier, sans manipuler directement PDO.

```php
$db = new UniversiteDB();
$enseignants = $db->getAllEnseignants();

public function getAllEnseignants(): array {
    $stmt = $this->connect()->query("SELECT * FROM enseignant_view");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

- **Utilisation des transactions et gestion des erreurs**

Les opérations critiques utilisent des transactions afin de garantir la cohérence des données.
En cas d’erreur, la transaction est annulée et l’erreur est enregistrée dans un fichier de log.

```php
$pdo->beginTransaction();

try {
    // Insertion utilisateur
    // Insertion enseignant
    $pdo->commit();
    return true;
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log($e->getMessage(), 3, ERROR_LOG_PATH);
    return false;
}
```

- **Validation des formulaires côté serveur**

Les formulaires sont validés côté serveur à l’aide de fonctions dédiées.
Cela garantit l’intégrité des données, même si les contrôles HTML sont contournés.

```php
$email = validate_email_required($errors, 'email', 'Email');
$nom   = validate_required_text($errors, 'nom', 'Nom', 1, 32);

function validate_email_required(array &$errors, string $field, string $label): ?string {
    if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
        $errors[] = "$label obligatoire.";
        return null;
    }

    if (!filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "$label invalide.";
        return null;
    }

    return htmlspecialchars($_POST[$field], ENT_QUOTES, 'UTF-8');
}
```

- **Réutilisabilité grâce aux fonctions de validation**

Les fonctions de validation sont génériques et réutilisables dans l’ensemble du projet.
Elles sont utilisées aussi bien lors de la création que de la modification des utilisateurs.

```php
function validate_required_text(
    array &$errors,
    string $key,
    string $label,
    int $minLen,
    int $maxLen
): ?string {
    $v = trim($_POST[$key] ?? '');
    if ($v === '') {
        $errors[$key] = "$label obligatoire.";
        return null;
    }
    if (mb_strlen($v) < $minLen || mb_strlen($v) > $maxLen) {
        $errors[$key] = "$label doit contenir entre $minLen et $maxLen caractères.";
        return null;
    }
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
```

- **Système d’actions pour la gestion des pages**

Les pages utilisent un paramètre action pour déterminer le traitement à effectuer.
Cela permet de centraliser la logique dans un seul fichier par espace utilisateur.

```php
$action = $_GET['action'] ?? null;

if ($action === 'liste_enseignants') {
    $enseignants = $db->getAllEnseignants();
}

if ($action === 'edit_enseignant') {
    $enseignantCourant = $db->getEnseignantById((int)$_GET['id']);
}
```

- **Séparation logique / affichage**

La logique applicative est séparée de l’affichage HTML.
Les fichiers *.logic.php contiennent les traitements PHP, tandis que les views se concentrent sur l’interface.

```php
// admin.logic.php
$enseignants = $db->getAllEnseignants();

<!-- admin.php -->
<?php foreach ($enseignants as $e): ?>
<tr>
    <td><?= htmlspecialchars($e['nom']) ?></td>
    <td><?= htmlspecialchars($e['prenom']) ?></td>
</tr>
<?php endforeach; ?>
```

- **Mécanisme de feedback utilisateur**

Un système de feedback basé sur la session permet d’informer l’utilisateur du résultat des actions effectuées.

```php
$_SESSION['feedback'] = [
    'message' => 'Utilisateur ajouté avec succès',
    'success' => true
];

<?php if (hasFeedbackInSession()): ?>
    <p class="<?= $_SESSION['feedback']['success'] ? 'success' : 'warning' ?>">
        <?= htmlspecialchars($_SESSION['feedback']['message']) ?>
    </p>
<?php unset($_SESSION['feedback']); endif; ?>
```

- **Authentification sécurisée côté serveur**

Mise en place d’une authentification robuste côté serveur. D’une part, la récupération de l’utilisateur s’appuie sur une requête préparée PDO avec paramètre nommé (:login), ce qui empêche les injections SQL en évitant toute concaténation directe de données utilisateur dans la requête. D’autre part, la vérification du mot de passe repose sur password_verify(), qui compare un mot de passe saisi à un hash stocké en base, sans jamais manipuler ou stocker le mot de passe en clair.

```php
public function getUserByLogin(string $login): ?array
{
  $sql = "SELECT id, login, mot_de_passe, role
          FROM utilisateur 
          WHERE login = :login";

  $stmt = $this->connect()->prepare($sql);
  $stmt->execute([':login' => $login]);
  $user = $stmt->fetch();

  return $user ?: null;
}

public function goodLoginPasswordPair(string $login, string $password): bool
{
  $user = $this->getUserByLogin($login);
  if (!$user) return false;

  if (!password_verify($password, $user['mot_de_passe'])) {
    return false;
  }
  return true;
}
```

- **Ajout d’un cours : transaction + gestion des prérequis**

Choix important pour garantir l’intégrité de la base de données : l’utilisation de transactions lors d’opérations complexes. L’ajout d’un cours peut entraîner plusieurs insertions (création du cours + insertion des prérequis). En encapsulant l’ensemble dans une transaction (beginTransaction, commit, rollBack), le système garantit un comportement “tout ou rien” : si un prérequis est invalide (ex. code inexistant), la transaction est annulée et la base reste dans un état cohérent, sans cours partiellement créé.

```php
$pdo->beginTransaction();

$stmtCours = $pdo->prepare($sqlCours);
$stmtCours->execute([
  ':code'    => $code,
  ':nom'     => $nom,
  ':credits' => $credits,
  ':desc'    => $description,
  ':cap'     => $capaciteMax,
  ':annee'   => $annee
]);

// Ajout des prérequis (si présents)...
foreach ($prerequisCodes as $prerequisCode) {
  $stmtGetId->execute([':code_prerequis' => $prerequisCode]);
  $prerequisId = $stmtGetId->fetchColumn();
  if ($prerequisId == false) { $succes = false; break; }

  $stmtInsertPrerequis->execute([
    ':cours_id' => $nouveauCoursId,
    ':prerequis_cours_id' => $prerequisId
  ]);
}

if ($succes) {
  $pdo->commit();
} else {
  $pdo->rollBack();
}
```

- **Inscription d’un étudiant : contrôle métier “prérequis” + exception explicite**

Cette partie met en avant l’intégration de règles métier directement dans la couche d’accès aux données. Avant l’inscription, le code vérifie les prérequis via une requête dédiée (getMissingPrerequisites). Si des prérequis ne sont pas validés, le système bloque l’opération et déclenche une exception explicite, ce qui permet de remonter un message clair côté interface (feedback). Cette séparation entre “contrôle métier” (prérequis) et “action technique” (INSERT) améliore la fiabilité et évite que des inscriptions incohérentes soient enregistrées en base.

```php
$missing = $this->getMissingPrerequisites($etudiantId, $coursId);
if (!empty($missing)) {
  $missingCodes = array_column($missing, 'code');
  $missingCodesStr = implode(', ', $missingCodes);

  throw new Exception('Inscription impossible : manquent la validation de ' . $missingCodes);
}

$sql = "INSERT INTO inscription (etudiant_id, cours_id) VALUES (:etudiant_id, :cours_id)";
$stmt = $this->connect()->prepare($sql);
$stmt->execute([
  ':etudiant_id' => $etudiantId,
  ':cours_id' => $coursId
]);
```

## Pour aller plus loin

- Mécanisme de feedback à améliorer : actuellement pas de retour précis sur les erreurs
- Mécanisme de gestion de durée de connexion
- Vérification sur les champs des formulaires (à perfectionner)
- Mots de passe provisoires
- Les demandes d'inscription (des élèves) et de création de cours (des enseignants) doivent rester en attente de validation de l'admin.
- Gestion des notes
- Possibilité pour les utilisateurs de changer des données personnelles.