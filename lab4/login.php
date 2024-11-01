<?php
session_start();
include 'Db.php';
include 'UserAuth.php';

// Create a new database connection
$db = new Db("localhost", "root", "root", "lab4_bi");
$auth = new UserAuth($db);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = $_POST['login'];
  $password = $_POST['password'];

  if ($auth->loginUser($login, $password)) {
    echo "User logged in successfully!";
    header("Location: dashboard.php");
    exit();
  } else {
    $error = "Login failed. Please check your credentials.";
  }
}
?>

<form method="POST" action="login.php">
  <label for="login">Login:</label>
  <input type="text" id="login" name="login" required><br>
  <label for="password">Password:</label>
  <input type="password" id="password" name="password" required><br>
  <button type="submit">Login</button>
</form>

<?php if ($error): ?>
  <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>