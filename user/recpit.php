<?php
session_start(); // Start the session to access session variables

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "You need to log in to view your bookings.";
    exit();
}

// Get the logged-in user's email from the session
$email = $_SESSION['user_email']; // Assuming the email is stored in the session

// Connect to the database (adjust with your actual database connection)
include 'db_connect.php'; // Make sure you have a separate file for DB connection

// Prepare the SQL query to fetch the latest booking details based on the user's email and the latest created_at timestamp
$sql = "SELECT booking_id, room_type, check_in, check_out
        FROM bookings
        WHERE user_email = ?
        ORDER BY created_at DESC
        LIMIT 1"; // Only fetch the latest booking

$stmt = $conn->prepare($sql); // Prepare the SQL statement
$stmt->bind_param("s", $email); // Bind the email parameter to the query
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result of the query

// Check if the user has any bookings
if ($result->num_rows > 0) {
    // Fetch the latest booking details
    $row = $result->fetch_assoc();

    // Display the booking details
    echo "<h2>Your Latest Booking</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Booking ID</th><th>Room Type</th><th>Check-In</th><th>Check-Out</th>";

    echo "<tr>
            <td>" . htmlspecialchars($row['booking_id']) . "</td>
            <td>" . htmlspecialchars($row['room_type']) . "</td>
            <td>" . htmlspecialchars($row['check_in']) . "</td>
            <td>" . htmlspecialchars($row['check_out']) . "</td>
           
          </tr>";
    echo "</table>";

    // Add a download link to generate a receipt
    echo '<a href="?download_receipt=true&booking_id=' . htmlspecialchars($row['booking_id']) . '" class="btn btn-primary">Download Receipt</a>';
} else {
    echo "<p>No bookings found for your account.</p>";
}

// Handle the download request for receipt
if (isset($_GET['download_receipt']) && $_GET['download_receipt'] == 'true' && isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch the booking details for the given booking_id
    $sql = "SELECT booking_id, room_type, check_in, check_out
            FROM bookings
            WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id); // Bind the booking_id parameter to the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the booking exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Set the file name for the receipt
        $filename = "receipt_booking_" . $booking_id . ".txt";

        // Set the header for the downloadable file
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Create the receipt content
        $receipt_content = "Receipt for Booking ID: " . $row['booking_id'] . "\n";
        $receipt_content .= "Room Type: " . $row['room_type'] . "\n";
        $receipt_content .= "Check-In: " . $row['check_in'] . "\n";
        $receipt_content .= "Check-Out: " . $row['check_out'] . "\n";
        
        $receipt_content .= "Thank you for booking with us!\n";

        // Output the content to download the file
        echo $receipt_content;
        exit();
    } else {
        echo "Booking not found.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
