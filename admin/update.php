<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";  // adjust according to your setup
$dbname = "hotel"; // database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the data in the 'registration' table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $r_ids = $_POST['r_id'];
    $r_firstnames = $_POST['r_firstname'];
    $r_lastnames = $_POST['r_lastname'];
    $r_emails = $_POST['r_email'];
    $r_phones = $_POST['r_phone'];
    $r_passwords = $_POST['r_password'];

    for ($i = 0; $i < count($r_ids); $i++) {
        $r_id = $r_ids[$i];
        $r_firstname = $r_firstnames[$i];
        $r_lastname = $r_lastnames[$i];
        $r_email = $r_emails[$i];
        $r_phone = $r_phones[$i];
        $r_password = $r_passwords[$i];

        // Prepare SQL query
        $sql = "UPDATE registration SET 
                r_firstname='$r_firstname', 
                r_lastname='$r_lastname', 
                r_email='$r_email', 
                r_phone='$r_phone', 
                r_password='$r_password' 
                WHERE r_id='$r_id'";

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully for ID $r_id<br>";
        } else {
            echo "Error updating record for ID $r_id: " . $conn->error . "<br>";
        }
    }
}

$conn->close();
?>
