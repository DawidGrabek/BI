<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

echo "Welcome to the dashboard! User ID: " . htmlspecialchars($_SESSION['user_id']);