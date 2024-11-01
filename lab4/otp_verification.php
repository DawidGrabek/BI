<?php
session_start();
include 'UserAuth.php';
include 'Db.php';

if (!isset($_SESSION['temp_user_id'])) {
  header("Location: login.php");
  exit();
}

$db = new Db();
$userAuth = new UserAuth($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $otp = $_POST['otp'];
  $userId = $_SESSION['temp_user_id'];

  if ($userAuth->verifyOtp($userId, $otp)) {
    $_SESSION['user_id'] = $userId;  // Full login session
    unset($_SESSION['temp_user_id']);  // Remove temp session
    header("Location: dashboard.php");
    exit();
  } else {
    $error = "Invalid or expired OTP. Please try again.";
  }
}
?>

<form method="POST" action="otp_verification.php">
  <label for="otp">Enter OTP:</label>
  <input type="text" id="otp" name="otp" required><br>
  <button type="submit">Verify OTP</button>
</form>

<?php if ($error): ?>
  <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>