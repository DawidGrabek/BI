<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "classes/Page.php";
include_once "classes/Db.php";

Page::display_header("Messages");
$db = new Db("localhost", "root", "root", "news");


if (isset($_REQUEST['add_message'])) {
  $name = $_POST['name'];
  $type = $_POST['type'];
  $content = $_POST['content'];

  // Define allowed message types
  $allowed_types = ['public', 'private'];
  if (!in_array($type, $allowed_types)) {
    echo "Invalid message type.";
    exit();
  }

  // Prepare data and types
  $data = [
    'name' => $name,
    'type' => $type,
    'message' => $content,
    'deleted' => 0
  ];
  $types = [
    'name' => 'name',
    'type' => 'name',
    'message' => 'text'
  ];

  // Use the generic insert function
  if ($db->insert('message', $data, $types)) {
    echo "Message added successfully";
  } else {
    echo "Adding new message failed";
  }
}
?>

<!-- Display Messages List -->
<hr>
<p> Messages </p>
<ol>
  <?php
  $where_clause = "";
  $params = [];
  if (isset($_REQUEST['filter_messages'])) {
    $string = filter_input(INPUT_POST, 'string', FILTER_SANITIZE_SPECIAL_CHARS);
    $where_clause = " WHERE name LIKE :filter";
    $params = [':filter' => "%$string%"];
  }

  $sql = "SELECT * from message" . $where_clause;
  $messages = $db->select($sql, $params);

  foreach ($messages as $msg) {
    echo "<li>";
    echo htmlspecialchars($msg->message);
    echo " - <a href='edit_message.php?id=" . $msg->id . "'>Edit</a>";
    echo "</li>";
  }
  ?>
</ol>
<hr>
<P>Messages filtering</P>
<form method="post" action="messages.php">
  <table>
    <tr>
      <td>Title contains: </td>
      <td>
        <label for="name"></label>
        <input required type="text" name="string" id="string" size="80" />
      </td>
    </tr>
  </table>
  <input type="submit" id="submit"
    value="Find messages" name="filter_messages">
</form>

<!-- --------------------------------------------------------------------- -->
<hr>
<p>Navigation</p>
<?php
Page::display_navigation();
?>
</body>

</html>