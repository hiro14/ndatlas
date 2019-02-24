<!-- Function module for database connection. -->
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "ndatlas_new";

// create connection
$conn = new mysqli($servername, $username, $password);

// connection check
if ($conn->connect_error) {
    die("Failed: " . $conn->connect_error);
}

// global parameters

mysqli_query($conn, "set names 'UTF8'");
mysqli_select_db($conn, $database);
?>
