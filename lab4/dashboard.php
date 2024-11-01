<?php
session_start();
require 'UserAuth.php';
require 'Db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

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