<?php
session_start(); // Start session to access session variables

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$user_email = $_SESSION['user_email']; // Retrieve logged-in user's email

// Connect to the database
include 'db_connect.php';

// Check if booking ID is passed via POST
if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    // Set the cancel date to current time when canceling
    $cancel_date = date('Y-m-d H:i:s'); // Current date and time

    // Update the booking status and add the cancel date
    $sql = "UPDATE bookings SET cancel_date = ? WHERE booking_id = ? AND user_email = ? AND cancel_date IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $cancel_date, $booking_id, $user_email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect back to bookings page with a success message
        header("Location: bookings.php?canceled=true");
        exit();
    } else {
        // If no rows affected, the booking wasn't canceled or it was already canceled
        echo "<p style='color: red;'>Error: Could not cancel your booking.</p>";
    }

    $stmt->close();
} else {
    echo "<p style='color: red;'>No booking ID provided.</p>";
}

// Close connection
$conn->close();
?>


