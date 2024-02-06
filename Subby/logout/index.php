<?php
session_start();
$_SESSION['loggedIn'] = FALSE;
session_unset();
session_destroy();
header("Location: /");
?>
