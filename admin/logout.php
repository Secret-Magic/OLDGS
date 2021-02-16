<?php
session_start();
$_SESSION["loggedin"] = FALSE;
session_unset();
session_destroy();

header("location: index.php");
exit();
