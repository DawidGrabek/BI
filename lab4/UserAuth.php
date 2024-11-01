<?php
include_once "Aes.php";
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
    $salt = bin2hex(random_bytes(16));
    $passwordHash = hash('sha512', $password . $salt);

    // Encrypt the hash and encode it to base64
    $aes = new Aes();
    $encryptedHash = base64_encode($aes->encrypt($passwordHash));

    $stmt = $this->pdo->prepare("INSERT INTO user (login, email, password_hash, salt) VALUES (:login, :email, :password_hash, :salt)");
    return $stmt->execute([
      ':login' => $login,
      ':email' => $email,
      ':password_hash' => $encryptedHash,
      ':salt' => $salt
    ]);
  }


  public function loginUser($login, $password)
  {
    $stmt = $this->pdo->prepare("SELECT id, password_hash, salt FROM user WHERE login = :login");
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $aes = new Aes();
      $decryptedHash = $aes->decrypt($user['password_hash']);
      $passwordHash = hash('sha512', $password . $user['salt']);

      if ($passwordHash === $decryptedHash) {
        $_SESSION['user_id'] = $user['id'];
        return true;
      }
    }
    return false;
  }

  public function changePassword($userId, $newPassword)
  {
    $newSalt = bin2hex(random_bytes(16));
    $newPasswordHash = hash('sha512', $newPassword . $newSalt);

    $aes = new Aes();
    $encryptedHash = $aes->encrypt($newPasswordHash);

    $stmt = $this->pdo->prepare("UPDATE user SET password_hash = :password_hash, salt = :salt WHERE id = :id");
    return $stmt->execute([
      ':password_hash' => $encryptedHash,
      ':salt' => $newSalt,
      ':id' => $userId
    ]);
  }
}
