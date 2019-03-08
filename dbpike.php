<?php
$servername = "localhost";
$username = "root";
$password = "2004";
$dbname = "test";

// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$con) {
    die("Data Connection failed: " . mysqli_connect_error());
}
//echo "Database connected successfully";
?>