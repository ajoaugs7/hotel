<?php
session_start(); // Start session to access session variables

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "You need to log in to view your bookings.";
    exit();
}

// Assuming the email of the logged-in user is stored in the session
$email = $_SESSION['user_email']; // Get the logged-in user's email from session

// Set the time zone (adjust as needed)
date_default_timezone_set('UTC'); // Change 'UTC' to your preferred time zone

// Connect to the database
include 'db_connect.php';

// Check if booking was recently canceled and display a message
if (isset($_GET['canceled']) && $_GET['canceled'] == 'true') {
    echo "<p style='color: red; text-align: center;'>Your booking has been successfully canceled.</p>";
}

// Prepare SQL to fetch the latest booking of the user
$sql = "SELECT 
            booking_id, 
            room_type, 
            check_in, 
            check_out, 
            created_at, 
            cancel_date, 
            payment_status
        FROM bookings
        WHERE user_email = ?
        ORDER BY created_at DESC 
        LIMIT 1"; // Fetch only the latest booking

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email); // Bind the email parameter
$stmt->execute();
$result = $stmt->get_result();

// Check if the user has any bookings
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Get the created_at time and convert it to a timestamp
    $created_at = strtotime($row['created_at']);
    $current_time = time(); // Get current time as a timestamp
    $time_difference = $current_time - $created_at; // Time difference in seconds
    
    // Set the cancel time limit to 2 hours (2 hours * 60 minutes * 60 seconds)
    $cancel_time_limit = 1 * 60 * 60;

    // Check if the time difference is within the 2-hour window and payment is confirmed
    $can_cancel = $time_difference <= $cancel_time_limit && $row['payment_status'] === 'Paid' && !$row['cancel_date'];

    // Display the latest booking detail
    echo "<h2>Your Latest Booking</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Booking ID</th><th>Room Type</th><th>Check-In</th><th>Check-Out</th><th>Booking Time</th><th>Payment Status</th><th>Cancellation Date</th><th>Action</th></tr>";
    
    echo "<tr>
            <td>" . htmlspecialchars($row['booking_id']) . "</td>
            <td>" . htmlspecialchars($row['room_type']) . "</td>
            <td>" . htmlspecialchars($row['check_in']) . "</td>
            <td>" . htmlspecialchars($row['check_out']) . "</td>
            <td>" . htmlspecialchars($row['created_at']) . "</td>
            <td>" . htmlspecialchars($row['payment_status']) . "</td>";

    // Display the cancel date if the booking was canceled
    if ($row['cancel_date']) {
        echo "<td>" . htmlspecialchars($row['cancel_date']) . "</td>";
    } else {
        echo "<td>Not canceled yet</td>";
    }

    // Show the cancel button only if within the 2-hour window, payment is confirmed, and not already canceled
    if ($can_cancel) {
        echo "<td>
                <form method='POST' action='cancel.php' style='display:inline-block;'>
                    <input type='hidden' name='booking_id' value='" . htmlspecialchars($row['booking_id']) . "'>
                    <button type='submit' name='cancel' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to cancel this booking?.The amount will refund with in 24 hours');\">Cancel Booking</button>
                </form>
              </td>";
    } else {
        // Display a message indicating cancellation is not available
        echo "<td><p style='color: gray;'>Cancellation not available (Payment pending, after 2 hours, or already canceled).</p></td>";
    }

    echo "</tr>";
    echo "</table>";
} else {
    echo "<p>No bookings found for your account.</p>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

<style>
 body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

table {
    width: 90%;
    max-width: 800px;
    margin: 0 auto;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    font-size: 14px;
}

th {
    background-color: #4CAF50;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

td {
    color: #555;
    border-bottom: 1px solid #ddd;
}

tr:nth-child(even) {
    background-color: #f7f7f7;
}

tr:hover {
    background-color: #f1f1f1;
}

p {
    text-align: center;
    font-size: 16px;
    color: #777;
}

button {
    background-color: #f44336;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #d32f2f;
}

button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

form {
    display: inline-block;
    margin: 0;
}

.cancel-info {
    color: gray;
    font-size: 13px;
    text-align: center;
}

.message {
    text-align: center;
    font-size: 16px;
    margin: 20px 0;
    color: #e74c3c;
}

@media (max-width: 600px) {
    table {
        width: 100%;
        font-size: 12px;
    }

    th, td {
        padding: 10px;
    }

    button {
        padding: 8px 16px;
        font-size: 12px;
    }
}
</style>
