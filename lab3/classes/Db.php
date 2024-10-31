<?php
class Db
{
  private $mysqli; //Database variable
  private $select_result; //result

  public function __construct($serwer, $user, $pass, $baza)
  {
    $this->mysqli = new mysqli($serwer, $user, $pass, $baza);
    if ($this->mysqli->connect_errno) {
      printf("Connection to server failed: %s \n", $this->mysqli->connect_error);
      exit();
    }
    if ($this->mysqli->set_charset("utf8")) {
      // charset changed
    }
  }

  function __destruct()
  {
    $this->mysqli->close();
  }

  public function select($sql)
  {
    $results = array();
    if ($result = $this->mysqli->query($sql)) {
      while ($row = $result->fetch_object()) {
        $results[] = $row;
      }
      $result->close();
    }
    $this->select_result = $results;
    return $results;
  }

  public function addMessage($name, $type, $content)
  {
    // Apply addslashes to content
    $content = addslashes($content);

    $sql = "INSERT INTO message (`name`, `type`, `message`, `deleted`) VALUES (?, ?, ?, 0)";
    $stmt = $this->mysqli->prepare($sql);

    if ($stmt) {
      $stmt->bind_param("sss", $name, $type, $content);
      $result = $stmt->execute();
      $stmt->close();
      return $result;
    } else {
      echo "Preparation failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
      return false;
    }
  }

  public function getMessage($message_id)
  {
    foreach ($this->select_result as $message) {
      if ($message->id == $message_id)
        return $message->message;
    }
  }

  public function insert($table, $data, $types)
  {
    foreach ($data as $key => $value) {
      if (isset($types[$key])) {
        // Filter data based on the specified type (name, email, text, etc.)
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
