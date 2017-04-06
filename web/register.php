<?php require_once('header.php'); ?>
<?php
require_once '../config/config.php';

session_start();
$_SESSION["username"] = $_SESSION["email"] = "";

$username = $password = $re_password = $email = $activationCode = $username_error = $password_error = $re_password_error = $email_error = $internal_error = "";
$execute = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* validate all inputs */
    // validate the username
    $username = $_POST["username"];
    if (empty($username)) {
        $username_error = "Please enter a value.";
        $execute = false;
    } else if (strlen($username) < 4) {
        $username_error = "Username must be 4 characters or more.";
        $execute = false;
    } else {
        $_SESSION["username"] = $username;
    }

    // validate the password
    $password = $_POST["password"];
    if (empty($password)) {
        $password_error = "Please enter a value.";
        $execute = false;
    } else if (strlen($password) < 4) {
        $password_error = "Password must be 4 characters or more.";
        $execute = false;
    }

    // validate repeated password
    $re_password = $_POST["re_password"];
    if (empty($re_password)) {
        $re_password_error = "Please enter a value.";
        $execute = false;
    } else if (strlen($re_password) < 4) {
        $re_password_error = "Repeated password must be 4 characters or more.";
        $execute = false;
    } else if (strcmp($re_password, $password) !== 0) {
        $re_password_error = "Repeated password should match with the password.";
        $execute = false;
    }

    // validate the email
    $email = $_POST["email"];
    if (empty($email)) {
        $email_error = "Please enter a value.";
        $execute = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please enter a valid email address.";
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
            $username_error = "Someone's already using that username. If that’s you, enter your Email and password to sign in.";
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
            $retval = mail($email, "Activation Code", "Your are warmly welcome " . $username . ".\nYour activation code is " . $activationCode);
            if ($retval == true) {
                // redirect to account activation
                header("Location: activate_account.php");
                die();
            } else {
                $internal_error = "Server encountered with a problem while trying to send the activation code through email. Please try again later.";
            }
        } else {
            $internal_error = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>

    <main>
        <section id="reg_body_sec">
            <div class="container main_reg_container">
                <div class="row reg_content_row ">

                    <div class="col-md-6 col-md-push-3 form_top_wrp">
                        <div class="reg_form_wrp">

                            <div class="reg_header">
                                <h6>Don't you have an account?</h6>
                                <h2>Register As Member</h2>
                            </div>
                            <span><?php echo $internal_error; ?></span><br/>
                            <!--                             htmlspecialchars is used to protect against XSS attacks -->
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                                <div class="input_txt_wrp"><input type="text" type="text" name="username"
                                                                  value="<?php echo htmlspecialchars($username); ?>"
                                                                  autofocus="autofocus" placeholder="User Name"><i
                                            class="fa fa-user" aria-hidden="true"></i></div>
                                <span><?php echo $username_error; ?></span><br/>
                                <div class="input_txt_wrp"><input type="email" name="email" placeholder="Email"
                                                                  value="<?php echo htmlspecialchars($email); ?>"> <i
                                            class="fa fa-envelope" aria-hidden="true"></i></div>
                                <span><?php echo $email_error; ?></span><br/>
                                <div class="input_txt_wrp"><input type="password" name="password"
                                                                  placeholder="Password"> <i class="fa fa-lock"
                                                                                             aria-hidden="true"></i>
                                </div>
                                <span><?php echo $password_error; ?></span><br/>
                                <div class="input_txt_wrp"><input type="password" name="re_password"
                                                                  placeholder="Re-Enter the Password"> <i
                                            class="fa fa-lock" aria-hidden="true"></i></div>
                                <span><?php echo $re_password_error; ?></span><br/>
                                <div class="g-recaptcha" data-sitekey="6Lce6hoUAAAAAOh37uDcc1MVtv6qY35GLF46FNJ2"></div>
                                <div class="input_wrp">
                                    <input type="submit" class="btn log_form_submit" value="REGISTER NOW"/>
                                </div>

                            </form>


                        </div>
                    </div>
                </div>


            </div>
        </section>
    </main>


<?php require_once('footer.php'); ?>