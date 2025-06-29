<?php  
// This file should act similar to config.php

// Q1: Specify the servername, username and password to your local host
$sname = "localhost";
$uname = "root";
$password = "";

// Q2: Assign database to $db_name
//     You can create a new database or just used the previous database mydb 
$db_name = "voting";

// Q2: Establish the connection with mysql
$conn = mysqli_connect($sname,$uname,$password,$db_name);

if (!$conn) {
	echo "Connection Failed!";
	exit();
}