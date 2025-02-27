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

// Define the category ID you want to filter by
$c_id = 2; // Fetching details of c_id = 1

// SQL query to fetch items from the 'item' table where c_id = ?
$sql = "SELECT * FROM item WHERE c_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $c_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h1>Laxuary Category Items</h1>";
    
    // Loop through each item and display its details
    while ($row = $result->fetch_assoc()) {
        if ($row['i_stock'] !== 'available') {
            continue; // Skip items that are not available
        }
        echo "<div class='item'>";
        echo "<h2>" . $row['i_name'] . "</h2>";
        echo "<img src='uploads/" . $row['i_image'] . "' alt='" . $row['i_name'] . "'>";
        echo "<p>Price: $" . $row['i_price'] . "</p>";
        echo "<p>availablity: " . $row['i_stock'] . "</p>";
        echo "<p>Description: " . $row['i_des'] . "</p>";
        // Add a booking button that links to a booking page
        echo "<a href='check_in.php?i_id=" . $row['i_id'] . "' class='booking-button'>Book Now</a>";

        echo "</div>";
        echo "<hr>";
    }
} else {
    echo "<p>No items found in this category.</p>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

</body>
</html>
