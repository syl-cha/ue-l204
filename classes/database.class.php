<?php
require_once __DIR__ . '/../config.php';
define('ERROR_LOG_PATH', dirname(__FILE__) . '/database-errors.log');

class DataBase
{
  private $host = DB_HOST;
  private $dbname = DB_NAME;
  private $user = DB_USER;
  private $pwd = DB_PWD;

  protected function connect()
  {
    $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';

    try {
      $pdo = new PDO($dsn, $this->user, $this->pwd, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    } catch (PDOException $error) {
      var_dump(ERROR_LOG_PATH);
      error_log('[' . date(DATE_RFC2822) . '] Database connection error : ' . $error->getMessage() . PHP_EOL, 3, ERROR_LOG_PATH);
      throw new Exception("Connection to database failed");
    }
    return $pdo;
  }
}
