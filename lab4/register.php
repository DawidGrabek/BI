<?php
session_start();
include 'Db.php';
include 'UserAuth.php';

// Create a new database connection
$db = new Db("localhost", "root", "root", "lab4_bi");
$auth = new UserAuth($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = $_POST['login'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  if ($auth->registerUser($login, $email, $password)) {
    echo "User registered successfully!";
  } else {
    echo "Registration failed.";
  }
}
?>

<form method="POST" action="register.php">
  <label for="login">Login:</label>
  <input type="text" id="login" name="login" required><br>
  <label for="email">Email:</label>
  <input type="email" id="email" name="email" required><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password" required><br>
  <button type="submit">Register</button>
</form>