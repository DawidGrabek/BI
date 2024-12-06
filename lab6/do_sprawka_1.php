<?php
session_start();
require 'auth.php'; // Ensures the user is authenticated
include_once "classes/Page.php";
include_once "classes/Db.php";

Page::display_header("My Messages");

$db = new Db("localhost", "root", "root", "news");
$user_id = $_SESSION['user_id'];

// Fetch messages created by the logged-in user
$sql = "SELECT * FROM message WHERE id_user = :id_user";
$params = [':id_user' => $user_id];
$messages = $db->select($sql, $params);

echo "<h1>Messages</h1>";
echo "<ul>";
foreach ($messages as $msg) {
  echo "<li>";
  echo htmlspecialchars($msg->message);
  echo " - <a href='edit_message.php?id=" . $msg->id . "'>Edit</a>";
  echo "</li>";
}
echo "</ul>";

Page::display_navigation();
