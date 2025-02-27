<?php include('header.php'); ?>
<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";  // adjust according to your setup
$dbname = "hotel"; // database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from 'registration' table
$sql = "SELECT * FROM registration";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Table</title>
</head>
<body>

<h2>Registration Details</h2>

<form action="update.php" method="post">
    <table border="1">
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Password</th>
            <th>Action</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            // Display each row as a form
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td><input type='text' name='r_id[]' value='" . $row['r_id'] . "' readonly></td>
                    <td><input type='text' name='r_firstname[]' value='" . $row['r_firstname'] . "'></td>
                    <td><input type='text' name='r_lastname[]' value='" . $row['r_lastname'] . "'></td>
                    <td><input type='text' name='r_email[]' value='" . $row['r_email'] . "'></td>
                    <td><input type='text' name='r_phone[]' value='" . $row['r_phone'] . "'></td>
                    <td><input type='password' name='r_password[]' value='" . $row['r_password'] . "'></td>
                    <td><input type='submit' name='update' value='Update'></td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No records found</td></tr>";
        }
        ?>

    </table>
</form>

</body>
</html>

<?php
$conn->close();
?>
