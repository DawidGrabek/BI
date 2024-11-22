<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

$db = new Db();
$pdo = $db->getConnection();
$privilegeManager = new PrivilegeManager($pdo);

// Wyświetlanie uprawnień dla roli
if (!empty($_GET['role_id'])) {
  $roleId = intval($_GET['role_id']);
  $permissions = $privilegeManager->getRolePermissions($roleId);

  echo "<h3>Uprawnienia przypisane do roli o ID: $roleId</h3>";
  foreach ($permissions as $permission) {
    echo "<p>{$permission['name']} <form method='POST' style='display:inline;'>
                <input type='hidden' name='role_id' value='$roleId'>
                <input type='hidden' name='permission_id' value='{$permission['id']}'>
                <button type='submit' name='remove_permission'>Usuń</button>
              </form></p>";
  }
}

// Obsługa dodawania i usuwania uprawnień
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_permission']) && !empty($_POST['role_id']) && !empty($_POST['permission_id'])) {
    $privilegeManager->addRolePermission(intval($_POST['role_id']), intval($_POST['permission_id']));
    header("Location: index.php");
    exit();
  } elseif (isset($_POST['remove_permission']) && !empty($_POST['role_id']) && !empty($_POST['permission_id'])) {
    $privilegeManager->removeRolePermission(intval($_POST['role_id']), intval($_POST['permission_id']));
    header("Location: index.php");
    exit();
  }
}
?>

<!-- Formularz do dodawania uprawnienia do roli -->
<form method="POST">
  <label for="role_id">ID roli:</label>
  <input type="number" id="role_id" name="role_id" required>
  <label for="permission_id">ID uprawnienia do dodania:</label>
  <input type="number" id="permission_id" name="permission_id" required>
  <button type="submit" name="add_permission">Dodaj uprawnienie</button>
</form>