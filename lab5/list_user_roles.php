<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

$db = new Db();
$pdo = $db->getConnection();
$privilegeManager = new PrivilegeManager($pdo);

// Wyświetlanie ról dla użytkownika
if (!empty($_GET['user_id'])) {
  $userId = intval($_GET['user_id']);
  $roles = $privilegeManager->getUserRoles($userId);

  echo "<h3>Role użytkownika o ID: $userId</h3>";
  foreach ($roles as $role) {
    echo "<p>{$role['name']} <form method='POST' style='display:inline;'>
                <input type='hidden' name='user_id' value='$userId'>
                <input type='hidden' name='role_id' value='{$role['id']}'>
                <button type='submit' name='remove_role'>Usuń</button>
              </form></p>";
  }
}

// Obsługa dodawania i usuwania ról
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_role']) && !empty($_POST['user_id']) && !empty($_POST['role_id'])) {
    $privilegeManager->addUserRole(intval($_POST['user_id']), intval($_POST['role_id']));
    header("Location: index.php");
    exit();
  }
  // elseif (isset($_POST['remove_role']) && !empty($_POST['user_id']) && !empty($_POST['role_id'])) {
  //   $privilegeManager->removeUserRole(intval($_POST['user_id']), intval($_POST['role_id']));
  //   header("Location: index.php");
  //   exit();
  // }
}
?>

<!-- Formularz do dodawania roli do użytkownika -->
<form method="POST">
  <label for="user_id">ID użytkownika:</label>
  <input type="number" id="user_id" name="user_id" required>
  <label for="role_id">ID roli do dodania:</label>
  <input type="number" id="role_id" name="role_id" required>
  <button type="submit" name="add_role">Dodaj rolę</button>
</form>