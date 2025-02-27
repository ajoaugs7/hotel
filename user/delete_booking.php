<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "You need to log in to delete your booking.";
    exit();
}

// Get the logged-in user's email from session
$email = $_SESSION['user_email'];

// Connect to the database
include 'db_connect.php';

// Prepare SQL to delete the user's booking
$sql = "DELETE FROM bookings WHERE user_email = ?";

// Prepare statement and bind email
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

// Execute the query
if ($stmt->execute()) {
    echo "Booking deleted successfully.";
} else {
    echo "Error deleting booking: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
