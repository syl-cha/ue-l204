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
    // format incorrect de l'année scolaire
    if (!$this->isYearFormatValid($annee)) {
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
      $nouveauCoursId = $stmtNouvelleId->fetchColumn(); // ID sur la 1ere colonne
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
    } catch (PDOException $exception) {
      // si une erreur survient lors de l'exécution d'une requête
      // on doit revenir en arrière
      if ($pdo->inTransaction()) {
        $pdo->rollBack();
      }
      error_log('[' . date(DATE_RFC2822) . '] Erreur addCourse : ' . $exception->getMessage() . PHP_EOL, 3, ERROR_LOG_PATH);
      return false;
    }
  }

  /**
   * Ajoute un enseignant à un cours pour une année scolaire particulière (entité **enseigne**).
   * Vérification des doublons ou des problèmes de clés invalides via l'exception à l'insertion.
   * @param int $enseignantId L'ID de l'enseignant
   * @param int $coursId L'ID du cours
   * @param string $annee L'année scolaire
   * @return bool true si l'enseignant a été ajouté au cours, sinon false.
   */
  public function addTeaching(int $enseignantId, int $coursId, string $annee, bool $responsable = false): bool
  {
    // format incorrect de l'année scolaire
    if (!$this->isYearFormatValid($annee)) {
      return false;
    }
    $sql = 'INSERT INTO enseigne (enseignant_id, cours_id, annee_universitaire, responsable)
            VALUES (:enseignant_id, :cours_id, :annee, :responsable)';
    $stmt = $this->connect()->prepare($sql);

    try {
      $stmt->execute([
        ':enseignant_id' => $enseignantId,
        ':cours_id' => $coursId,
        ':annee' => $annee,
        ':responsable' => $responsable ? true : false
      ]);
      return true;
    } catch (\PDOException $exception) {
      if ($exception->getCode() == '23000') {
        $mysqlCode = $exception->errorInfo[1] ?? null; // Récupération du code d'erreur MySQL
        if ($mysqlCode == 1062) { // C'est un doublon (UNIQUE KEY non respecté)
          return false;
        }
        if ($mysqlCode == 1452) { // C'est une clé étrangère non respectée
          throw new Exception("Erreur : L'enseignant (ID $enseignantId) ou le cours (ID $coursId) n'existe pas.");
        }
        throw $exception;
      }
    }
  }

  /**
   * Inscrit un étudiant à un cours. (entité **inscription**)
   * @param int $etudiantId L'ID de l'étudiant à inscrire
   * @param int $coursId L'ID du cours où l'inscrire
   * @return bool Retourne true si l'étudiant est bien inscrit au cours.
   */
  public function addEnrollment(int $etudiantId, int $coursId): bool
  {
    // vérification des prérequis
    $missing = $this->getMissingPrerequisites($etudiantId, $coursId);
    if (!empty($missing)) {
      $missingCodes = implode(', ', $missing);
      throw new Exception('Inscription impossible : manquent la validation de ' . $missingCodes);

      $sql = "INSERT INTO inscription (etudiant_id, cours_id) VALUES (:etudiant_id, :cours_id)";
      $stmt = $this->connect()->prepare($sql);
      try {
        $stmt->execute([
          ':etudiant_id' => $etudiantId,
          ':cours_id' => $coursId
        ]);
        return true;
      } catch (PDOException $exception) {
        if ($exception->getCode() == '23000') {
          $mysqlCode = $exception->errorInfo[1] ?? null; // Récupération du code d'erreur MySQL
          if ($mysqlCode == 1062) { // L'étudiant est déjà inscrit au cours (UNIQUE KEY non respecté)
            return false;
          }
          if ($mysqlCode == 1452) { // C'est une clé étrangère non respectée
            throw new Exception("Erreur : L'étudiant (ID $etudiantId) ou le cours (ID $coursId) n'existe pas.");
          }
          throw $exception;
        }
        return false;
      }
    }
  }

  /**
   *        MÉTHODES AUXILIAIRES
   */

  /**
   * Vérifie si le format d'année scolaire est correct 
   * (par exemple '2024-2025')
   * @param string $year L'année scolaire à vérifier
   * @return bool true si le format est correct, False sinon
   */
  private function isYearFormatValid(string $year): bool
  {
    $regexp = '/^(\d{4})-(\d{4})$/';
    if (!preg_match($regexp, $year, $matches)) {
      return false;
    }
    $firstYear = (int)$matches[1];
    $secondYear = (int)$matches[2];
    return $secondYear - $firstYear === 1;
  }

  private function getMissingPrerequisites(int $etudiantId, int $coursId): array
  {
    $sql = 'SELECT c.code
            FROM prerequis p
            INNER JOIN cours c ON p.prerequis_cours_id = c.id
            LEFT JOIN inscription i ON i.cours_id = p.prerequis_cours_id 
                 AND i.etudiant_id = :etudiant_id 
                 AND i.valide = 1
            WHERE p.cours_id = :cours_id
            AND i.id IS NULL';

    $stmt = $this->connect()->prepare($sql);
    $stmt->execute([
      ':etudiant_id' => $etudiantId,
      ':cours_id' => $coursId
    ]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }
}
