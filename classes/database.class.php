<?php
require('../ue_l204_projet/config.php');
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
      error_log('[' . date(DATE_RFC2822) . '] Database connection error : ' . $error->getMessage(), 3, 'database-errors.log' . PHP_EOL);
      throw new Exception("Connection to database failed");
    }
    return $pdo;
  }
}
