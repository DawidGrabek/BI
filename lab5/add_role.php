<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['role_name'])) {
  $db = new Db();
  $pdo = $db->getConnection();
  $privilegeManager = new PrivilegeManager($pdo);

  $roleName = htmlspecialchars($_POST['role_name']);
  $privilegeManager->addRole($roleName);

  echo "Dodano nową rolę: $roleName";
  header("Location: index.php");
  exit();
}
?>

<form method="POST">
  <label for="role_name">Nazwa roli:</label>
  <input type="text" id="role_name" name="role_name" required>
  <button type="submit">Dodaj rolę</button>
</form>