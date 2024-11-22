<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

$db = new Db();
$pdo = $db->getConnection();
$privilegeManager = new PrivilegeManager($pdo);

$permissions = $privilegeManager->getSystemPermissions();

echo "<h2>Lista uprawnień w systemie:</h2>";
echo "<ul>";
foreach ($permissions as $permission) {
  echo "<li>{$permission['id']}: {$permission['name']}</li>";
}
echo "</ul>";
