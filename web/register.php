<?php
require '../config/config.php';

$username = $password = $re_password = $email = $activationCode = $username_error = $password_error = $re_password_error = $email_error = "";
$execute = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // validate the username
    $username = $_POST["username"];
    if (empty($username)) {
        $username_error = "Username can't be empty.";
    } else if (strlen($username) < 4) {
        $username_error = "Username should contain at least 4 chars.";
    }

    // validate the password
    $password = $_POST["password"];
    if (empty($password)) {
        $password_error = "Password can't be empty.";
    } else if (strlen($password) < 4) {
        $password_error = "Password should contain at least 4 chars.";
    } else {
        // validate repeated password
        $re_password = $_POST["re_password"];
        if (strcmp($re_password, $password) !== 0) {
            $re_password_error = "Password should match with the repeated password.";
        }
    }

    // validate the email
    $email = $_POST["email"];
    if (empty($email)) {
        $email_error = "Email can't be empty.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Email you entered is invalid.";
    }

    /* if all inputs are valid */
    //generate the 5 char activation activation code
    $activationCode = substr(uniqid('', true), -5);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn -> query($sql);
    if ($result -> num_rows > 0) {
        $username_error = "Username already exists. Please choose another.";
        $execute = false;
    }

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn -> query($sql);
    if ($result -> num_rows > 0) {
        $email_error = "This email is associated with an account. Please enter the another email.";
        $execute = false;
    }

    $sql = "SELECT * FROM inactive_users WHERE username = '$username'";
    $result = $conn -> query($sql);
    if ($result -> num_rows > 0) {
        $username_error = "An account has been requested with this username. Please choose another.";
        $execute = false;
    }

    $sql = "SELECT * FROM inactive_users WHERE email = '$email'";
    $result = $conn -> query($sql);
    if ($result -> num_rows > 0) {
        $email_error = "An account has been requested for this email. Please enter the another email.";
        $execute = false;
    }
    
    if($execute == true) {
          // insert user into inactive_users table - need account verification
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
          $sql = "INSERT INTO inactive_users (username, password, email, activation_code)
VALUES ('$username', '$password', '$email', '$activationCode')";

    if (mysqli_query($conn, $sql)) {
        header("Location: activate_account.php");
	die();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
    }
}

?>

<? xml version = "1.0" encoding = "UTF-8"?>
<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
    <title>Create a member's account</title>
</head>
<body>
<form id="register_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!--<div class="g-recaptcha" data-sitekey="6Lce6hoUAAAAAOh37uDcc1MVtv6qY35GLF46FNJ2"></div>-->
    <label>Username :</label><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"
                                    autofocus="autofocus"/><span><?php echo $username_error; ?></span><br/>
    <label>Password :</label><input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>"/><span><?php echo $password_error; ?></span><br/>
    <label>Repeat password :</label><input type="password" name="re_password"
                                           value="<?php echo htmlspecialchars($re_password); ?>"/><span><?php echo $re_password_error; ?></span><br/>
    <label>Email :</label><input type="email" name="email"
                                 value="<?php echo htmlspecialchars($email); ?>"/><span><?php echo $email_error; ?></span><br/>
    <input type="submit" value=" Submit "/><input type="reset"/><br/>
</form>
</body>
</html>