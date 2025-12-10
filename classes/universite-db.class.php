<?php
require('./classes/database.class.php');

/**
 * Extension de la classe Database gérant la logique spécifique
 * à la BDD de l'université
 * @package universite-db.class.php
 */
class UniversiteDB extends DataBase
{
  /**
   * Récupère un utilisateur par son login
   * @param string $login Le login de l'utilisateur
   * @return array|null L'utilisateur trouvé ou null si non
   */
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

  /**
   * Vérifie si un login matche un mot de passe
   * @param string $login Le login de l'utilisateur
   * @param string $password Le mot de passe de l'utilisateur
   * @return bool true si le couple login/mot correspond à celui de la BBD
   */
  public function goodLoginPasswordPair(string $login, string $password): bool
  {
    $user = $this->getUserByLogin($login);
    // utilisateur introuvable
    if (!$user) {
      return false;
    }
    // mot de passe incorrect
    if (!password_verify($password, $user['mot_de_passe'])) {
      return false;
    }
    return true;
  }

  /**
   * Ajoute un nouveau cours à la BDD de l'
   * université
   * @param string $code Le code du cours à ajouter
   * @param string $nom Le nom du cours à ajouter
   * @param int $credits Le nombre de crédits ECTS du cours
   * @param string $annee L'année universitaire du cours
   * @param string $description La description du cours
   * @param int $capaciteMax Le nombre d'élèves maximum pouvant assister au cours
   * @param array $prerequisCodes Un tableau de codes de cours prérequis
   * @return bool true si le cours a été ajouté, false si cela c'est mal passé
   */
  public function addCourse(
    string $code,
    string $nom,
    int $credits,
    string $description,
    string $annee,
    int $capaciteMax,
    array $prerequisCodes = []
  ): bool {
    // vérifications

    // crédits négatifs ou capacité du cours mal renseignée
    if ($credits <= 0 || $capaciteMax <= 1) {
      return false;
    }
    $pdo = $this->connect();

    try {
      $sqlCoursCheck = "SELECT COUNT(*) FROM cours WHERE code = :code";
      $stmtCheck = $pdo->prepare($sqlCoursCheck);
      $stmtCheck->execute([':code' => $code]);
      // le code existe déjà
      if ($stmtCheck->fetchColumn() > 0) {
        return false;
      }
      $pdo->beginTransaction(); // pour pouvoir revenir en arrière si PB
      // on ajoute le cours
      $sqlCours = "INSERT INTO cours (code, nom, credits, description, capacite_max, annee_universitaire) 
                     VALUES (:code, :nom, :credits, :desc, :cap, :annee)";

      $stmtCours = $pdo->prepare($sqlCours);
      $stmtCours->execute([
        ':code'    => $code,
        ':nom'     => $nom,
        ':credits' => $credits,
        ':desc'    => $description,
        ':cap'     => $capaciteMax,
        ':annee'   => $annee
      ]);
      // récupération de l'ID du cours nouvellement créé
      $stmtNouvelleId = $pdo->prepare("SELECT id FROM cours WHERE code = :code");
      $stmtNouvelleId->execute([':code' => $code]);
      $nouveauCoursId = $stmtNouvelleId->fetchColumn();
      if ($nouveauCoursId === false) {
        return false; // erreur lors de la récupération de l'ID
      }
      // on ajoute les prérequis s'il y en a
      if (!empty($prerequisCodes)) {
        // requête pour retrouver l'ID du prérequis
        $sqlGetId = "SELECT id FROM cours WHERE code = :code_prerequis";
        $stmtGetId = $pdo->prepare($sqlGetId);
        // requête pour insérer le nouveau prérequis
        $sqlInsertPrerequis = "INSERT INTO prerequis (cours_id, prerequis_cours_id) VALUES (:cours_id, :prerequis_cours_id)";
        $stmtInsertPrerequis = $pdo->prepare($sqlInsertPrerequis);
        $succes = true;
        foreach ($prerequisCodes as $prerequisCode) {
          if ($prerequisCode == $code) {
            // c'est un auto-référencement !
            // on passe à l'item suivant...
            continue;
          }
          // récupération de lID du prérequis en cours
          $stmtGetId->execute([':code_prerequis' => $prerequisCode]);
          $prerequisId = $stmtGetId->fetchColumn();
          if ($prerequisId == false) {
            // le code du prérequis courant n'existe pas : erreur
            $succes = false;
            break;
          }
          // on ajoute le prérequis courant
          $stmtInsertPrerequis->execute([
            ':cours_id' => $nouveauCoursId,
            ':prerequis_cours_id' => $prerequisId
          ]);
        }
        if ($succes) {
          $pdo->commit(); // on valide la transaction
        }
        return $succes;
      } else {
        $pdo->commit(); // on valide la transaction
        return true;
      }
    } catch (PDOException $error) {
      // si une erreur survient lors de l'exécution d'une requête
      // on doit revenir en arrière
      if ($pdo->inTransaction()) {
        $pdo->rollBack();
      }
      error_log('[' . date(DATE_RFC2822) . '] Erreur addCourse : ' . $error->getMessage() . PHP_EOL, 3, ERROR_LOG_PATH);
      return false;
    }
  }
}
