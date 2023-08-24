<?php

$host = ""; //Host Name
$dbname = ""; // DB Name
$root = ""; // User Name
$pswd = ""; // Password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;", $root, $pswd);
} catch (PDOException $e) {
    die("Database Error" . $e->getMessage());
}

require_once 'function.php';
