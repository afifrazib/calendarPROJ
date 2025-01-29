<?php
//db creds
$servername = "calendarproj_db";
$username = "root";
$pass = "verysecret";
$dbname = "calendarproj";

//database connection
$conn = new mysqli($servername, $username, $pass, $dbname);

//check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

?>