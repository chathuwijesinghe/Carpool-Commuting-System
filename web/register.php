<?php

require '../config/config.php';

session_start();
$_SESSION["username"] = $_SESSION["email"] = "";

$username = $password = $re_password = $email = $activationCode = $username_error = $password_error = $re_password_error = $email_error = $internal_error = "";
$execute = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* validate all inputs */
    // validate the username
    $username = $_POST["username"];
    if (empty($username)) {
        $username_error = "Username can't be empty.";
        $execute = false;
    } else if (strlen($username) < 4) {
        $username_error = "Username should contain at least 4 chars.";
        $execute = false;
    } else {
        $_SESSION["username"] = $username;
    }

    // validate the password
    $password = $_POST["password"];
    if (empty($password)) {
        $password_error = "Password can't be empty.";
        $execute = false;
    } else if (strlen($password) < 4) {
        $password_error = "Password should contain at least 4 chars.";
        $execute = false;
    }

    // validate repeated password
    $re_password = $_POST["re_password"];
    if (empty($re_password)) {
        $re_password_error = "Repeated password can't be empty.";
        $execute = false;
    } else if (strlen($re_password) < 4) {
        $re_password_error = "Repeated password should contain at least 4 chars.";
        $execute = false;
    } else if (strcmp($re_password, $password) !== 0) {
        $re_password_error = "Password should match with the repeated password.";
        $execute = false;
    }

    // validate the email
    $email = $_POST["email"];
    if (empty($email)) {
        $email_error = "Email can't be empty.";
        $execute = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Email you entered is invalid.";
        $execute = false;
    } else {
        $_SESSION["email"] = $email;
    }

    /* if all inputs are valid, then */
    if ($execute) {
        //generate the 5 char activation activation code
        $activationCode = substr(uniqid('', true), -5);

        /* perform validations related to database */
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $username_error = "Username already exists. Please choose another.";
            $execute = false;
        }

        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $email_error = "This email is associated with an account. Please enter the another email.";
            $execute = false;
        }

        $sql = "SELECT * FROM inactive_user WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $username_error = "An account has been requested with this username. Please choose another.";
            $execute = false;
        }

        $sql = "SELECT * FROM inactive_user WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $email_error = "An account has been requested for this email. Please enter the another email.";
            $execute = false;
        }
    }

    /* then, if all database constrains are preserved */
    if ($execute) {
        // insert user into inactive_users table - need account verification
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "INSERT INTO inactive_user (username, password, email, activation_code)
VALUES ('$username', '$password', '$email', '$activationCode')";
        if (mysqli_query($conn, $sql)) {
            // send an email with the activation code
            /*            $retval = mail("nishenkpeiris@gmail.com", "Activation Code", $activationCode);
                        if ($retval == true) {
                            echo "Message sent successfully...";
                        } else {
                            echo "Message could not be sent...";
                        }*/

            // redirect to account activation
            header("Location: activate_account.php");
            die();
        } else {
            $internal_error = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <!--<script src='https://www.google.com/recaptcha/api.js'></script>-->
    <title>Create a member's account</title>
</head>
<body>
<span><?php echo $internal_error; ?></span><br/>
<!-- htmlspecialchars is used to protect against XSS attacks -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!--<div class="g-recaptcha" data-sitekey="6Lce6hoUAAAAAOh37uDcc1MVtv6qY35GLF46FNJ2"></div>-->
    <label>Username :</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"
           autofocus="autofocus"/>
    <span><?php echo $username_error; ?></span><br/>
    <label>Password :</label>
    <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>"/>
    <span><?php echo $password_error; ?></span><br/>
    <label>Repeat password :</label>
    <input type="password" name="re_password"
           value="<?php echo htmlspecialchars($re_password); ?>"/>
    <span><?php echo $re_password_error; ?></span><br/>
    <label>Email :</label>
    <input type="email" name="email"
           value="<?php echo htmlspecialchars($email); ?>"/>
    <span><?php echo $email_error; ?></span><br/>
    <input type="submit" value=" Submit "/>
</form>
</body>
</html>