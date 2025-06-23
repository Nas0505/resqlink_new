<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "resqlink";
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn -> connect_error) {
    die("Connection failed: " . mysql->error());
}
//echo "Connected successfully";
?>