<?php
error_reporting(E_ALL);
$db = new Mysqlidb($db_server, $db_user, $db_pass, $db_name);
if(!$db) die("Database error");
?>
