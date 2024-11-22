<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['role_id'])) {
  $db = new Db();
  $pdo = $db->getConnection();
  $privilegeManager = new PrivilegeManager($pdo);

  $roleId = intval($_POST['role_id']);
  $privilegeManager->removeRole($roleId);

  echo "Usunięto rolę o ID: $roleId";
  header("Location: index.php");
  exit();
}
?>

<form method="POST">
  <label for="role_id">ID roli do usunięcia:</label>
  <input type="number" id="role_id" name="role_id" required>
  <button type="submit">Usuń rolę</button>
</form>