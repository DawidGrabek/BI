<?php
class Db
{
  private $pdo;

  public function __construct($server = 'localhost', $user = 'root', $password = 'root', $database = 'lab4_bi')
  {
    $dsn = "mysql:host=$server;dbname=$database;charset=utf8";
    try {
      $this->pdo = new PDO($dsn, $user, $password);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
      exit();
    }
  }

  public function getPdo()
  {
    return $this->pdo;
  }
}
