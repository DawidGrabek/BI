<?php
class Db
{
  private $pdo;

  public function __construct()
  {
    try {
      $this->pdo = new PDO('mysql:host=localhost;dbname=lab5', 'root', 'root');
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  public function getConnection()
  {
    return $this->pdo;
  }
}
