
   



<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form | Dan Aleko</title>
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

<style type="text/css">
            .valErr{
                color:red!important;
            }
        </style>
<?php
require('../config/autoload.php'); 
include 'db_connect.php';
$dao = new DataAccess();

$elements = array(
    "r_firstname" => "",
    "r_lastname" => "",
    "r_email" => "",
    "r_phone" => "",
    "r_password" => "",
);

$form = new FormAssist($elements, $_POST);
$labels = array(
    'r_firstname' => "First Name",
    'r_lastname' => "Last Name",
    'r_email' => "Email",
    'r_phone' => "Phone Number",
    'r_password' => "Password",
);

$rules = array(
    "r_firstname" => array("required" => true, "minlength" => 3, "maxlength" => 30, "alphaspaceonly" => true),
    "r_lastname" => array("required" => true, "minlength" => 3, "maxlength" => 30, "alphaonly" => true),
    "r_email" => array("required" => true, "email" => true),
    "r_phone" => array("required" => true, "integeronly" => true, "minlength" => 10, "maxlength" => 10),
    "r_password" => array("required" => true),
);

$validator = new FormValidator($rules, $labels);
$msg = "";

if (isset($_POST['register'])) {
    if ($validator->validate($_POST)) {
        // Check if email already exists
        $email = $_POST['r_email'];
        
        // Query to check if email already exists in the registration table
        $sql = "SELECT * FROM registration WHERE r_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email already registered
            $msg = "This email is already registered. Please use a different email.";
        } else {
            // Insert new user into registration table
           

            $data = array(
                'r_firstname' => $_POST['r_firstname'],
                'r_lastname' => $_POST['r_lastname'],
                'r_phone' => $_POST['r_phone'],
                'r_email' => $_POST['r_email'],
                'r_password' => $_POST['r_password'],
            );

            if ($dao->insert($data, 'registration')) {
                echo "<script>alert('Registration successful! Redirecting to login.');</script>";
                echo "<script>location.replace('login.php');</script>";
            } else {
                $msg = "Registration failed. Please try again.";
            }
        }
    }
}
if (isset($_POST['home'])) {
    echo "<script>location.replace('displaycategory.php');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles.css">
    <style type="text/css">
        .valErr {
            color: red !important;
        }
    </style>
</head>

<body>
    <div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins">
        <div class="wrapper wrapper--w780">
            <div class="card card-3">
                <div class="card-body">
                    <h2 class="title">Registration Info</h2>
                    <form method="POST">
                        <p style="color: red;"><?php if (isset($msg)) echo $msg; ?></p>
                        <div class="input-group">
                            <?= $form->textBox('r_firstname', array("placeholder" => "First Name")); ?>
                            <span class="valErr"><?= $validator->error('r_firstname'); ?></span>
                        </div>
                        <div class="input-group">
                            <?= $form->textBox('r_lastname', array("placeholder" => "Last Name")); ?>
                            <span class="valErr"><?= $validator->error('r_lastname'); ?></span>
                        </div>
                        <div class="input-group">
                            <?= $form->textBox('r_email', array("placeholder" => "Email")); ?>
                            <span class="valErr"><?= $validator->error('r_email'); ?></span>
                        </div>
                        <div class="input-group">
                            <?= $form->textBox('r_phone', array("placeholder" => "Phone Number")); ?>
                            <span class="valErr"><?= $validator->error('r_phone'); ?></span>
                        </div>
                        <div class="input-group">
                            <?= $form->passwordBox('r_password', array("placeholder" => "Password")); ?>
                            <span class="valErr"><?= $validator->error('r_password'); ?></span>
                        </div>
                        <div class="p-t-10">
                            <button class="btn btn--pill btn--green" name="register" type="submit">Register</button>
                        </div>
                        <div class="p-t-10">
                            <a href="login.php" class="btn btn--pill btn--green">Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
</body>

</html>

