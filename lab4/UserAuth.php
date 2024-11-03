<?php
require 'Aes.php';
// Include PHPMailer or your preferred mail library
require './PHPMailer/PHPMailer/src/PHPMailer.php';
require './PHPMailer/PHPMailer/src/Exception.php';
require './PHPMailer/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $stmt = $this->pdo->prepare("SELECT id, password_hash, salt, email FROM user WHERE login = :login");
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $passwordHash = hash('sha512', $password . $user['salt']);

      if ($passwordHash === $user['password_hash']) {
        // Login successful, set session variables
        $_SESSION['user_id'] = $user['id']; // Store user ID
        $_SESSION['user_login'] = $login;   // Store login
        $_SESSION['temp_user_id'] = $user['id']; // Store temp user ID for OTP
        $this->initiateTwoFactorAuth($user['id'], $user['email']);
        header("Location: otp_verification.php");
        exit();
      }
    }
    return false; // Login failed
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

  public function initiateTwoFactorAuth($userId, $email)
  {
    // Generate a 6-digit OTP
    $otp = random_int(100000, 999999);
    $expiry = date('Y-m-d H:i:s', time() + 300); // OTP expires in 5 minutes

    // Store OTP and expiry in the database for the user
    $stmt = $this->pdo->prepare("UPDATE user SET otp = :otp, otp_expiry = :expiry WHERE id = :id");
    $stmt->execute([
      ':otp' => $otp,
      ':expiry' => $expiry,
      ':id' => $userId
    ]);

    // Send OTP to the user's email
    $this->sendOtpEmail($email, $otp);
  }

  public function sendOtpEmail($email, $otp)
  {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 's95386@pollub.edu.pl';
    $mail->Password = 'your_password';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('s95386@pollub.edu.pl', 'YourApp');
    $mail->addAddress($email);
    $mail->Subject = 'Your One-Time Authentication Code';
    $mail->Body = "Your OTP is: $otp. It is valid for the next 5 minutes.";

    if (!$mail->send()) {
      echo 'Message could not be sent.' . $mail->ErrorInfo;
      // throw new Exception('Email could not be sent. Mailer Error: ' . $mail->ErrorInfo);
    }
  }

  public function verifyOtp($userId, $otp)
  {
    $stmt = $this->pdo->prepare("SELECT otp, otp_expiry FROM user WHERE id = :id");
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['otp'] == $otp && strtotime($user['otp_expiry']) > time()) {
      // OTP is correct and not expired
      $this->clearOtp($userId); // Clear OTP after successful verification

      // Initialize session expiration
      $_SESSION['last_activity'] = time();
      $_SESSION['expire_time'] = time() + 30; // Set session expiration time to 5 minutes

      return true;
    }
    return false;
  }

  private function clearOtp($userId)
  {
    $stmt = $this->pdo->prepare("UPDATE user SET otp = NULL, otp_expiry = NULL WHERE id = :id");
    $stmt->execute([':id' => $userId]);
  }
}
