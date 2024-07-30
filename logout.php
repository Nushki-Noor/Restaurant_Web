<?php
session_start();
session_unset();
session_destroy();
echo '<script>alert("Logged out Successfully");</script>';
echo '<script>location = "Customer/php/Home.php";</script>';
exit();
?>