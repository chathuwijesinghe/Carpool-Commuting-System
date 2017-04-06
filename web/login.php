<?php require_once('header.php'); ?>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: root
 * Date: 4/2/17
 * Time: 8:14 AM
 */
require_once '../config/config.php';

session_start();

$internal_error = $login_error = $username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $login_error = "";

    // validate username and password against the database records
    $sql = "SELECT username FROM user WHERE username = '$username' and password = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;

        // redirect to home page
        header("Location: home.php");
        die();
    } else {
        $login_error = "Username or password incorrect. Please try again.";
    }
}

?>

    <main>
        <section id="login_body_sec">
            <div class="container main_login_container">
                <div class="row login_content_row ">

                    <div class="col-md-4 col-md-push-4 form_top_wrp">
                        <div class="login_form_wrp">

                            <div class="login_header">
                                <h6>Already have an account?</h6>
                                <h2>LOGIN</h2>
                            </div>

                            <span><?php echo $internal_error; ?></span><br/>
                            <!-- htmlspecialchars is used to protect against XSS attacks -->
                            <form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="input_txt_wrp"><input type="text" name="username"
                                                                  value="<?php echo htmlspecialchars($username); ?>"
                                                                  autofocus="autofocus" placeholder="User Name"><i
                                            class="fa fa-user" aria-hidden="true"></i></div>
                                <div class="input_txt_wrp"><input type="password" name="password"
                                                                  placeholder="Password"> <i class="fa fa-lock"
                                                                                             aria-hidden="true"></i>
                                </div>
                                <span style="color: #611f27; font-size: 13px; font-family: Roboto-Regular;"><?php echo $login_error; ?></span><br/>
                                <div class="input_wrp">
                                    <input type="submit" class="btn log_form_submit" value="Login to my account"/>
                                </div>
                            </form>

                            <div class="login_footer clearfix">
                                <div class="line">
                                    <h6>Don't have an account?</h6>
                                </div>
                                <div class="input_wrp">
                                    <a href="register.php"><input type="submit" class="btn reg_btn"
                                                                  value="Sign up for free!"/></a>
                                </div>


                            </div>


                        </div>
                    </div>
                </div>


            </div>
        </section>
    </main>


<?php require_once('footer.php'); ?>