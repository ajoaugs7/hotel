<?php
require('../config/autoload.php'); 
$dao = new DataAccess();

$elements = array(
    "r_phone" => ""
);

$form = new FormAssist($elements, $_POST);
$labels = array(
    'r_phone' => "Phone Number"
);

$rules = array(
    "r_phone" => array("required" => true, "integeronly" => true, "minlength" => 10, "maxlength" => 10),
);

$validator = new FormValidator($rules, $labels);
$msg = "";

if (isset($_POST['retrieve'])) {
    if ($validator->validate($_POST)) {
        // Check if phone number exists in the database
        $phone = $_POST['r_phone'];
        $userData = $dao->getData(array("r_password"), "registration", array("r_phone" => $phone));

        if (!empty($userData)) {
            // Password found for the provided phone number
            $retrievedPassword = $userData[0]['r_password'];
            $msg = "Your password is: <strong>" . htmlspecialchars($retrievedPassword) . "</strong>";
        } else {
            // Phone number not found in the database
            $msg = "The phone number is not registered. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="styles.css">
    <style type="text/css">
        .valErr {
            color: red !important;
        }
        .msg {
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="page-wrapper bg-gra-01 p-t-180 p-b-100 font-poppins">
        <div class="wrapper wrapper--w780">
            <div class="card card-3">
                <div class="card-body">
                    <h2 class="title">Forget Password</h2>
                    <form method="POST">
                        <p class="msg"><?php if (isset($msg)) echo $msg; ?></p>
                        <div class="input-group">
                            <?= $form->textBox('r_phone', array("placeholder" => "Enter your registered phone number")); ?>
                            <span class="valErr"><?= $validator->error('r_phone'); ?></span>
                        </div>
                        <div class="p-t-10">
                            <button class="btn btn--pill btn--green" name="retrieve" type="submit">Retrieve Password</button>
                        </div>
                        <div class="p-t-10">
                            <a href="login.php" class="btn btn--pill btn--green">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
