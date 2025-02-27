<?php
// Database connection settings
$host = 'localhost'; // Change if your database is on another server
$dbname = 'hotel'; // Replace with your actual database name
$username = 'root'; // Replace with your actual database username
$password = ''; // Replace with your actual database password

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $cardHolderName = $_POST['card_holder_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $cardNumber = $_POST['card_number'];
        $expiryMonth = $_POST['expiry_month'];
        $expiryYear = $_POST['expiry_year'];
        $cvc = $_POST['cvc'];

        // Prepare an SQL statement
        $sql = "INSERT INTO payments (card_holder_name, email, address, city, zip, state, country, card_number, expiry_month, expiry_year, cvc)
                VALUES (:cardHolderName, :email, :address, :city, :zip, :state, :country, :cardNumber, :expiryMonth, :expiryYear, :cvc)";

        // Bind and execute the statement
        $stmt = $pdo->prepare($sql);
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
        $stmt->execute();

        echo "Payment information saved successfully!";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null; // Close the database connection
?>
