<?php
class UserAuth
{
  private $pdo;

  public function __construct($db)
  {
    // $db should be an instance of Db, so we get the PDO object from it
    $this->pdo = $db->getPdo();
  }

  public function registerUser($login, $email, $password)
  {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (login, email, password_hash) VALUES (:login, :email, :password_hash)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      ':login' => $login,
      ':email' => $email,
      ':password_hash' => $passwordHash
    ]);

    return $stmt->rowCount() > 0;
  }

  public function loginUser($login, $password)
  {
    $sql = "SELECT * FROM user WHERE login = :login";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      if (password_verify($password, $user['password_hash'])) {
        return true;
      } else {
        echo "Successful login.";
        return false;
      }
    } else {
      echo "User not found.";
      return false;
    }
  }
}
