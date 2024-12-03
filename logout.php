<?php
// Start the session
session_start();

// Destroy all session data to log out the user
session_unset();      // Unsets all session variables
session_destroy();    // Destroys the session

// Optionally, delete the session cookie if you are using cookies for session management
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect the user to the login page
header("Location: /usjr/login/login.php");
exit();
?>
