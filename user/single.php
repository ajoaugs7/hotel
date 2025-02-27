<?php
session_start();
// booking.php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";  // Change if required
$dbname = "hotel"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location:login/login.php");
    exit();
}

$user_email = $_SESSION['user_email']; // Logged-in user's email
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $user_email = $_SESSION['user_email']; // Get the logged-in user’s email

    $sql = "INSERT INTO bookings (room_type, check_in, check_out, user_email, created_at) VALUES ('$room_type', '$check_in', '$check_out', '$user_email', NOW())";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the appropriate payment page based on room type
        if ($room_type == "budget single bed room") {
            header("Location:payment_form.php"); // Redirect to Single Bed Room payment page
        } else {
            header("Location:payment_form.php"); // Redirect to other payment page
        }
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking</title>
    <style>
        /* Body styling with a background image */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('r1.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Form container */
        .booking-container {
            background-color: rgba(255, 255, 255, 0.8);
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            margin: 100px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form heading */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Form labels and inputs */
        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

    </style>
</head>
<body>
    <div class="booking-container">
        <h2>Budget Room Booking</h2>
        <form method="POST" action="">
            <label for="room_type">Room Type:</label>
            <select name="room_type" required>
                <option value="budget single bed room">Single Bed Room</option>
                <option value="budget double bed room">Double Bed Room</option>
            </select><br><br>
            <label for="check_in">Check-In Date:</label>
            <input type="date" name="check_in" id="check_in" required>

            <label for="check_out">Check-Out Date:</label>
            <input type="date" name="check_out" id="check_out" required>
            <input type="submit" value="Book Now">
        </form>
    </div>
    <script>
        // Set today's date as the minimum for check-in
        const today = new Date().toISOString().split("T")[0];
        const checkInInput = document.getElementById("check_in");
        const checkOutInput = document.getElementById("check_out");

        checkInInput.setAttribute("min", today);
        checkOutInput.setAttribute("min", today);

        // Ensure check-out is after check-in
        checkInInput.addEventListener("change", function () {
            const checkInDate = checkInInput.value;
            checkOutInput.setAttribute("min", checkInDate);
        });
    </script>
</body>
</html>
