<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

$db = new Db();
$pdo = $db->getConnection();
$privilegeManager = new PrivilegeManager($pdo);

$userId = 2; // Przykładowe ID użytkownika - zastąp rzeczywistym ID
$userPermissions = $privilegeManager->getUserPermissions($userId);

echo "<h2>Lista uprawnień użytkownika (ID: $userId):</h2>";
echo "<ul>";
foreach ($userPermissions as $permission) {
  echo "<li>{$permission['id']}: {$permission['name']}</li>";
}
echo "</ul>";
