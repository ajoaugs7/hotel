<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Category Items</title>
    <style>
        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            margin: 0;
        }

        /* Styling for the header */
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        /* Container for each item */
        .item {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto; /* Center the container */
        }

        /* Styling for the item image */
        .item img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        /* Styling for item details */
        .item h2 {
            color: #444;
            font-size: 24px;
            margin-bottom: 10px;
            text-align: center;
        }

        .item p {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Styling for the Booking button */
        .booking-button {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            margin: 0 auto; /* Center the button */
        }

        .booking-button:hover {
            background-color: #218838;
        }

        /* Styling for horizontal line between items */
        hr {
            border: none;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }
    </style>
</head>
<body>
<?php
// Database connection (modify the credentials as per your database setup)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the category ID and booking limit
$c_id = 1; // Fetching details for category ID = 1
$limit = 5; // Maximum number of rooms available in this category

// Define variables for booking
$room_type = "budget single bed room"; // Specify the room type to limit
$check_in = '2024-12-09'; // Example check-in date
$check_out = '2024-12-12'; // Example check-out date
$booking_limit = 4;

// Query to count active bookings for the specified room type and date overlap
$checkDateLimitSql = "
    SELECT COUNT(*) AS active_bookings
    FROM bookings
    WHERE room_type = ?
    AND status = 'booked'
    AND cancel_date IS NULL
    AND (
        (check_in <= ? AND check_out > ?) OR
        (check_in < ? AND check_out >= ?) OR
        (check_in >= ? AND check_out <= ?)
    )
";
$dateLimitStmt = $conn->prepare($checkDateLimitSql);
$dateLimitStmt->bind_param("sssssss", $room_type, $check_in, $check_in, $check_out, $check_out, $check_in, $check_out);
$dateLimitStmt->execute();
$dateLimitResult = $dateLimitStmt->get_result();
$active_bookings = $dateLimitResult->fetch_assoc()['active_bookings'];

// Determine if 'budget single bed room' should be excluded
$exclude_room_type = ($active_bookings >= $booking_limit);

// Query to fetch available rooms
$sql = "SELECT * FROM item WHERE i_stock = 'available'";
if ($exclude_room_type) {
    // Exclude 'budget single bed room' if its limit is reached
    $sql .= " AND i_name != ?";
}
$sql .= " AND c_id = ?"; // Limit by category

$stmt = $conn->prepare($sql);
if ($exclude_room_type) {
    $stmt->bind_param("si", $room_type, $c_id);
} else {
    $stmt->bind_param("i", $c_id);
}

$stmt->execute();
$result = $stmt->get_result();

// Display available rooms
if ($result->num_rows > 0) {
    echo "<h1>Available Rooms</h1>";

    while ($row = $result->fetch_assoc()) {
        echo "<div class='item'>";
        echo "<h2>" . $row['i_name'] . "</h2>";
        echo "<img src='uploads/" . $row['i_image'] . "' alt='" . $row['i_name'] . "'>";
        echo "<p>Price: $" . $row['i_price'] . "</p>";
        echo "<p>Availability: " . $row['i_stock'] . "</p>";
        echo "<p>Description: " . $row['i_des'] . "</p>";
        // Add a booking button that links to a booking page
        echo "<a href='single.php?i_id=" . $row['i_id'] . "' class='booking-button'>Book Now</a>";
        echo "</div>";
        echo "<hr>";
    }
} else {
    echo "<p>No available rooms found in this category.</p>";
}

// Close the statement and connection
$stmt->close();
$dateLimitStmt->close();
$conn->close();
?>
