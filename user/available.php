<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Items</title>
    <style>
        /* General styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        /* Container for each item */
        .item-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Styling for the item image */
        .item-image img {
            max-width: 100px;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }

        /* Item details */
        .item-details {
            margin-bottom: 10px;
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
        }

        .booking-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>Available rooms</h1>

<?php
// Database connection
$host = 'localhost';  // Replace with your host
$db = 'hotel';  // Replace with your database name
$user = 'root';  // Replace with your DB username
$pass = '';  // Replace with your DB password

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "SELECT i_id, i_name, i_image, i_price, i_stock, c_id, i_des FROM item WHERE i_stock = ?";
$stmt = $conn->prepare($sql);

// Bind the parameter (i.e., 'available')
$stock_status = 'available';
$stmt->bind_param("s", $stock_status);  // "s" means string

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch the data and display
if ($result->num_rows > 0) {
    // Output each row
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item-container'>";
        echo "<div class='item-image'><img src='user" . $row["i_image"] . "' alt='" . $row["i_name"] . "'></div>";
        
        echo "<div class='item-details'><strong>Name:</strong> " . $row["i_name"] . "</div>";
        echo "<div class='item-details'><strong>Price:</strong> $" . $row["i_price"] . "</div>";
        echo "<div class='item-details'><strong>Stock:</strong> " . $row["i_stock"] . "</div>";
        echo "<div class='item-details'><strong>Category ID:</strong> " . $row["c_id"] . "</div>";
        echo "<div class='item-details'><strong>Description:</strong> " . $row["i_des"] . "</div>";
        echo "<a href='booking.php?i_id=" . $row["i_id"] . "' class='booking-button'>Book Now</a>";
        echo "</div>";
    }
} else {
    echo "<p>No items found.</p>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

</body>
</html>
