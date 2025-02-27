<?php
// Database connection settings
$host = 'localhost'; // Change to your database host
$dbname = 'hotel'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $cardHolderName = htmlspecialchars($_POST['card_holder_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $zip = htmlspecialchars($_POST['zip']);
    $state = htmlspecialchars($_POST['state']);
    $country = htmlspecialchars($_POST['country']);
    $cardNumber = htmlspecialchars($_POST['card_number']);
    $expiryMonth = htmlspecialchars($_POST['expiry_month']);
    $expiryYear = htmlspecialchars($_POST['expiry_year']);
    $cvc = htmlspecialchars($_POST['cvc']);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Database connection using PDO
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert payment information into the payments table
// Define a fixed amount
$amount = 300.00; // Set the fixed amount value

// Prepare the SQL query
$sql = "INSERT INTO payments (card_holder_name, email, address, city, zip, state, country, card_number, expiry_month, expiry_year, cvc, amount)
        VALUES (:cardHolderName, :email, :address, :city, :zip, :state, :country, :cardNumber, :expiryMonth, :expiryYear, :cvc, :amount)";

$stmt = $pdo->prepare($sql);

// Bind parameters from form input
$stmt->bindParam(':cardHolderName', $cardHolderName);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':address', $address);
$stmt->bindParam(':city', $city);
$stmt->bindParam(':zip', $zip);
$stmt->bindParam(':state', $state);
$stmt->bindParam(':country', $country);
$stmt->bindParam(':cardNumber', $cardNumber);
$stmt->bindParam(':expiryMonth', $expiryMonth);
$stmt->bindParam(':expiryYear', $expiryYear);
$stmt->bindParam(':cvc', $cvc);

// Bind the fixed amount
$stmt->bindParam(':amount', $amount);

// Execute the query
$stmt->execute();

echo "Payment successfully recorded with a fixed amount of $" . $amount;


// Execute the query
$stmt->execute();

        // Execute the insert statement
        $stmt->execute();

        // Update the payment status in the bookings table
        $updateSql = "UPDATE bookings SET payment_status = 'Paid' WHERE user_email = :email AND payment_status = 'Pending' ORDER BY created_at DESC LIMIT 1";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':email', $email);

        // Execute the update statement
        $updateStmt->execute();

        // Check if the payment status was updated successfully
        if ($updateStmt->rowCount() > 0) {
            echo "Booking sucessful.<br>";
        } else {
            echo "Payment saved, but no booking found to update or already marked as 'Paid'.<br>";
        }

        // Display success message and navigation options
        echo '<a href="front.php" class="btn btn-primary">Go to Home Page</a><br>';
        echo '<a href="recpit.php" class="btn btn-primary">Download Receipt</a>';

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $pdo = null;
}
?>
