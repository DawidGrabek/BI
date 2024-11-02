<?php
session_start();
include 'UserAuth.php';
include 'Db.php';

// Check if the session has expired
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
  // Session expired after 5 minutes
  session_unset();     // Remove session variables
  session_destroy();   // Destroy the session
  header("Location: login.php?message=Session expired. Please log in again.");
  exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Initialize session expiration after successful login (e.g., after OTP verification)
if (isset($_SESSION['user_id'])) {
  $_SESSION['expire_time'] = time() + 60; // Set session expiration to 5 minutes from now
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