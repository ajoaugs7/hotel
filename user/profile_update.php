<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";  // adjust according to your setup
$dbname = "hotel"; // database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "<script>alert('You need to log in first.'); location.replace('login.php');</script>";
    exit;
}

// Get logged-in user's email
$logged_in_email = $_SESSION['user_email'];

// Fetch user details
$sql = "SELECT * FROM registration WHERE r_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $logged_in_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('No details found for the logged-in user.'); location.replace('login.php');</script>";
    exit;
}

$user = $result->fetch_assoc();
$msg = "";

// Handle profile update
if (isset($_POST['update_profile'])) {
    // Get user inputs
    $firstname = $_POST['r_firstname'];
    $lastname = $_POST['r_lastname'];
    $phone = $_POST['r_phone'];
    $password = $_POST['r_password'];

    // Update query for the logged-in user's email
    $update_sql = "UPDATE registration SET r_firstname = ?, r_lastname = ?, r_phone = ?, r_password = ? WHERE r_email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssss", $firstname, $lastname, $phone, $password, $logged_in_email);

    if ($update_stmt->execute()) {
        $msg = "Profile updated successfully.";
        // Refresh the user data
        $_SESSION['user_email'] = $logged_in_email;
        $user['r_firstname'] = $firstname;
        $user['r_lastname'] = $lastname;
        $user['r_phone'] = $phone;
        $user['r_password'] = $password;
        
        // Redirect to the front page (e.g., index.php)
        echo "<script>alert('Profile updated successfully.'); window.location.href = 'front.php';</script>";
        exit;
    } else {
        $msg = "Profile update failed. Please try again.";
    }
}
    

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>

<h2>Edit Profile</h2>

<?php if ($msg): ?>
    <p><?= htmlspecialchars($msg); ?></p>
<?php endif; ?>

<form method="post">
    <table>
        <tr>
            <td>First Name:</td>
            <td><input type="text" name="r_firstname" value="<?= htmlspecialchars($user['r_firstname']); ?>" required></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" name="r_lastname" value="<?= htmlspecialchars($user['r_lastname']); ?>" required></td>
        </tr>
        <tr>
            <td>Email (Cannot Change):</td>
            <td><input type="email" value="<?= htmlspecialchars($user['r_email']); ?>" readonly></td>
        </tr>
        <tr>
            <td>Phone:</td>
            <td><input type="text" name="r_phone" value="<?= htmlspecialchars($user['r_phone']); ?>" required></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="r_password" value="<?= htmlspecialchars($user['r_password']); ?>" required></td>
        </tr>
        <tr>
            <td colspan="2"><button type="submit" name="update_profile">Update</button></td>
        </tr>
    </table>
</form>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: url('fimage/header.jpg') no-repeat center center fixed; 
        background-size: cover;
        color: #333;
    }

    h2 {
        text-align: center;
        color: #fff;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    form {
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    table {
        width: 100%;
    }

    td {
        padding: 10px;
    }

    td:first-child {
        text-align: right;
        font-weight: bold;
        color: #555;
    }

    input[type="text"], 
    input[type="email"], 
    input[type="password"] {
        width: calc(100% - 20px);
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
        box-sizing: border-box;
    }

    input[type="text"]:focus, 
    input[type="email"]:focus, 
    input[type="password"]:focus {
        border-color: #007bff;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    button:hover {
        background-color: #0056b3;
    }

    p {
        text-align: center;
        font-size: 14px;
        color: #e74c3c;
    }
</style>

</body>
</html>
