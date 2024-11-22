<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['permission_name'])) {
  $db = new Db();
  $pdo = $db->getConnection();
  $privilegeManager = new PrivilegeManager($pdo);

  $permissionName = htmlspecialchars($_POST['permission_name']);
  $privilegeManager->addPermission($permissionName);

  echo "Dodano nowe uprawnienie: $permissionName";
  header("Location: index.php");
  exit();
}
?>

<form method="POST">
  <label for="permission_name">Nazwa uprawnienia:</label>
  <input type="text" id="permission_name" name="permission_name" required>
  <button type="submit">Dodaj uprawnienie</button>
</form>