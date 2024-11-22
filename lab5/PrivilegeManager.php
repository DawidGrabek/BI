<?php
class PrivilegeManager
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function getSystemPermissions()
  {
    $stmt = $this->pdo->prepare("SELECT * FROM privilege");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getUserPermissions($userId)
  {
    $stmt = $this->pdo->prepare("SELECT p.* FROM privilege p 
                                     JOIN user_privilege up ON p.id = up.id_privilege 
                                     WHERE up.id_user = :userId");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getRoles()
  {
    $stmt = $this->pdo->prepare("SELECT * FROM role");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function addPermission($name)
  {
    $stmt = $this->pdo->prepare("INSERT INTO privilege (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
  }

  // public function removePermission($id)
  // {
  //   $stmt = $this->pdo->prepare("DELETE FROM privilege WHERE id = :id");
  //   $stmt->bindParam(':id', $id);
  //   $stmt->execute();
  // }

  public function addRole($name)
  {
    $stmt = $this->pdo->prepare("INSERT INTO role (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
  }

  // public function removeRole($id)
  // {
  //   $stmt = $this->pdo->prepare("DELETE FROM role WHERE id = :id");
  //   $stmt->bindParam(':id', $id);
  //   $stmt->execute();
  // }

  public function removePermission($id)
  {
    // Remove entries from role_privilege before deleting the privilege
    $stmt = $this->pdo->prepare("DELETE FROM role_privilege WHERE id_privilege = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $stmt = $this->pdo->prepare("DELETE FROM privilege WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
  }

  public function removeRole($id)
  {
    // Remove entries from role_privilege before deleting the role
    $stmt = $this->pdo->prepare("DELETE FROM role_privilege WHERE id_role = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $stmt = $this->pdo->prepare("DELETE FROM role WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
  }
}
