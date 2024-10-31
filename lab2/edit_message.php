<?php
include_once "classes/Page.php";
include_once "classes/Db.php";

Page::display_header("Edit Message");

$db = new Db("localhost", "root", "root", "news");

// Get the message ID from the URL or form submission
$message_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

// Fetch message details if the ID is provided
$message = null;
if ($message_id) {
  // Use the generic select function to fetch the message by ID
  $sql = "SELECT * FROM message WHERE id = :id";
  $params = [':id' => $message_id];
  $messages = $db->select($sql, $params);

  if (!empty($messages)) {
    $message = $messages[0];
  } else {
    echo "<p>Message not found.</p>";
    exit;
  }
}

// Handle form submission for editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_message'])) {
  $id = (int)$_POST['id'];
  $name = $_POST['name'];
  $type = $_POST['type'];
  $content = $_POST['content'];

  // Define allowed types
  $allowed_types = ['public', 'private'];
  if (!in_array($type, $allowed_types)) {
    echo "Invalid message type.";
    exit();
  }

  // Prepare data and types for update
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
  $condition = "id = $id";

  // Use the generic update function to save changes
  if ($db->update('message', $data, $types, $condition)) {
    echo "<p>Message updated successfully!</p>";
  } else {
    echo "<p>Failed to update message.</p>";
  }
}
?>

<h2>Edit Message</h2>
<form method="POST" action="">
  <label for="id">Record ID:</label><br>
  <input type="number" id="id" name="id" value="<?php echo htmlspecialchars($message_id); ?>" required><br>

  <label for="name">Name:</label><br>
  <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($message->name); ?>" required><br>

  <label for="type">Type:</label><br>
  <select id="type" name="type">
    <option value="public" <?php if ($message->type === 'public') echo 'selected'; ?>>Public</option>
    <option value="private" <?php if ($message->type === 'private') echo 'selected'; ?>>Private</option>
  </select><br>

  <label for="content">Content:</label><br>
  <textarea id="content" name="content" rows="10" cols="50" required><?php echo htmlspecialchars($message->message); ?></textarea><br>

  <button type="submit" name="edit_message">Save Changes</button>
</form>

<hr>
<a href="messages.php">Back to Messages</a>

<?php
Page::display_navigation();
?>