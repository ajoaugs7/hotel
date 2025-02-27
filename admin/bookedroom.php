<?php
session_start();

// Connect to the database
include 'db_connect.php';

// Initialize variables for date filters
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Build the SQL query
$sql = "SELECT 
            booking_id, 
            room_type, 
            check_in, 
            check_out, 
            created_at, 
            user_email, 
            cancel_date, 
            payment_status
        FROM bookings";

// Apply date filter if startDate and endDate are provided
if (!empty($startDate) && !empty($endDate)) {
    $sql .= " WHERE check_in >= '$startDate' AND check_out <= '$endDate'";
}

$sql .= " ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Details</title>
<style>
   body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #444;
    margin-bottom: 20px;
}

form {
    text-align: center;
    margin-bottom: 20px;
}

form input[type="date"], form button {
    padding: 8px;
    margin: 5px;
    font-size: 14px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-size: 14px;
}

th {
    background-color: #4CAF50;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

td {
    color: #555;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

.no-bookings {
    text-align: center;
    font-style: italic;
    color: #777;
    font-size: 16px;
}

@media (max-width: 600px) {
    table {
        width: 100%;
        font-size: 12px;
    }

    th, td {
        padding: 8px;
    }
}
</style>
</head>
<body>

<h2>All Booking Details</h2>

<form method="get" action="">
    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required>
    <label for="end_date">End Date:</label>
    <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required>
    <button type="submit">Filter</button>
</form>

<?php
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr>
            <th>Booking ID</th>
            <th>Room Type</th>
            <th>Check-in Date</th>
            <th>Check-out Date</th>
            <th>Booking Created At</th>
            <th>User Email</th>
            <th>Cancel Date</th>
            <th>Payment Status</th>
          </tr>";

    // Fetch and display each booking detail
    while ($row = $result->fetch_assoc()) {
        // Check if cancel_date is NULL, if so, set it to a default message
        $cancelDate = $row['cancel_date'] ? htmlspecialchars($row['cancel_date']) : "Not Cancelled";

        echo "<tr>
                <td>" . htmlspecialchars($row['booking_id']) . "</td>
                <td>" . htmlspecialchars($row['room_type']) . "</td>
                <td>" . htmlspecialchars($row['check_in']) . "</td>
                <td>" . htmlspecialchars($row['check_out']) . "</td>
                <td>" . htmlspecialchars($row['created_at']) . "</td>
                <td>" . htmlspecialchars($row['user_email']) . "</td>
                <td>" . $cancelDate . "</td>
                <td>" . htmlspecialchars($row['payment_status']) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='no-bookings'>No bookings found for the selected date range.</p>";
}
?>

</body>
</html>