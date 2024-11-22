<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['permission_id'])) {
  $db = new Db();
  $pdo = $db->getConnection();
  $privilegeManager = new PrivilegeManager($pdo);

  $permissionId = intval($_POST['permission_id']);
  $privilegeManager->removePermission($permissionId);

  echo "Usunięto uprawnienie o ID: $permissionId";
  header("Location: index.php");
  exit();
}
?>

<form method="POST">
  <label for="permission_id">ID uprawnienia do usunięcia:</label>
  <input type="number" id="permission_id" name="permission_id" required>
  <button type="submit">Usuń uprawnienie</button>
</form>