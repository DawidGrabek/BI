<?php
session_start();
require 'auth.php'; // Ensures the user is authenticated
include_once "classes/Page.php";
include_once "classes/Db.php";

Page::display_header("Edit Message");

$db = new Db("localhost", "root", "root", "news");

$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id']; // Logged-in user's ID

// Fetch the message
$sql = "SELECT * FROM message WHERE id = :id";
$params = [':id' => $message_id];
$messages = $db->select($sql, $params);

if (empty($messages)) {
  echo "<p>Message not found.</p>";
  exit;
}

$message = $messages[0];

if ($message->id_user !== $user_id && $_SESSION['role'] !== 'admin') {
  echo "<p>You do not have permission to edit this message.</p>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_message'])) {
  $name = $_POST['name'];
  $type = $_POST['type'];
  $content = $_POST['content'];

  $allowed_types = ['public', 'private'];
  if (!in_array($type, $allowed_types)) {
    echo "Invalid message type.";
    exit();
  }

  $data = [
    'name' => $name,
    'type' => $type,
    'message' => $content
  ];
  $types = [
    'name' => 'name',
    'type' => 'name',
    'message' => 'text'
  ];
  $condition = "id = $message_id";

  if ($db->update('message', $data, $types, $condition)) {
    echo "<p>Message updated successfully!</p>";
  } else {
    echo "<p>Failed to update message.</p>";
  }
}
?>

<h2>Edit Message</h2>
<form method="POST" action="">
  <label for="name">Name:</label><br>
  <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($message->name); ?>" required><br><br>

  <label for="type">Type:</label><br>
  <select id="type" name="type">
    <option value="public" <?php if ($message->type === 'public') echo 'selected'; ?>>Public</option>
    <option value="private" <?php if ($message->type === 'private') echo 'selected'; ?>>Private</option>
  </select><br><br>

  <label for="content">Content:</label><br>
  <textarea id="content" name="content" rows="5" cols="50" required><?php echo htmlspecialchars($message->message); ?></textarea><br><br>

  <button type="submit" name="edit_message">Save Changes</button>
</form>

<hr>
<a href="my_messages.php">Back to My Messages</a>

<?php
Page::display_navigation();
?>