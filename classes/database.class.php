<?php
require_once __DIR__ . '/../config.php';
define('ERROR_LOG_PATH', dirname(__FILE__) . '/database-errors.log');

/**
 * Classe de base pour gérer la connexion à la BDD de l'université.
 * ELle doit servir de parent à toutes les classes nécessitant
 * un accès aux données
 */
class DataBase
{
  private $host = DB_HOST;
  private $dbname = DB_NAME;
  private $user = DB_USER;
  private $pwd = DB_PWD;

  private $pdo = null;

  /**
   * Établit et retourne une connexion PDO à la BDD de l'université.
   * @return PDO La connexion à la BDD
   * @throws Exception si la connexion échoue.
   */
  protected function connect()
  {
    if ($this->pdo !== null) {
      // la connection à la base de données est déjà établie
      return $this->pdo;
    }

    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
    
    try {
      $this->pdo = new PDO($dsn, $this->user, $this->pwd, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $exception) {
      error_log('[' . date(DATE_RFC2822) . '] Database connection error : ' . $exception->getMessage() . PHP_EOL, 3, ERROR_LOG_PATH);
      throw new Exception("Connection to database failed");
    }
    return $this->pdo;
  }

  /**
   * Ferme la connexion à la BDD de l'université
   */
  protected function disconnect() {
    if ($this->pdo !== null) {
      $this->pdo = null;
    }
  }
}
