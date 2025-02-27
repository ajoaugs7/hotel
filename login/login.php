
<!DOCTYPE html>
<html lang="en">
<head>
  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">


  <title>Login Form | Dan Aleko</title>
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<script>
    // Prevent back navigation
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>


<body>
<div class="wrapper">
    <h2>Login</h2>
    <form action="login.php" method="POST">
      <div class="input-box">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br><br>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <i class='bx bxs-user'></i>
      </div>
      
      <div class="remember-forgot">
        <label><input type="checkbox">Remember Me</label>
        <a href="../user/forgetpass">Forgot Password</a>
      </div>
      <button type="submit" name="login" class="btn">Login</button>
      <div class="register-link">
        <p>Don’t have an account? <a href="registration.php">Register</a></p>
      </div>
    </form>
</div>
<script type="text/javascript">
    // This script will prevent users from using the back button after logging out
    if (typeof history.pushState === 'function') {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.pushState(null, null, location.href);
        };
    }
</script>
<script type="text/javascript">
    if (typeof history.pushState === 'function') {
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.replaceState(null, null, location.href);  // Ensures the login page stays on the history stack
        };
    }
</script>


</body>
</html>
<?php

session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // Redirect to dashboard or home page
    header("Location: ../dashboard/dashboard.php");  // Adjust as needed
    exit;
}

// Your normal login code here (form, validation, etc.)


if (isset($_POST['login'])) {
    include 'db_connect.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check for admin credentials
      // Query to verify admin credentials
      $sql = "SELECT * FROM admin WHERE a_email = ? AND a_pass = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $email, $password);
      $stmt->execute();
      $result = $stmt->get_result();
  
      if ($result->num_rows > 0) {
          // Login successful
          $row = $result->fetch_assoc();
         
          $_SESSION['admin_email'] = $row['a_email'];
          $_SESSION['admin_name'] = $row['a_name'] ?? "Admin";

        // Redirect to admin page
        header("Location: ../admin/header.php");
        exit;
    }

    // Query to check for user credentials
    $sql_user = "SELECT r_id, r_firstname, r_lastname, r_email FROM registration 
                 WHERE r_email = '$email' AND r_password = '$password'";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        // User login successful
        $row = $result_user->fetch_assoc();

        // Start session and store user details
        session_start();
        $_SESSION['user_id'] = $row['r_id'];
        $_SESSION['user_name'] = $row['r_firstname'];
        $_SESSION['user_email'] = $row['r_email'];

        // Redirect to user page
        header("Location: ../user/front.php");
        exit;
    } else {
        // Invalid login
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>
