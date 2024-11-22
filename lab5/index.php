<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <title>Zarządzanie Uprawnieniami i Rolami</title>
</head>

<body>
  <section>
    <?php include 'list_permissions.php'; ?>
  </section>

  <section>
    <?php include 'list_user_permissions.php'; ?>
  </section>

  <section>
    <h2>Zarządzanie uprawnieniami:</h2>
    <?php include 'add_permission.php'; ?>
    <?php include 'remove_permission.php'; ?>
  </section>

  <section>
    <?php include 'list_roles.php'; ?>
  </section>

  <section>
    <h2>Zarządzanie rolami:</h2>
    <?php include 'add_role.php'; ?>
    <?php include 'remove_role.php'; ?>
  </section>

  <section>
    <h2>Zarządzanie uprawnieniami przypisanymi do ról:</h2>
    <?php include 'list_role_permissions.php'; ?>
  </section>

  <section>
    <h2>Zarządzanie rolami użytkownika:</h2>
    <?php include 'list_user_roles.php'; ?>
  </section>

</body>

</html>