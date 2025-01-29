<?php
//db creds
$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "calendar_app";

//database connection
$conn = new mysqli($servername, $username, $pass, $dbname);

//check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

?>