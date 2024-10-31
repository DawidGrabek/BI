<?php
include_once 'Filter.php';

class Db
{
  private $pdo;

  public function __construct($serwer, $user = 'root', $pass = 'password', $baza = 'news')
  {
    $dsn = "mysql:host=$serwer;dbname=$baza;charset=utf8";
    try {
      $this->pdo = new PDO($dsn, $user, $pass);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
      exit();
    }
  }

  // Generic insert function
  public function insert($table, $data, $types)
  {
    foreach ($data as $key => $value) {
      if (isset($types[$key])) {
        $data[$key] = Filter::filterData($value, $types[$key]);
      }
    }

    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

    try {
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute(array_values($data));
    } catch (PDOException $e) {
      echo "Insert failed: " . $e->getMessage();
      return false;
    }
  }

  // Generic select function
  public function select($sql, $params = [])
  {
    try {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);
      return $stmt->fetchAll(PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      echo "Query failed: " . $e->getMessage();
      return [];
    }
  }

  // Generic update function
  public function update($table, $data, $types, $condition)
  {
    foreach ($data as $key => $value) {
      if (isset($types[$key])) {
        $data[$key] = Filter::filterData($value, $types[$key]);
      }
    }

    $set = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
    $sql = "UPDATE $table SET $set WHERE $condition";

    try {
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute(array_values($data));
    } catch (PDOException $e) {
      echo "Update failed: " . $e->getMessage();
      return false;
    }
  }
}
