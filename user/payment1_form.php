<<?php
session_start(); // Start session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "You need to log in to make a payment.";
    exit();
}

// Get the logged-in user's email from session
$email = $_SESSION['user_email']; 

// Connect to the database (replace with your actual connection details)
include 'db_connect.php';

// Prepare SQL to fetch the user's booking information, including the room type
$sql = "SELECT check_in, check_out, room_type FROM bookings WHERE user_email = ? ORDER BY created_at DESC LIMIT 1"; // Fetch the latest booking

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email); // Bind the email parameter to the query
$stmt->execute();
$result = $stmt->get_result();

// Default daily rate if no booking is found
$amount = 8000; // Base daily rate before adjusting for room type

if ($result->num_rows > 0) {
    // Fetch the booking details
    $row = $result->fetch_assoc();
    $check_in = $row['check_in'];
    $check_out = $row['check_out'];
    $room_type = $row['room_type']; // Get the room type for the booking

    // Calculate the number of days between check_in and check_out
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $interval = $check_in_date->diff($check_out_date);
    $days_diff = $interval->days;

    // Adjust the amount based on room type
    if ($room_type == 'Luxury Single Bed Room') {
        $amount = $days_diff * 1500; // Luxury Single Bed Room rate
    } elseif ($room_type == 'Luxury Double Bed Room') {
        $amount = $days_diff * 3000; // Luxury Double Bed Room rate
    } else {
        $amount = $days_diff * 8000; // Default rate for other room types
    }
} else {
    // Handle case if no booking is found for the user
    echo "<p>No booking found. Please make a booking first.</p>";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Customer Details</h2>
    <form id="paymentForm" action="process_payment.php" method="POST">

        <!-- Customer Details -->
        <div class="form-group">
            <label for="card_holder_name">Card Holder Name *</label>
            <input type="text" class="form-control" id="card_holder_name" name="card_holder_name" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required readonly>
            <small class="form-text text-muted">Please enter the login email address</small>
        </div>

        <div class="form-group">
            <label for="address">Address *</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="city">City *</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group col-md-3">
                <label for="zip">Zip *</label>
                <input type="text" class="form-control" id="zip" name="zip" required>
            </div>
            <div class="form-group col-md-3">
                <label for="state">State</label>
                <input type="text" class="form-control" id="state" name="state">
            </div>
        </div>

        <div class="form-group">
            <label for="country">Country *</label>
            <input type="text" class="form-control" id="country" name="country" required>
        </div>

        <!-- Fixed Amount Section -->
        <div class="form-group">
            <label for="amount">Amount *</label>
            <input type="text" class="form-control" id="amount" name="amount" value="<?php echo number_format($amount, 2); ?>" readonly>
            <small class="form-text text-muted">This is the calculated amount based on your booking duration and room type.</small>
        </div>

        <h2 class="text-center mt-4">Payment Details</h2>

        <!-- Payment Details -->
        <div class="form-group">
            <label for="card_number">Card Number *</label>
            <input type="text" class="form-control" id="card_number" name="card_number" required maxlength="16" placeholder="1234 5678 9012 3456">
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="expiry_month">Expiry Month *</label>
                <input type="text" class="form-control" id="expiry_month" name="expiry_month" required maxlength="2" placeholder="MM">
            </div>
            <div class="form-group col-md-4">
                <label for="expiry_year">Expiry Year *</label>
                <input type="text" class="form-control" id="expiry_year" name="expiry_year" required maxlength="4" placeholder="YYYY">
            </div>
            <div class="form-group col-md-4">
                <label for="cvc">CVC *</label>
                <input type="text" class="form-control" id="cvc" name="cvc" required maxlength="3" placeholder="123">
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-block">Pay Now</button>
    </form>
</div>

<!-- JavaScript to prevent leaving without submission -->
<script>
    // Flag to check if the form was submitted
    let isFormSubmitted = false;

    // Set the flag to true when the form is submitted
    document.getElementById('paymentForm').addEventListener('submit', function() {
        isFormSubmitted = true;
    });

    // Warn user before leaving the page
    window.addEventListener('beforeunload', function (event) {
        if (!isFormSubmitted) {
            event.preventDefault();
            event.returnValue = 'Are you sure you want to leave? Your payment information will not be saved.';
        }
    });
</script>

</body>
</html>
