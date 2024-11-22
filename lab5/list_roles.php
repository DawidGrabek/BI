<?php
require_once 'Db.php';
require_once 'PrivilegeManager.php';

$db = new Db();
$pdo = $db->getConnection();
$privilegeManager = new PrivilegeManager($pdo);

$roles = $privilegeManager->getRoles();

echo "<h2>Lista ról w systemie:</h2>";
echo "<ul>";
foreach ($roles as $role) {
  echo "<li>{$role['id']}: {$role['name']}</li>";
}
echo "</ul>";
