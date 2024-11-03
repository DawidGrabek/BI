<?php
session_start();
require 'UserAuth.php';
require 'Db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?message=not_logged_in");
  exit();
}

// Check if the session has expired
if (isset($_SESSION['expire_time']) && time() > $_SESSION['expire_time']) {
  // Session expired
  session_unset();
  session_destroy();
  header("Location: login.php?message=session_expired");
  exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();
$_SESSION['expire_time'] = time() + 300; // Renew session expiration time

// Display user's login status
if (isset($_SESSION['user_login'])) {
  echo "<p>Logged in as: " . htmlspecialchars($_SESSION['user_login']) . "</p>";
} else {
  echo "<p>Not logged in</p>";
}

// Database connection
$db = new Db();
$userAuth = new UserAuth($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newPassword = $_POST['new_password'];
  $userId = $_SESSION['user_id'];

  if ($userAuth->changePassword($userId, $newPassword)) {
    echo "Password changed successfully.";
  } else {
    echo "Failed to change password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
</head>

<body>
  <h2>Change Password</h2>
  <form action="" method="POST">
    <label for="new_password">New Password:</label><br>
    <input type="password" name="new_password" id="new_password" required><br><br>
    <button type="submit">Change Password</button>
  </form>
</body>

</html>