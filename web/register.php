<?php
require_once '../config/config.php';
require_once 'header.php';

$internal_error = $username = $username_error = $password = $password_error = $email = $email_error = $activationCode = "";
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

    // validate the email
    $email = $_POST["email"];
    if (empty($email)) {
        $email_error = "Please enter a value.";
        $execute = false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please enter a valid email address.";
        $execute = false;
    }

    /* if all inputs are valid, then */
    if ($execute) {
        //generate the 5 char activation activation code
        $activationCode = substr(uniqid('', true), -5);

        /* perform validations related to database */
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $username_error = "Someone is already using this username. If that’s you, sign in with your username and password.";
            $execute = false;
        }

        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $email_error = "Someone is already using this email. If that’s you, sign in with your username and password.";
            $execute = false;
        }

        $sql = "SELECT * FROM inactive_user WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $username_error = "An account has been requested for this username. If that is you, we send you a mail with your activation code. Use it to activate your account.";
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            // redirect to activate_account page
            header("Location: activate_account.php");
            die();
        }

        $sql = "SELECT * FROM inactive_user WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $email_error = "An account has been requested for this email. If that is you, we send you a mail with your activation code. Use it to activate your account.";
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            // redirect to activate_account page
            header("Location: activate_account.php");
            die();
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
                // add info needed to verify the account, to the current session
                session_start();
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $email;
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
<<<<<<< HEAD
<<<<<<< HEAD
                                <div class="input_txt_wrp"><input type="password" name="re_password" placeholder="Re-Enter the Password"> <i class="fa fa-lock" aria-hidden="true"></i></div>
                                <span><?php echo $re_password_error; ?></span><br/>
                                <div class="captcha_wrp">
                                    <div class="g-recaptcha" data-sitekey="6Lce6hoUAAAAAOh37uDcc1MVtv6qY35GLF46FNJ2" ></div>
                                </div>
=======
                                <!-- add your API key as the data-sitekey -->
                                <div class="g-recaptcha" data-sitekey="6Lce6hoUAAAAAOh37uDcc1MVtv6qY35GLF46FNJ2"></div>
>>>>>>> 33035c22e74369a46c60b496b5ef25b28e312240
=======
                                <!-- add your API key as the data-sitekey -->
                                <div class="g-recaptcha" data-sitekey="6Lce6hoUAAAAAOh37uDcc1MVtv6qY35GLF46FNJ2"></div>
>>>>>>> f6601b6551c42442a93c322e2422e6174ee356e0
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

<?php require_once 'footer.php'; ?>