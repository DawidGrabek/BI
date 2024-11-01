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
    // Generate a unique salt
    $salt = bin2hex(random_bytes(16));
    // Hash the password with the salt
    $passwordHash = hash('sha512', $password . $salt);

    // Insert the user into the database
    $stmt = $this->pdo->prepare("INSERT INTO user (login, email, password_hash, salt) VALUES (:login, :email, :password_hash, :salt)");
    return $stmt->execute([
      ':login' => $login,
      ':email' => $email,
      ':password_hash' => $passwordHash,
      ':salt' => $salt
    ]);
  }

  public function loginUser($login, $password)
  {
    // Retrieve the user's salt and password hash from the database
    $stmt = $this->pdo->prepare("SELECT id, password_hash, salt FROM user WHERE login = :login");
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // Hash the entered password with the retrieved salt
      $passwordHash = hash('sha512', $password . $user['salt']);

      // Verify the hashed password matches the stored hash
      if ($passwordHash === $user['password_hash']) {
        // Successful login
        $_SESSION['user_id'] = $user['id'];
        return true;
      }
    }
    return false; // Login failed
  }
}
