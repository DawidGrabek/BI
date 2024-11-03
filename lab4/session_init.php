<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
  // Check if the session has expired
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
    // Session expired, destroy session and redirect to login with a message
    session_unset();
    session_destroy();
    header("Location: login.php?message=session_expired");
    exit();
  } else {
    // Update the last activity timestamp
    $_SESSION['last_activity'] = time();
  }
}
