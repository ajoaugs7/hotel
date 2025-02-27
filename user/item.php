<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "hotel");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if c_id is set in the URL
if (isset($_GET['c_id'])) {
    $c_id = $_GET['c_id'];

    // SQL query to fetch all items with the given c_id
    $sql = "SELECT * FROM item WHERE c_id = ?";
    
    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $c_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h1>Items in Category ID: $c_id1 </h1>";
        
        // Loop through each item and display its details
        while ($row = $result->fetch_assoc()) {
            echo "<div class='item'>";
            echo "<h2>" . $row['i_name'] . "</h2>";
            echo "<img src='user" . $row['i_image'] . "' alt='" . $row['i_name'] . "' width='200px'>";
            echo "<p>Price: " . $row['i_price'] . "</p>";
            echo "<p>Stock: " . $row['i_stock'] . "</p>";
            echo "<p>Description: " . $row['i_des'] . "</p>";
            echo "</div>";
            echo "<hr>";
        }
    } else {
        echo "<p>No items found in this category.</p>";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "<p>Category ID not provided.</p>";
}

// Close the database connection
$conn->close();
?>
