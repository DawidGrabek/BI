<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Przykładowy login i hasło (zastąp zapytaniem do bazy danych)
  $valid_user = 'admin';
  $valid_pass = 'admin';

  if ($username === $valid_user && $password === $valid_pass) {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    header("Location: index.php");
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
</head>

<body>
  <h1>Login</h1>
  <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
  <form method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Login</button>
  </form>
</body>

</html>