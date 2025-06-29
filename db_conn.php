<?php
$sname = "localhost:3307";  // server name with port
$uname = "root";
$password = "";
$dbname = "voting_system";

// Use mysqli_connect or parse port properly
$conn = new mysqli("localhost", $uname, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>