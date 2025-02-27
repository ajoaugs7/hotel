<?php
// Database connection settings
$host = 'localhost'; // Database host
$dbname = 'hotel';   // Database name
$username = 'root';  // Database username
$password = '';      // Database password

try {
    // Create a PDO instance (Database connection)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to count the number of bookings for each room_type
    $query = "
        SELECT room_type, COUNT(*) AS booking_count
        FROM booking
        WHERE cancel_date IS NULL OR cancel_date > NOW() -- Exclude canceled bookings
        GROUP BY room_type
        HAVING booking_count > 4;
    ";
    
    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Loop through each room_type with more than 4 bookings
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $room_type = $row['room_type'];
        
        // Update i_stock to 'unavailable' for the room_type in the item table
        $updateQuery = "
            UPDATE item
            SET i_stock = 'unavailable'
            WHERE room_type = :room_type;
        ";
        
        // Prepare and execute the update query
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':room_type', $room_type);
        $updateStmt->execute();

        echo "Updated i_stock to 'unavailable' for room type: $room_type\n";
    }

} catch (PDOException $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}

?>
