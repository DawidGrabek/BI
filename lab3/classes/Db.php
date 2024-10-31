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
}
