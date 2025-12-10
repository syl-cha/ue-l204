<?php
require('./classes/database.class.php');
class UniversiteDB extends DataBase {

  public function getPDO() {
    return $this->connect();
  }
}