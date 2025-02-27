<?php include('header.php'); ?>
<?php
// view_booking.php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";  // Change if required
$dbname = "hotel"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the bookings from the database
$sql = "SELECT b.booking_id, b.room_type, b.check_in, b.check_out, r.r_firstname, r.r_lastname 
        FROM booking 
        JOIN registration  ON b.user_id = r.r_id";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
</head>
<body>

<h2>View Bookings</h2>

<table border="1">
    <tr>
        <th>Booking ID</th>
        <th>Room Type</th>
        <th>Check-In Date</th>
        <th>Check-Out Date</th>
        <th>Customer Name</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        // Output the data for each booking
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['booking_id']}</td>
                    <td>{$row['room_type']}</td>
                    <td>{$row['check_in']}</td>
                    <td>{$row['check_out']}</td>
                    <td>{$row['r_firstname']} {$row['r_lastname']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No bookings found</td></tr>";
    }
    ?>

</table>

</body>
</html>

<?php
$conn->close();
?>
