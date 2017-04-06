<?php
require_once '../config/config.php';
require_once 'header.php';

session_start();
$username = $_SESSION["username"];
$email = $_SESSION["email"];

$internal_error = $activation_code = $activation_code_error = "";

$activation_code = $activation_code_error = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($username)) {
        // no session exists, redirect to register page
        header("Location: register.php");
        die();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activation_code = $_POST["activation_code"];
    // compare the activation code entered by the user, with the activation code stored in the database
    $sql = "SELECT * FROM inactive_user WHERE username = '$username' AND activation_code = '$activation_code'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        /* activation code entered by user is correct */
        // move the user from inactive_users to users table
        while ($row = $result->fetch_assoc()) {
            $password = $row["password"];
        }

        /* delete the entry from inactive_user table */
        $sql = "DELETE FROM inactive_user WHERE username = '$username' AND activation_code = '$activation_code'; ";
        if (mysqli_query($conn, $sql)) {
            /* create an entry in user table */
            $sql = "INSERT INTO user (username, password, email) VALUES ('$username', '$password', '$email'); ";
            if (mysqli_query($conn, $sql)) {
                // redirect to home page
                header("Location: home.php");
                die();
            } else {
                $internal_error = "Error: " . $sql . " < br>" . mysqli_error($conn);
            }
        } else {
            $internal_error = "Error: " . $sql . " < br>" . mysqli_error($conn);
        }
    } else {
        $activation_code_error = "Activation code is invalid . Please check the activation code you entered.";
    }
    mysqli_close($conn);
}
?>

    <main>
        <section id="verification_sec">
            <div class="container verify_container">
                <div class="well col-sm-7 col-sm-push-3 verify_box">
                    <div class="col-sm-8">
                        <!--                <span>--><?php //echo $internal_error; ?><!--</span><br/>-->
                        <p>An email with your activation code has been sent to:<br>
                            <?php echo $email ?></p>
                        <!-- htmlspecialchars is used to protect against XSS attacks -->
                        <form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="clearfix">
                                <div class="left_content">
                                    <label>Activation code:</label>
                                    <input type="text" name="activation_code"
                                           value="<?php echo htmlspecialchars($activation_code); ?>"
                                           autofocus="autofocus"/>
                                    <span style="color:#ce4f43; font-size: 11px;"><?php echo $activation_code_error; ?></span><br/>
                                </div>
                                <div class="right_content">
                                    <input type="submit" value=" Verify " class="verify_btn"/>
                                </div>
                            </div>


                        </form>


                    </div>
                    <div class="col-sm-4 img_wrp">
                        <img class="img-responsive" src="../images/mail.png ">
                    </div>
                </div>
            </div>

        </section>
    </main>

<?php require_once('footer.php'); ?>