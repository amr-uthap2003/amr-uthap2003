<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login or home page
header("Location: login.php");
exit();

