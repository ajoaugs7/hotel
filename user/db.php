<?php
// db.php
$servername = "localhost"; // Change if necessary
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "hotel"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
