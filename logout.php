<?php
// Initialize the session.

// If you are using session_name("something"), don't forget it now!

session_start();
setcookie('memid', '', time() - 3600); 
unset($_COOKIE['memid']);


// Finally, destroy the session.

session_destroy();
?>
<script> window.location.href="index.php"; </script>
