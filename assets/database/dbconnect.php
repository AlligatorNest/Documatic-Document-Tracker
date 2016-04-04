<?php
error_reporting(E_ALL);
$db = new Mysqlidb('localhost', 'root', '', 'documents');
if(!$db) die("Database error");
?>
