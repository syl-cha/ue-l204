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
}
