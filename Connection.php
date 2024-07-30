<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon restaurant";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// global $mysqli;
// $mysqli = $conn;
?>