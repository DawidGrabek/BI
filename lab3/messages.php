<?php
include_once "classes/Page.php";
include_once "classes/Db.php";
Page::display_header("Messages");
$db = new Db("localhost", "root", "root", "news");
// adding new message
if (isset($_REQUEST['add_message'])) {
  $name = $_REQUEST['name'];
  $type = $_REQUEST['type'];
  $content = $_REQUEST['content'];
  if (!$db->addMessage($name, $type, $content))
    echo "Adding new message failed";
}
?>
<hr>
<P> Messages</P>
<ol>
  <?php
  $sql = "SELECT * from message";
  $messages = $db->select($sql);
  foreach ($messages as $msg) {
    echo "<li>";
    echo htmlspecialchars($msg->message);  // Prevent XSS by encoding
    // echo " - <a href='edit_message.php?id=" . htmlspecialchars($msg->id) . "'>Edit</a>";
    echo "</li>";
  }

  ?>
</ol>
<hr>
<P>Navigation</P>
<?php
Page::display_navigation();
?>
</body>

</html>